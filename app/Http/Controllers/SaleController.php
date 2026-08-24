<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Transaction;
use App\Services\AccountingService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'items.product', 'ledgerTransaction']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('product_id')) {
            $productId = $request->product_id;
            $query->whereHas('items', function ($iq) use ($productId) {
                $iq->where('product_id', $productId);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        if ($request->filled('min_amount')) {
            $query->where('total_amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('total_amount', '<=', $request->max_amount);
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('invoice_date', 'asc')->orderBy('id', 'asc');
        } elseif ($sortBy === 'amount_desc') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sortBy === 'amount_asc') {
            $query->orderBy('total_amount', 'asc');
        } else {
            $query->orderBy('invoice_date', 'desc')->orderBy('id', 'desc');
        }

        $sales = $query->paginate(20)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('ajax')) {
            return view('sales._table', compact('sales'))->render();
        }

        $customers = Customer::orderBy('name')->get(['id', 'name', 'balance']);
        $products = Product::orderBy('name')->get(['id', 'name', 'stock']);

        return view('sales.index', compact('sales', 'customers', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date|before_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ], [
            'date.required' => 'يرجى تحديد تاريخ الفاتورة',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ فاتورة في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
        ]);

        DB::transaction(function () use ($request) {
            $customer = Customer::query()->lockForUpdate()->findOrFail($request->customer_id);
            $totalAmount = 0;

            // Generate invoice number safely
            $lastSale = Sale::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = $lastSale ? ($lastSale->id + 1) : 1;
            do {
                $invoiceNumber = 'INV-'.str_pad($nextId, 6, '0', STR_PAD_LEFT);
                $nextId++;
            } while (Sale::withTrashed()->where('invoice_number', $invoiceNumber)->exists());

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->date,
                'total_amount' => 0, // will update later
                'notes' => $request->notes,
            ]);

            $totalQuantity = 0;

            foreach ($request->items as $index => $itemData) {
                $product = Product::query()->lockForUpdate()->findOrFail($itemData['product_id']);
                app(InventoryService::class)->assertAvailable($product, $itemData['quantity'], "items.$index.quantity");
                $quantity = $itemData['quantity'];
                $price = $itemData['price'];
                $total = $quantity * $price;
                $totalAmount += $total;
                $totalQuantity += $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total' => $total,
                ]);

                // Update stock
                $product->stock -= $quantity;
                $product->save();

                // Log product transaction
                $product->transactions()->create([
                    'type' => 'sale',
                    'transaction_date' => $request->date,
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $sale->id,
                    'related_type' => Sale::class,
                    'notes' => 'فاتورة مبيعات رقم '.$invoiceNumber,
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            // Register the transaction in the ledger
            $paidAmount = $request->paid_amount ?: 0;
            $debtAdded = $totalAmount - $paidAmount;

            $customer->balance += $debtAdded;
            $customer->save();

            $customer->transactions()->create([
                'type' => 'sale',
                'transaction_date' => $request->date,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_after' => $customer->balance,
                'source_type' => Sale::class,
                'source_id' => $sale->id,
                'notes' => 'فاتورة مبيعات رقم '.$invoiceNumber.($request->notes ? ' - '.$request->notes : ''),
            ]);

            app(AccountingService::class)->recalculateParty($customer);
            Product::whereIn('id', collect($request->items)->pluck('product_id')->unique())
                ->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم تسجيل فاتورة المبيعات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Sale $sale)
    {
        $sale->load(['items.product', 'customer', 'ledgerTransaction']);

        if ($request->wantsJson() || $request->ajax()) {
            $transaction = $sale->ledgerTransaction ?: $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم '.$sale->invoice_number.'%')
                ->first();

            $paid_cash = $transaction ? $transaction->paid_amount : 0;
            $total_amount = $sale->total_amount;
            $paid_from_balance = 0;
            if ($transaction && $transaction->paid_amount > $total_amount) {
                $paid_cash = $total_amount;
                $paid_from_balance = $transaction->paid_amount - $total_amount;
            }

            $remaining_from_this = $total_amount - $paid_cash;

            $transactionData = null;
            if ($transaction) {
                $transactionData = [
                    'paid_cash' => $paid_cash,
                    'paid_from_balance' => $paid_from_balance,
                    'remaining_from_this' => max(0, $remaining_from_this),
                ];
            }

            return response()->json([
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'customer_id' => $sale->customer_id,
                'customer_name' => $sale->customer->name,
                'date' => $sale->invoice_date?->format('Y-m-d') ?? $sale->created_at->format('Y-m-d'),
                'notes' => $sale->notes,
                'total_amount' => $sale->total_amount,
                'items' => $sale->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total,
                    ];
                }),
                'transaction' => $transactionData,
            ]);
        }

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load(['items.product', 'customer']);
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date|before_or_equal:today',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ], [
            'date.required' => 'يرجى تحديد تاريخ الفاتورة',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ فاتورة في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
        ]);

        DB::transaction(function () use ($request, $sale) {
            $sale->load(['items', 'customer']);
            $oldCustomer = $sale->customer;
            $affectedProductIds = $sale->items->pluck('product_id')
                ->merge(collect($request->items)->pluck('product_id'))
                ->unique();
            Product::query()->whereIn('id', $affectedProductIds)->orderBy('id')->lockForUpdate()->get();

            // 1. FIND OLD TRANSACTION TO PRESERVE PAID AMOUNT
            $oldTransaction = $sale->ledgerTransaction()->first() ?: $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم '.$sale->invoice_number.'%')
                ->first();

            // Preserve the original paid_amount — do NOT re-apply it as a new payment.
            // Only use the new paid_amount if the user explicitly changed it from the original.
            $originalPaidAmount = $oldTransaction ? (float) $oldTransaction->paid_amount : 0;
            $newPaidAmountInput = $request->filled('paid_amount') ? (float) $request->paid_amount : null;

            // If user sent a value that differs from the original → use the new one.
            // If same or not sent → keep the original.
            $paidAmount = ($newPaidAmountInput !== null && $newPaidAmountInput !== $originalPaidAmount)
                ? $newPaidAmountInput
                : $originalPaidAmount;

            // 2. REVERT OLD TRANSACTION & STOCK
            if ($oldTransaction) {
                $oldDebtAdded = $oldTransaction->total_amount - $oldTransaction->paid_amount;
                $sale->customer->balance -= $oldDebtAdded;
                $sale->customer->save();
                $oldTransaction->delete();
            }

            // Reverse product stock
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            $sale->items()->delete();

            // Delete product transactions
            ProductTransaction::where('related_type', Sale::class)
                ->where('related_id', $sale->id)
                ->delete();

            // 3. IF CUSTOMER CHANGED, load the new customer
            $customer = Customer::query()->lockForUpdate()->findOrFail($request->customer_id);

            // 4. UPDATE SALE METADATA
            $sale->update([
                'customer_id' => $customer->id,
                'invoice_date' => $request->date,
                'notes' => $request->notes,
            ]);

            // 5. CREATE NEW ITEMS & UPDATE STOCK
            $totalAmount = 0;
            $totalQuantity = 0;

            foreach ($request->items as $index => $itemData) {
                $product = Product::query()->lockForUpdate()->findOrFail($itemData['product_id']);
                app(InventoryService::class)->assertAvailable($product, $itemData['quantity'], "items.$index.quantity");
                $quantity = $itemData['quantity'];
                $price = $itemData['price'];
                $total = $quantity * $price;
                $totalAmount += $total;
                $totalQuantity += $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total' => $total,
                ]);

                // Update stock (Selling decreases stock)
                $product->stock -= $quantity;
                $product->save();

                // Log product transaction
                $product->transactions()->create([
                    'type' => 'sale',
                    'transaction_date' => $request->date,
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $sale->id,
                    'related_type' => Sale::class,
                    'notes' => 'تعديل فاتورة مبيعات رقم '.$sale->invoice_number,
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            // 6. REGISTER NEW TRANSACTION (with preserved or updated paid_amount)
            $debtAdded = $totalAmount - $paidAmount;

            $customer->balance += $debtAdded;
            $customer->save();

            $customer->transactions()->create([
                'type' => 'sale',
                'transaction_date' => $request->date,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_after' => $customer->balance,
                'source_type' => Sale::class,
                'source_id' => $sale->id,
                'notes' => 'فاتورة مبيعات رقم '.$sale->invoice_number.($request->notes ? ' - '.$request->notes : ''),
            ]);

            collect([$oldCustomer, $customer])
                ->unique('id')
                ->each(fn (Customer $party) => app(AccountingService::class)->recalculateParty($party));
            Product::whereIn('id', $affectedProductIds)->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم تعديل فاتورة المبيعات بنجاح');
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load(['items', 'customer']);
            $affectedProductIds = $sale->items->pluck('product_id')->unique();

            // Find related transaction using exact note format
            $transaction = $sale->ledgerTransaction()->first() ?: $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم '.$sale->invoice_number.'%')
                ->first();

            // Reverse customer balance
            if ($transaction) {
                // The debt added was total_amount - paid_amount
                $debtAdded = $transaction->total_amount - $transaction->paid_amount;
                $sale->customer->balance -= $debtAdded;
                $sale->customer->save();
                $transaction->delete();
            }

            // Reverse product stock (since selling decreases stock, deleting increases it)
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $sale->items()->delete();

            // Delete product transactions
            ProductTransaction::where('related_type', Sale::class)
                ->where('related_id', $sale->id)
                ->delete();
            $sale->delete();

            app(AccountingService::class)->recalculateParty($sale->customer);
            Product::whereIn('id', $affectedProductIds)->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم حذف فاتورة المبيعات واسترجاع أرصدة المخزن والعميل بنجاح');
    }
}

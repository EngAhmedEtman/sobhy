<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Services\AccountingService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.product', 'ledgerTransaction']);
        $query->addSelect([
            'latest_party_invoice_id' => Purchase::query()
                ->from('purchases as party_purchases')
                ->select('party_purchases.id')
                ->whereColumn('party_purchases.supplier_id', 'purchases.supplier_id')
                ->whereNull('party_purchases.deleted_at')
                ->orderByDesc('party_purchases.id')
                ->limit(1),
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
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

        $purchases = $query->paginate(20)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('ajax')) {
            return view('purchases._table', compact('purchases'))->render();
        }

        $suppliers = Supplier::orderBy('name')->get(['id', 'name', 'balance']);
        $products = Product::orderBy('name')->get(['id', 'name', 'stock']);

        return view('purchases.index', compact('purchases', 'suppliers', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date|before_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ], [
            'supplier_id.required' => 'يرجى تحديد المورد',
            'supplier_id.exists' => 'المورد المحدد غير موجود',
            'date.required' => 'يرجى تحديد تاريخ الفاتورة',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ فاتورة في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
            'items.required' => 'يجب إضافة منتج واحد على الأقل للفاتورة',
            'items.min' => 'يجب إضافة منتج واحد على الأقل للفاتورة',
            'items.*.product_id.required' => 'يرجى تحديد المنتج',
            'items.*.product_id.exists' => 'المنتج المحدد غير موجود',
            'items.*.quantity.required' => 'يرجى تحديد الكمية',
            'items.*.price.required' => 'يرجى تحديد السعر',
        ]);

        DB::transaction(function () use ($request) {
            $supplier = Supplier::query()->lockForUpdate()->findOrFail($request->supplier_id);
            $totalAmount = 0;

            // Generate invoice number safely
            $lastPurchase = Purchase::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = $lastPurchase ? ($lastPurchase->id + 1) : 1;
            do {
                $invoiceNumber = 'PO-'.str_pad($nextId, 6, '0', STR_PAD_LEFT);
                $nextId++;
            } while (Purchase::withTrashed()->where('invoice_number', $invoiceNumber)->exists());

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->date,
                'total_amount' => 0, // will update later
                'notes' => $request->notes,
            ]);

            $totalQuantity = 0;

            foreach ($request->items as $itemData) {
                $product = Product::query()->lockForUpdate()->findOrFail($itemData['product_id']);
                $quantity = $itemData['quantity'];
                $price = $itemData['price'];
                $total = $quantity * $price;
                $totalAmount += $total;
                $totalQuantity += $quantity;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total' => $total,
                ]);

                // Update stock (Purchasing increases stock)
                $product->stock += $quantity;
                $product->save();

                // Log product transaction
                $product->transactions()->create([
                    'type' => 'purchase',
                    'transaction_date' => $request->date,
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $purchase->id,
                    'related_type' => Purchase::class,
                    'notes' => 'فاتورة مشتريات رقم '.$invoiceNumber,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);

            // Register the transaction in the ledger
            $paidAmount = $request->paid_amount ?: 0;
            $debtAdded = $totalAmount - $paidAmount;

            $supplier->balance += $debtAdded;
            $supplier->save();

            $supplier->transactions()->create([
                'type' => 'purchase',
                'transaction_date' => $request->date,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_after' => $supplier->balance,
                'source_type' => Purchase::class,
                'source_id' => $purchase->id,
                'notes' => 'فاتورة مشتريات رقم '.$invoiceNumber.($request->notes ? ' - '.$request->notes : ''),
            ]);

            app(AccountingService::class)->recalculateParty($supplier);
            Product::whereIn('id', collect($request->items)->pluck('product_id')->unique())
                ->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم تسجيل فاتورة المشتريات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier', 'ledgerTransaction']);

        if ($request->wantsJson() || $request->ajax()) {
            $transaction = $purchase->ledgerTransaction ?: $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم '.$purchase->invoice_number.'%')
                ->first();

            $paid_cash = $transaction ? $transaction->paid_amount : 0;
            $total_amount = $purchase->total_amount;
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
                'id' => $purchase->id,
                'invoice_number' => $purchase->invoice_number,
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier->name,
                'date' => $purchase->invoice_date?->format('Y-m-d') ?? $purchase->created_at->format('Y-m-d'),
                'notes' => $purchase->notes,
                'total_amount' => $purchase->total_amount,
                'can_edit' => $purchase->id === $purchase->supplier->purchases()->max('id'),
                'items' => $purchase->items->map(function ($item) {
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

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier']);
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date|before_or_equal:today',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ], [
            'supplier_id.required' => 'يرجى تحديد المورد',
            'supplier_id.exists' => 'المورد المحدد غير موجود',
            'date.required' => 'يرجى تحديد تاريخ الفاتورة',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ فاتورة في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
            'items.required' => 'يجب إضافة منتج واحد على الأقل للفاتورة',
            'items.min' => 'يجب إضافة منتج واحد على الأقل للفاتورة',
            'items.*.product_id.required' => 'يرجى تحديد المنتج',
            'items.*.product_id.exists' => 'المنتج المحدد غير موجود',
            'items.*.quantity.required' => 'يرجى تحديد الكمية',
            'items.*.price.required' => 'يرجى تحديد السعر',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            $purchase->load(['items', 'supplier']);
            $oldSupplier = $purchase->supplier;
            $affectedProductIds = $purchase->items->pluck('product_id')
                ->merge(collect($request->items)->pluck('product_id'))
                ->unique();
            Product::query()->whereIn('id', $affectedProductIds)->orderBy('id')->lockForUpdate()->get();

            // 1. FIND OLD TRANSACTION TO PRESERVE PAID AMOUNT
            $oldTransaction = $purchase->ledgerTransaction()->first() ?: $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم '.$purchase->invoice_number.'%')
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
                $purchase->supplier->balance -= $oldDebtAdded;
                $purchase->supplier->save();
                $oldTransaction->delete();
            }

            // Reverse product stock
            foreach ($purchase->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }
            $purchase->items()->delete();

            // Delete old product transactions for this purchase
            ProductTransaction::where('related_type', Purchase::class)
                ->where('related_id', $purchase->id)
                ->delete();

            // 3. IF SUPPLIER CHANGED, load the new supplier
            $supplier = Supplier::query()->lockForUpdate()->findOrFail($request->supplier_id);

            // 4. UPDATE PURCHASE METADATA
            $purchase->update([
                'supplier_id' => $supplier->id,
                'invoice_date' => $request->date,
                'notes' => $request->notes,
            ]);

            // 5. CREATE NEW ITEMS & UPDATE STOCK
            $totalAmount = 0;
            $totalQuantity = 0;

            foreach ($request->items as $itemData) {
                $product = Product::query()->lockForUpdate()->findOrFail($itemData['product_id']);
                $quantity = $itemData['quantity'];
                $price = $itemData['price'];
                $total = $quantity * $price;
                $totalAmount += $total;
                $totalQuantity += $quantity;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total' => $total,
                ]);

                // Update stock (Purchasing increases stock)
                $product->stock += $quantity;
                $product->save();

                // Log product transaction
                $product->transactions()->create([
                    'type' => 'purchase',
                    'transaction_date' => $request->date,
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $purchase->id,
                    'related_type' => Purchase::class,
                    'notes' => 'تعديل فاتورة مشتريات رقم '.$purchase->invoice_number,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);

            // 6. REGISTER NEW TRANSACTION (with preserved or updated paid_amount)
            $debtAdded = $totalAmount - $paidAmount;

            $supplier->balance += $debtAdded;
            $supplier->save();

            $supplier->transactions()->create([
                'type' => 'purchase',
                'transaction_date' => $request->date,
                'quantity' => $totalQuantity,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'balance_after' => $supplier->balance,
                'source_type' => Purchase::class,
                'source_id' => $purchase->id,
                'notes' => 'فاتورة مشتريات رقم '.$purchase->invoice_number.($request->notes ? ' - '.$request->notes : ''),
            ]);

            collect([$oldSupplier, $supplier])
                ->unique('id')
                ->each(fn (Supplier $party) => app(AccountingService::class)->recalculateParty($party));
            Product::whereIn('id', $affectedProductIds)->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم تعديل فاتورة المشتريات بنجاح');
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->load(['items', 'supplier']);
            $affectedProductIds = $purchase->items->pluck('product_id')->unique();

            // Find related transaction using exact note format
            $transaction = $purchase->ledgerTransaction()->first() ?: $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم '.$purchase->invoice_number.'%')
                ->first();

            // Reverse supplier balance
            if ($transaction) {
                // The debt added was total_amount - paid_amount
                $debtAdded = $transaction->total_amount - $transaction->paid_amount;
                $purchase->supplier->balance -= $debtAdded;
                $purchase->supplier->save();
                $transaction->delete();
            }

            // Reverse product stock (since purchasing increases stock, deleting decreases it)
            foreach ($purchase->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            $purchase->items()->delete();

            // Delete product transactions
            ProductTransaction::where('related_type', Purchase::class)
                ->where('related_id', $purchase->id)
                ->delete();
            $purchase->delete();

            app(AccountingService::class)->recalculateParty($purchase->supplier);
            Product::whereIn('id', $affectedProductIds)->get()
                ->each(fn (Product $product) => app(InventoryService::class)->recalculateProduct($product));
        });

        return back()->with('success', 'تم حذف الفاتورة واسترجاع أرصدة المخزن والمورد بنجاح');
    }
}

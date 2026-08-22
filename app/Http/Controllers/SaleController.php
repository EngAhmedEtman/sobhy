<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'items.product'])->orderBy('id', 'desc')->paginate(20);
        $customers = Customer::all(['id', 'name', 'balance']);
        $products = Product::all(['id', 'name', 'stock']);
        
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
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $customer = Customer::findOrFail($request->customer_id);
            $totalAmount = 0;

            // Generate invoice number
            $lastSale = Sale::orderBy('id', 'desc')->first();
            $nextId = $lastSale ? $lastSale->id + 1 : 1;
            $invoiceNumber = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $sale = Sale::create([
                'customer_id' => $customer->id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => 0, // will update later
                'notes' => $request->notes,
                'created_at' => $request->date . ' ' . date('H:i:s'),
            ]);

            $totalQuantity = 0;

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
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
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $sale->id,
                    'related_type' => Sale::class,
                    'notes' => 'فاتورة مبيعات رقم ' . $invoiceNumber,
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
                'notes' => 'فاتورة مبيعات رقم ' . $invoiceNumber . ($request->notes ? ' - ' . $request->notes : ''),
            ]);
        });

        return back()->with('success', 'تم تسجيل فاتورة المبيعات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Sale $sale)
    {
        $sale->load(['items.product', 'customer']);
        
        if ($request->wantsJson() || $request->ajax()) {
            $transaction = $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم ' . $sale->invoice_number . '%')
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
                    'remaining_from_this' => max(0, $remaining_from_this)
                ];
            }
            
            return response()->json([
                'id' => $sale->id,
                'invoice_number' => $sale->invoice_number,
                'customer_id' => $sale->customer_id,
                'customer_name' => $sale->customer->name,
                'date' => $sale->created_at->format('Y-m-d'),
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
                'transaction' => $transactionData
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
            'date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $sale) {
            $sale->load(['items', 'customer']);
            
            // 1. REVERT OLD TRANSACTION & STOCK
            // Find related transaction using exact note format
            $oldTransaction = $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم ' . $sale->invoice_number . '%')
                ->first();

            // Reverse customer balance
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
            \App\Models\ProductTransaction::where('related_type', Sale::class)
                ->where('related_id', $sale->id)
                ->delete();
            
            // 2. IF CUSTOMER CHANGED, we need to load the new customer
            $customer = Customer::findOrFail($request->customer_id);
            
            // 3. UPDATE SALE METADATA
            $sale->update([
                'customer_id' => $customer->id,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // 4. CREATE NEW ITEMS & UPDATE STOCK
            $totalAmount = 0;
            $totalQuantity = 0;

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
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
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $sale->id,
                    'related_type' => Sale::class,
                    'notes' => 'تعديل فاتورة مبيعات رقم ' . $sale->invoice_number,
                ]);
            }

            $sale->update(['total_amount' => $totalAmount]);

            // 5. REGISTER NEW TRANSACTION
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
                'notes' => 'فاتورة مبيعات رقم ' . $sale->invoice_number . ($request->notes ? ' - ' . $request->notes : ''),
            ]);
        });

        return back()->with('success', 'تم تعديل فاتورة المبيعات بنجاح');
    }
    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->load(['items', 'customer']);
            
            // Find related transaction using exact note format
            $transaction = $sale->customer->transactions()
                ->where('type', 'sale')
                ->where('notes', 'like', '%رقم ' . $sale->invoice_number . '%')
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
            \App\Models\ProductTransaction::where('related_type', Sale::class)
                ->where('related_id', $sale->id)
                ->delete();
            $sale->delete();
        });

        return back()->with('success', 'تم حذف فاتورة المبيعات واسترجاع أرصدة المخزن والعميل بنجاح');
    }
}

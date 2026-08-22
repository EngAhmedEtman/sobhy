<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'items.product'])->orderBy('id', 'desc')->paginate(20);
        $suppliers = Supplier::all(['id', 'name', 'balance']);
        $products = Product::all(['id', 'name', 'stock']);
        
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
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $supplier = Supplier::findOrFail($request->supplier_id);
            $totalAmount = 0;

            // Generate invoice number
            $lastPurchase = Purchase::orderBy('id', 'desc')->first();
            $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
            $invoiceNumber = 'PO-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
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
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $purchase->id,
                    'related_type' => Purchase::class,
                    'notes' => 'فاتورة مشتريات رقم ' . $invoiceNumber,
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
                'notes' => 'فاتورة مشتريات رقم ' . $invoiceNumber . ($request->notes ? ' - ' . $request->notes : ''),
            ]);
        });

        return back()->with('success', 'تم تسجيل فاتورة المشتريات بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier']);
        
        if ($request->wantsJson() || $request->ajax()) {
            $transaction = $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم ' . $purchase->invoice_number . '%')
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
                    'remaining_from_this' => max(0, $remaining_from_this)
                ];
            }
            
            return response()->json([
                'id' => $purchase->id,
                'invoice_number' => $purchase->invoice_number,
                'supplier_id' => $purchase->supplier_id,
                'supplier_name' => $purchase->supplier->name,
                'date' => $purchase->created_at->format('Y-m-d'),
                'notes' => $purchase->notes,
                'total_amount' => $purchase->total_amount,
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
                'transaction' => $transactionData
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
            'date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            $purchase->load(['items', 'supplier']);
            
            // 1. REVERT OLD TRANSACTION & STOCK
            // Find related transaction using exact note format
            $oldTransaction = $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم ' . $purchase->invoice_number . '%')
                ->first();

            // Reverse supplier balance
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
            \App\Models\ProductTransaction::where('related_type', Purchase::class)
                ->where('related_id', $purchase->id)
                ->delete();
            
            // 2. IF SUPPLIER CHANGED, we need to load the new supplier
            $supplier = Supplier::findOrFail($request->supplier_id);
            
            // 3. UPDATE PURCHASE METADATA
            $purchase->update([
                'supplier_id' => $supplier->id,
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
                    'quantity' => $quantity,
                    'balance_after' => $product->stock,
                    'related_id' => $purchase->id,
                    'related_type' => Purchase::class,
                    'notes' => 'تعديل فاتورة مشتريات رقم ' . $purchase->invoice_number,
                ]);
            }

            $purchase->update(['total_amount' => $totalAmount]);

            // 5. REGISTER NEW TRANSACTION
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
                'notes' => 'فاتورة مشتريات رقم ' . $purchase->invoice_number . ($request->notes ? ' - ' . $request->notes : ''),
            ]);
        });

        return back()->with('success', 'تم تعديل فاتورة المشتريات بنجاح');
    }

    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->load(['items', 'supplier']);
            
            // Find related transaction using exact note format
            $transaction = $purchase->supplier->transactions()
                ->where('type', 'purchase')
                ->where('notes', 'like', '%رقم ' . $purchase->invoice_number . '%')
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
            \App\Models\ProductTransaction::where('related_type', Purchase::class)
                ->where('related_id', $purchase->id)
                ->delete();
            $purchase->delete();
        });

        return back()->with('success', 'تم حذف الفاتورة واسترجاع أرصدة المخزن والمورد بنجاح');
    }
}

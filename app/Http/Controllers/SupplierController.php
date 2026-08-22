<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'balance' => 'required|numeric'
        ]);

        Supplier::create($request->all());

        return back()->with('success', 'تم إضافة المورد بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->only(['name', 'phone']));

        return back()->with('success', 'تم تعديل المورد بنجاح');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        
        $hasPurchases = \App\Models\Purchase::where('supplier_id', $id)->exists();
        $hasTransactions = $supplier->transactions()->exists();

        if ($hasPurchases || $hasTransactions) {
            return back()->with('error', 'لا يمكن حذف هذا المورد لوجود فواتير أو تعاملات سابقة مرتبطة به.');
        }

        $supplier->delete();

        return back()->with('success', 'تم حذف المورد بنجاح');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        $transactions = $supplier->transactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20, ['*'], 'page');
        
        $totalPurchases = $supplier->transactions()->where('type', 'purchase')->sum('total_amount');
        $totalPayments = $supplier->transactions()->where('type', 'payment_made')->sum('paid_amount');
        
        $products = \App\Models\Product::all(['id', 'name', 'stock']);

        return view('suppliers.show', compact('supplier', 'transactions', 'totalPurchases', 'totalPayments', 'products'));
    }

    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $supplier = Supplier::findOrFail($id);

        DB::transaction(function () use ($supplier, $request) {
            $amount = $request->amount;
            $newBalance = $supplier->balance - $amount; // Payment decreases supplier debt

            $supplier->transactions()->create([
                'type' => 'payment_made',
                'paid_amount' => $amount,
                'total_amount' => 0,
                'balance_after' => $newBalance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? 'سداد دفعة'
            ]);

            $supplier->update(['balance' => $newBalance]);
        });

        return back()->with('success', 'تم تسجيل السداد بنجاح');
    }

    public function storeReturn(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);

        $supplier = Supplier::findOrFail($id);

        DB::transaction(function () use ($supplier, $request) {
            $amount = $request->amount;
            $paidAmount = $request->paid_amount ?? 0;
            $newBalance = $supplier->balance - $amount + $paidAmount; // Return decreases supplier debt, paid back increases it

            $product = \App\Models\Product::findOrFail($request->product_id);
            // Returning a purchase to supplier means we give the product back, so stock DECREASES
            $product->decrement('stock', $request->quantity);

            $supplier->transactions()->create([
                'type' => 'return_purchase',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->amount / $request->quantity,
                'paid_amount' => $paidAmount,
                'total_amount' => $amount, 
                'balance_after' => $newBalance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? 'مرتجع ' . $product->name
            ]);

            $supplier->balance = $newBalance;
            $supplier->save();
        });

        return back()->with('success', 'تم تسجيل المرتجع بنجاح');
    }
}

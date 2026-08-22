<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('balance_status')) {
            if ($request->balance_status === 'debt') {
                $query->where('balance', '>', 0);
            } elseif ($request->balance_status === 'credit') {
                $query->where('balance', '<', 0);
            } elseif ($request->balance_status === 'zero') {
                $query->where('balance', 0);
            }
        }

        if ($request->filled('min_balance')) {
            $query->where('balance', '>=', $request->min_balance);
        }

        if ($request->filled('max_balance')) {
            $query->where('balance', '<=', $request->max_balance);
        }

        if ($request->filled('min_volume')) {
            $query->where(DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), '>=', $request->min_volume);
        }

        if ($request->filled('max_volume')) {
            $query->where(DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), '<=', $request->max_volume);
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('id', 'asc');
        } elseif ($sortBy === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortBy === 'balance_desc') {
            $query->orderBy('balance', 'desc');
        } elseif ($sortBy === 'balance_asc') {
            $query->orderBy('balance', 'asc');
        } elseif ($sortBy === 'volume_desc') {
            $query->orderBy(DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $customers = $query->paginate(20)->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->has('ajax')) {
            return view('customers._table', compact('customers'))->render();
        }

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'balance' => 'required|numeric'
        ]);

        Customer::create($request->all());

        return back()->with('success', 'تم إضافة العميل بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name', 'phone']));

        return back()->with('success', 'تم تعديل بيانات العميل بنجاح');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        $hasSales = \App\Models\Sale::where('customer_id', $id)->exists();
        $hasTransactions = $customer->transactions()->exists();

        if ($hasSales || $hasTransactions) {
            return back()->with('error', 'لا يمكن حذف العميل لوجود مبيعات أو تعاملات مالية مرتبطة به.');
        }

        $customer->delete();

        return back()->with('success', 'تم حذف العميل بنجاح');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        $transactions = $customer->transactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20, ['*'], 'page');
        
        $totalsales = $customer->transactions()->where('type', 'sale')->sum('total_amount');
        $totalPayments = $customer->transactions()->where('type', 'payment_received')->sum('paid_amount');
        
        $products = \App\Models\Product::all(['id', 'name', 'stock']);

        return view('customers.show', compact('customer', 'transactions', 'totalsales', 'totalPayments', 'products'));
    }

    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $customer = Customer::findOrFail($id);

        DB::transaction(function () use ($customer, $request) {
            $amount = $request->amount;
            $newBalance = $customer->balance - $amount;

            $customer->transactions()->create([
                'type' => 'payment_received',
                'paid_amount' => $amount,
                'total_amount' => 0,
                'balance_after' => $newBalance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? 'Ø¯ÙØ¹Ø© Ù†Ù‚Ø¯ÙŠØ©'
            ]);

            $customer->update(['balance' => $newBalance]);
        });

        return back()->with('success', 'ØªÙ… ØªØ³Ø¬ÙŠÙ„ Ø§Ù„Ø¯ÙØ¹Ø© Ø¨Ù†Ø¬Ø§Ø­');
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

        $customer = Customer::findOrFail($id);

        DB::transaction(function () use ($customer, $request) {
            $amount = $request->amount;
            $paidAmount = $request->paid_amount ?? 0;
            $newBalance = $customer->balance - $amount + $paidAmount; // Return reduces customer debt, paid back increases it

            $product = \App\Models\Product::findOrFail($request->product_id);
            // Returning a sale from customer means we get the product back, so stock INCREASES
            $product->increment('stock', $request->quantity);

            $customer->transactions()->create([
                'type' => 'return_sale',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->amount / $request->quantity,
                'paid_amount' => $paidAmount,
                'total_amount' => $amount, // We record it in total_amount to show value of goods
                'balance_after' => $newBalance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? 'Ù…Ø±ØªØ¬Ø¹ ' . $product->name
            ]);

            $customer->update(['balance' => $newBalance]);
        });

        return back()->with('success', 'تم تسجيل المرتجع بنجاح');
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Services\AccountingService;
use App\Services\InventoryService;
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
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
            'balance' => 'required|numeric',
        ], [
            'name.required' => 'يرجى إدخال اسم العميل',
            'name.regex' => 'اسم العميل يجب ألا يتكون من أرقام فقط',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط بدون أحرف',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 رقماً',
            'balance.required' => 'يرجى تحديد الرصيد الافتتاحي',
        ]);

        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'balance' => $request->balance,
            'opening_balance' => $request->balance,
        ]);

        return back()->with('success', 'تم إضافة العميل بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^(?!\d+$).+$/u'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]+$/'],
        ], [
            'name.required' => 'يرجى إدخال اسم العميل',
            'name.regex' => 'اسم العميل يجب ألا يتكون من أرقام فقط',
            'phone.regex' => 'رقم الهاتف يجب أن يحتوي على أرقام فقط بدون أحرف',
            'phone.max' => 'رقم الهاتف لا يجب أن يتجاوز 20 رقماً',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name', 'phone']));

        return back()->with('success', 'تم تعديل بيانات العميل بنجاح');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $hasSales = Sale::where('customer_id', $id)->exists();
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

        $products = Product::all(['id', 'name', 'stock']);
        $latestSaleId = $customer->sales()->max('id');

        return view('customers.show', compact('customer', 'transactions', 'totalsales', 'totalPayments', 'products', 'latestSaleId'));
    }

    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255',
        ], [
            'amount.required' => 'يرجى إدخال مبلغ التحصيل',
            'amount.min' => 'مبلغ التحصيل يجب أن يكون أكبر من 0',
            'date.required' => 'يرجى تحديد تاريخ التحصيل',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
        ]);

        DB::transaction(function () use ($id, $request) {
            $customer = Customer::query()->lockForUpdate()->findOrFail($id);
            $customer->transactions()->create([
                'type' => 'payment_received',
                'paid_amount' => $request->amount,
                'total_amount' => 0,
                'balance_after' => $customer->balance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? 'تحصيل دفعة نقدية',
            ]);

            app(AccountingService::class)->recalculateParty($customer);
        });

        return back()->with('success', 'تم تسجيل التحصيل بنجاح');
    }

    public function storeReturn(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'nullable|numeric|min:0',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255',
        ], [
            'product_id.required' => 'يرجى اختيار الصنف / المنتج المراد إرجاعه أولاً',
            'product_id.exists' => 'المنتج المحدد غير موجود في قاعدة البيانات',
            'quantity.required' => 'يرجى تحديد الكمية المسترجعة',
            'quantity.min' => 'الكمية المسترجعة يجب أن تكون أكبر من 0',
            'amount.required' => 'يرجى إدخال إجمالي قيمة المرتجع',
            'amount.min' => 'قيمة المرتجع يجب أن تكون أكبر من 0',
            'date.required' => 'يرجى تحديد تاريخ المرتجع',
            'date.before_or_equal' => 'لا يمكن تسجيل تاريخ في المستقبل، يجب أن يكون تاريخ اليوم أو تاريخ سابق',
        ]);

        DB::transaction(function () use ($id, $request) {
            $customer = Customer::query()->lockForUpdate()->findOrFail($id);
            $product = Product::query()->lockForUpdate()->findOrFail($request->product_id);

            $transaction = $customer->transactions()->create([
                'type' => 'return_sale',
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->amount / $request->quantity,
                'paid_amount' => $request->paid_amount ?? 0,
                'total_amount' => $request->amount,
                'balance_after' => $customer->balance,
                'transaction_date' => $request->date,
                'notes' => $request->notes ?? ('مرتجع بيع - '.$product->name),
            ]);

            $product->transactions()->create([
                'type' => 'return_sale',
                'transaction_date' => $request->date,
                'quantity' => $request->quantity,
                'balance_after' => $product->stock,
                'related_type' => Transaction::class,
                'related_id' => $transaction->id,
                'notes' => $transaction->notes,
            ]);

            app(AccountingService::class)->recalculateParty($customer);
            app(InventoryService::class)->recalculateProduct($product);
        });

        return back()->with('success', 'تم تسجيل المرتجع بنجاح');
    }
}

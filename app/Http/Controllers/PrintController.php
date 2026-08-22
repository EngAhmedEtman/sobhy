<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;

class PrintController extends Controller
{
    public function sale(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('print.invoice', [
            'type' => 'sale',
            'invoice' => $sale,
            'title' => 'فاتورة مبيعات رقم #' . $sale->id
        ]);
    }

    public function purchase(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        return view('print.invoice', [
            'type' => 'purchase',
            'invoice' => $purchase,
            'title' => 'فاتورة مشتريات رقم #' . $purchase->id
        ]);
    }

    public function customerStatement(Request $request, Customer $customer)
    {
        $query = Transaction::where('transactionable_type', Customer::class)
            ->where('transactionable_id', $customer->id)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        $filter = $request->query('filter', 'all');
        $subtitle = 'كشف حساب كامل';

        if ($filter === 'last_zero') {
            // Find the last transaction where balance_after was 0
            $lastZero = Transaction::where('transactionable_type', Customer::class)
                ->where('transactionable_id', $customer->id)
                ->where('balance_after', 0)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastZero) {
                $query->where('transaction_date', '>=', $lastZero->transaction_date)
                      ->where('id', '>=', $lastZero->id);
                $subtitle = 'منذ آخر تصفية حساب';
            }
        } elseif ($filter === 'last_n') {
            $n = (int) $request->query('n', 10);
            // Get the last N IDs first
            $latestIds = Transaction::where('transactionable_type', Customer::class)
                ->where('transactionable_id', $customer->id)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->take($n)
                ->pluck('id');
            
            $query->whereIn('id', $latestIds);
            $subtitle = 'آخر ' . $n . ' عمليات';
        }

        $transactions = $query->get();

        return view('print.statement', [
            'partyType' => 'customer',
            'party' => $customer,
            'transactions' => $transactions,
            'title' => 'كشف حساب عميل',
            'subtitle' => $subtitle
        ]);
    }

    public function supplierStatement(Request $request, Supplier $supplier)
    {
        $query = Transaction::where('transactionable_type', Supplier::class)
            ->where('transactionable_id', $supplier->id)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        $filter = $request->query('filter', 'all');
        $subtitle = 'كشف حساب كامل';

        if ($filter === 'last_zero') {
            // Find the last transaction where balance_after was 0
            $lastZero = Transaction::where('transactionable_type', Supplier::class)
                ->where('transactionable_id', $supplier->id)
                ->where('balance_after', 0)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            if ($lastZero) {
                $query->where('transaction_date', '>=', $lastZero->transaction_date)
                      ->where('id', '>=', $lastZero->id);
                $subtitle = 'منذ آخر تصفية حساب';
            }
        } elseif ($filter === 'last_n') {
            $n = (int) $request->query('n', 10);
            // Get the last N IDs first
            $latestIds = Transaction::where('transactionable_type', Supplier::class)
                ->where('transactionable_id', $supplier->id)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->take($n)
                ->pluck('id');
            
            $query->whereIn('id', $latestIds);
            $subtitle = 'آخر ' . $n . ' عمليات';
        }

        $transactions = $query->get();

        return view('print.statement', [
            'partyType' => 'supplier',
            'party' => $supplier,
            'transactions' => $transactions,
            'title' => 'كشف حساب مورد',
            'subtitle' => $subtitle
        ]);
    }

    public function customersReport(Request $request)
    {
        $query = Customer::query();
        $filtersApplied = [];

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
            $filtersApplied[] = "بحث: {$search}";
        }

        if ($request->filled('balance_status')) {
            if ($request->balance_status === 'debt') {
                $query->where('balance', '>', 0);
                $filtersApplied[] = "الحالة: مطلوب منه مديونية";
            } elseif ($request->balance_status === 'credit') {
                $query->where('balance', '<', 0);
                $filtersApplied[] = "الحالة: له رصيد دائن";
            } elseif ($request->balance_status === 'zero') {
                $query->where('balance', 0);
                $filtersApplied[] = "الحالة: خالص (صفر)";
            }
        }

        if ($request->filled('min_balance')) {
            $query->where('balance', '>=', $request->min_balance);
            $filtersApplied[] = "من رصيد: " . format_amount($request->min_balance);
        }

        if ($request->filled('max_balance')) {
            $query->where('balance', '<=', $request->max_balance);
            $filtersApplied[] = "إلى رصيد: " . format_amount($request->max_balance);
        }

        if ($request->filled('min_volume')) {
            $query->where(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), '>=', $request->min_volume);
            $filtersApplied[] = "من تعاملات: " . format_amount($request->min_volume);
        }

        if ($request->filled('max_volume')) {
            $query->where(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), '<=', $request->max_volume);
            $filtersApplied[] = "إلى تعاملات: " . format_amount($request->max_volume);
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
            $query->orderBy(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM sales WHERE sales.customer_id = customers.id)'), 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $customers = $query->get();

        $subtitle = count($filtersApplied) > 0 ? implode(' | ', $filtersApplied) : 'كافة العملاء المسجلين';

        return view('print.customers-report', [
            'customers' => $customers,
            'title' => 'تقرير قائمة العملاء والأرصدة',
            'subtitle' => $subtitle,
            'totalCustomers' => $customers->count(),
            'totalDebt' => $customers->where('balance', '>', 0)->sum('balance'),
            'totalCredit' => abs($customers->where('balance', '<', 0)->sum('balance')),
            'netBalance' => $customers->sum('balance'),
        ]);
    }

    public function suppliersReport(Request $request)
    {
        $query = Supplier::query();
        $filtersApplied = [];

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
            $filtersApplied[] = "بحث: {$search}";
        }

        if ($request->filled('balance_status')) {
            if ($request->balance_status === 'debt') {
                $query->where('balance', '>', 0);
                $filtersApplied[] = "الحالة: مستحق للمورد (له علينا)";
            } elseif ($request->balance_status === 'credit') {
                $query->where('balance', '<', 0);
                $filtersApplied[] = "الحالة: لنا عنده (دافعين زيادة)";
            } elseif ($request->balance_status === 'zero') {
                $query->where('balance', 0);
                $filtersApplied[] = "الحالة: خالص (صفر)";
            }
        }

        if ($request->filled('min_balance')) {
            $query->where('balance', '>=', $request->min_balance);
            $filtersApplied[] = "من رصيد: " . format_amount($request->min_balance);
        }

        if ($request->filled('max_balance')) {
            $query->where('balance', '<=', $request->max_balance);
            $filtersApplied[] = "إلى رصيد: " . format_amount($request->max_balance);
        }

        if ($request->filled('min_volume')) {
            $query->where(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM purchases WHERE purchases.supplier_id = suppliers.id)'), '>=', $request->min_volume);
            $filtersApplied[] = "من تعاملات: " . format_amount($request->min_volume);
        }

        if ($request->filled('max_volume')) {
            $query->where(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM purchases WHERE purchases.supplier_id = suppliers.id)'), '<=', $request->max_volume);
            $filtersApplied[] = "إلى تعاملات: " . format_amount($request->max_volume);
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
            $query->orderBy(\Illuminate\Support\Facades\DB::raw('(SELECT COALESCE(SUM(total_amount), 0) FROM purchases WHERE purchases.supplier_id = suppliers.id)'), 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $suppliers = $query->get();

        $subtitle = count($filtersApplied) > 0 ? implode(' | ', $filtersApplied) : 'كافة الموردين والشركات المسجلة';

        return view('print.suppliers-report', [
            'suppliers' => $suppliers,
            'title' => 'تقرير قائمة الموردين والأرصدة المستحقة',
            'subtitle' => $subtitle,
            'totalSuppliers' => $suppliers->count(),
            'totalDebt' => $suppliers->where('balance', '>', 0)->sum('balance'),
            'totalCredit' => abs($suppliers->where('balance', '<', 0)->sum('balance')),
            'netBalance' => $suppliers->sum('balance'),
        ]);
    }

    public function salesReport(Request $request)
    {
        $query = Sale::with(['customer', 'items.product']);
        $filtersApplied = [];

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanSearch = ltrim(preg_replace('/[^0-9]/', '', $search), '0');
            $query->where(function ($q) use ($search, $cleanSearch) {
                if (!empty($cleanSearch)) {
                    $q->where('id', $cleanSearch);
                }
                $q->orWhereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
            $filtersApplied[] = "بحث: {$search}";
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
            $customer = Customer::find($request->customer_id);
            if ($customer) {
                $filtersApplied[] = "العميل: {$customer->name}";
            }
        }

        if ($request->filled('product_id')) {
            $query->whereHas('items', function ($iq) use ($request) {
                $iq->where('product_id', $request->product_id);
            });
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $filtersApplied[] = "الصنف: {$product->name}";
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
            $filtersApplied[] = "من تاريخ: {$request->start_date}";
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
            $filtersApplied[] = "إلى تاريخ: {$request->end_date}";
        }

        if ($request->filled('min_amount')) {
            $query->where('total_amount', '>=', $request->min_amount);
            $filtersApplied[] = "من مبلغ: " . format_amount($request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('total_amount', '<=', $request->max_amount);
            $filtersApplied[] = "إلى مبلغ: " . format_amount($request->max_amount);
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sortBy === 'amount_desc') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sortBy === 'amount_asc') {
            $query->orderBy('total_amount', 'asc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $sales = $query->get();

        $subtitle = count($filtersApplied) > 0 ? implode(' | ', $filtersApplied) : 'كافة فواتير المبيعات';

        return view('print.sales-report', [
            'sales' => $sales,
            'title' => 'تقرير فواتير المبيعات',
            'subtitle' => $subtitle,
            'totalInvoices' => $sales->count(),
            'totalAmount' => $sales->sum('total_amount'),
            'totalPaid' => $sales->sum('paid_amount'),
            'totalRemaining' => $sales->sum('remaining_amount'),
        ]);
    }

    public function purchasesReport(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.product']);
        $filtersApplied = [];

        if ($request->filled('search')) {
            $search = trim($request->search);
            $cleanSearch = ltrim(preg_replace('/[^0-9]/', '', $search), '0');
            $query->where(function ($q) use ($search, $cleanSearch) {
                if (!empty($cleanSearch)) {
                    $q->where('id', $cleanSearch);
                }
                $q->orWhereHas('supplier', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhere('notes', 'like', "%{$search}%");
            });
            $filtersApplied[] = "بحث: {$search}";
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
            $supplier = Supplier::find($request->supplier_id);
            if ($supplier) {
                $filtersApplied[] = "المورد: {$supplier->name}";
            }
        }

        if ($request->filled('product_id')) {
            $query->whereHas('items', function ($iq) use ($request) {
                $iq->where('product_id', $request->product_id);
            });
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $filtersApplied[] = "الصنف: {$product->name}";
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
            $filtersApplied[] = "من تاريخ: {$request->start_date}";
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
            $filtersApplied[] = "إلى تاريخ: {$request->end_date}";
        }

        if ($request->filled('min_amount')) {
            $query->where('total_amount', '>=', $request->min_amount);
            $filtersApplied[] = "من مبلغ: " . format_amount($request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('total_amount', '<=', $request->max_amount);
            $filtersApplied[] = "إلى مبلغ: " . format_amount($request->max_amount);
        }

        $sortBy = $request->get('sort_by', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sortBy === 'amount_desc') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sortBy === 'amount_asc') {
            $query->orderBy('total_amount', 'asc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $purchases = $query->get();

        $subtitle = count($filtersApplied) > 0 ? implode(' | ', $filtersApplied) : 'كافة فواتير المشتريات';

        return view('print.purchases-report', [
            'purchases' => $purchases,
            'title' => 'تقرير فواتير المشتريات',
            'subtitle' => $subtitle,
            'totalInvoices' => $purchases->count(),
            'totalAmount' => $purchases->sum('total_amount'),
            'totalPaid' => $purchases->sum('paid_amount'),
            'totalRemaining' => $purchases->sum('remaining_amount'),
        ]);
    }
}

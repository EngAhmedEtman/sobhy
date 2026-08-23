<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Sales Metrics
        $todaySales = Sale::whereDate('created_at', $today)
            ->selectRaw('COUNT(*) as aggregate_count, COALESCE(SUM(total_amount), 0) as aggregate_total')
            ->first();
        $todaySalesCount = (int) $todaySales->aggregate_count;
        $todaySalesTotal = (float) $todaySales->aggregate_total;
        $monthSalesTotal = Sale::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // Purchases Metrics
        $todayPurchases = Purchase::whereDate('created_at', $today)
            ->selectRaw('COUNT(*) as aggregate_count, COALESCE(SUM(total_amount), 0) as aggregate_total')
            ->first();
        $todayPurchasesCount = (int) $todayPurchases->aggregate_count;
        $todayPurchasesTotal = (float) $todayPurchases->aggregate_total;
        $monthPurchasesTotal = Purchase::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // Cash Flow Today (from transactions)
        // Cash In: payments received from customers + paid amount in sales today
        $todayCashIn = Transaction::whereDate('transaction_date', $today)
            ->where(function ($q) {
                $q->where('type', 'payment_received')
                  ->orWhere(function ($sub) {
                      $sub->where('type', 'sale')->where('paid_amount', '>', 0);
                  });
            })
            ->sum('paid_amount');

        // Cash Out: payments made to suppliers + paid amount in purchases today + cash refunded on returns
        $todayCashOut = Transaction::whereDate('transaction_date', $today)
            ->where(function ($q) {
                $q->where('type', 'payment_made')
                  ->orWhere(function ($sub) {
                      $sub->where('type', 'purchase')->where('paid_amount', '>', 0);
                  })
                  ->orWhere(function ($sub) {
                      $sub->where('type', 'return_sale')->where('paid_amount', '>', 0);
                  });
            })
            ->sum('paid_amount');

        $todayNetCash = $todayCashIn - $todayCashOut;

        // Debt Overview
        $customerDebt = Customer::where('balance', '>', 0)
            ->selectRaw('COUNT(*) as aggregate_count, COALESCE(SUM(balance), 0) as aggregate_total')
            ->first();
        $totalCustomersDebt = (float) $customerDebt->aggregate_total;
        $customersDebtCount = (int) $customerDebt->aggregate_count;

        $supplierDebt = Supplier::where('balance', '>', 0)
            ->selectRaw('COUNT(*) as aggregate_count, COALESCE(SUM(balance), 0) as aggregate_total')
            ->first();
        $totalSuppliersDebt = (float) $supplierDebt->aggregate_total;
        $suppliersDebtCount = (int) $supplierDebt->aggregate_count;

        // Stock Overview
        $stock = Product::selectRaw('COUNT(*) as aggregate_count, COALESCE(SUM(stock), 0) as aggregate_total, SUM(CASE WHEN stock <= 100 THEN 1 ELSE 0 END) as low_stock_count')
            ->first();
        $totalStockWeight = (float) $stock->aggregate_total;
        $productsCount = (int) $stock->aggregate_count;
        $lowStockProductsCount = (int) $stock->low_stock_count;

        // Top Debtors (Customers who owe money)
        $topDebtorCustomers = Customer::where('balance', '>', 0)
            ->orderBy('balance', 'desc')
            ->take(5)
            ->get();

        // Top Creditors (Suppliers we owe money to)
        $topCreditorSuppliers = Supplier::where('balance', '>', 0)
            ->orderBy('balance', 'desc')
            ->take(5)
            ->get();

        // Stock Inventory list
        $topProducts = Product::orderBy('stock', 'desc')->take(6)->get();

        // Recent Activity Feed (Latest 8 transactions)
        $recentTransactions = Transaction::with(['transactionable', 'product'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Entities for Quick Modals
        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('dashboard', compact(
            'todaySalesCount',
            'todaySalesTotal',
            'monthSalesTotal',
            'todayPurchasesCount',
            'todayPurchasesTotal',
            'monthPurchasesTotal',
            'todayCashIn',
            'todayCashOut',
            'todayNetCash',
            'totalCustomersDebt',
            'customersDebtCount',
            'totalSuppliersDebt',
            'suppliersDebtCount',
            'totalStockWeight',
            'productsCount',
            'lowStockProductsCount',
            'topDebtorCustomers',
            'topCreditorSuppliers',
            'topProducts',
            'recentTransactions',
            'customers',
            'suppliers',
            'products'
        ));
    }
}

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
        $todaySales = Sale::whereDate('created_at', $today)->get();
        $todaySalesCount = $todaySales->count();
        $todaySalesTotal = $todaySales->sum('total_amount');
        $monthSalesTotal = Sale::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // Purchases Metrics
        $todayPurchases = Purchase::whereDate('created_at', $today)->get();
        $todayPurchasesCount = $todayPurchases->count();
        $todayPurchasesTotal = $todayPurchases->sum('total_amount');
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
        $totalCustomersDebt = Customer::where('balance', '>', 0)->sum('balance');
        $customersDebtCount = Customer::where('balance', '>', 0)->count();

        $totalSuppliersDebt = Supplier::where('balance', '>', 0)->sum('balance');
        $suppliersDebtCount = Supplier::where('balance', '>', 0)->count();

        // Stock Overview
        $totalStockWeight = Product::sum('stock');
        $productsCount = Product::count();
        $lowStockProductsCount = Product::where('stock', '<=', 100)->count();

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
        $customers = Customer::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

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

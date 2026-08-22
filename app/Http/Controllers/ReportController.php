<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'items.product'])->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $sales = $query->get();
        
        $totalAmount = $sales->sum('total_amount');
        $totalPaid = $sales->sum('paid_amount');
        $totalRemaining = $sales->sum('remaining_amount');

        return view('reports.sales', compact('sales', 'totalAmount', 'totalPaid', 'totalRemaining'));
    }

    public function purchases(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.product'])->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $purchases = $query->get();
        
        $totalAmount = $purchases->sum('total_amount');
        $totalPaid = $purchases->sum('paid_amount');
        $totalRemaining = $purchases->sum('remaining_amount');

        return view('reports.purchases', compact('purchases', 'totalAmount', 'totalPaid', 'totalRemaining'));
    }

    public function customers(Request $request)
    {
        $customersList = \App\Models\Customer::orderBy('name')->get();
        $customer = null;
        $transactions = [];
        $totalsales = 0;
        $totalPayments = 0;

        if ($request->filled('customer_id')) {
            $customer = \App\Models\Customer::findOrFail($request->customer_id);
            
            $query = $customer->transactions()->orderBy('transaction_date', 'desc')->orderBy('id', 'desc');
            
            // 1. Transaction Type Filter
            if ($request->filled('transaction_type') && $request->transaction_type !== 'all') {
                $query->where('type', $request->transaction_type);
            }

            // 2. Period Filter
            if ($request->filter_type === 'since_last_zero') {
                // Find the last transaction where the customer's balance became 0
                $lastZeroTransaction = $customer->transactions()
                    ->where('balance_after', 0)
                    ->orderBy('transaction_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($lastZeroTransaction) {
                    $query->where('id', '>', $lastZeroTransaction->id);
                }
            } else {
                // Standard Date Filter
                if ($request->filled('start_date')) {
                    $query->whereDate('transaction_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('transaction_date', '<=', $request->end_date);
                }
            }
            
            $transactions = $query->get();
            $totalsales = $transactions->where('type', 'sale')->sum('total_amount');
            $totalPayments = $transactions->where('type', 'payment_received')->sum('paid_amount');
        }

        return view('reports.customers', compact('customersList', 'customer', 'transactions', 'totalsales', 'totalPayments'));
    }

    public function suppliers(Request $request)
    {
        $suppliersList = \App\Models\Supplier::orderBy('name')->get();
        $supplier = null;
        $transactions = [];
        $totalPurchases = 0;
        $totalPayments = 0;

        if ($request->filled('supplier_id')) {
            $supplier = \App\Models\Supplier::findOrFail($request->supplier_id);
            
            $query = $supplier->transactions()->orderBy('transaction_date', 'desc')->orderBy('id', 'desc');
            
            // 1. Transaction Type Filter
            if ($request->filled('transaction_type') && $request->transaction_type !== 'all') {
                $query->where('type', $request->transaction_type);
            }

            // 2. Period Filter
            if ($request->filter_type === 'since_last_zero') {
                // Find the last transaction where the supplier's balance became 0
                $lastZeroTransaction = $supplier->transactions()
                    ->where('balance_after', 0)
                    ->orderBy('transaction_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($lastZeroTransaction) {
                    $query->where('id', '>', $lastZeroTransaction->id);
                }
            } else {
                // Standard Date Filter
                if ($request->filled('start_date')) {
                    $query->whereDate('transaction_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('transaction_date', '<=', $request->end_date);
                }
            }
            
            $transactions = $query->get();
            $totalPurchases = $transactions->where('type', 'purchase')->sum('total_amount');
            $totalPayments = $transactions->where('type', 'payment_made')->sum('paid_amount');
        }

        return view('reports.suppliers', compact('suppliersList', 'supplier', 'transactions', 'totalPurchases', 'totalPayments'));
    }

    public function products(Request $request)
    {
        $productsList = \App\Models\Product::orderBy('name')->get();
        $product = null;
        $transactions = collect();
        $totalIn = 0;
        $totalOut = 0;
        
        if ($request->filled('product_id')) {
            $product = \App\Models\Product::findOrFail($request->product_id);
            
            $query = $product->transactions()->orderBy('created_at', 'asc')->orderBy('id', 'asc');
            
            if ($request->filled('transaction_type') && $request->transaction_type !== 'all') {
                if ($request->transaction_type === 'in') {
                    $query->whereIn('type', ['purchase', 'adjustment_add']);
                } elseif ($request->transaction_type === 'out') {
                    $query->whereIn('type', ['sale', 'adjustment_sub']);
                }
            }
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            $transactions = $query->get();
            
            $totalIn = $product->transactions()
                ->whereIn('type', ['purchase', 'adjustment_add'])
                ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
                ->sum('quantity');
                
            $totalOut = $product->transactions()
                ->whereIn('type', ['sale', 'adjustment_sub'])
                ->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
                ->sum('quantity');
        }

        return view('reports.products', compact('productsList', 'product', 'transactions', 'totalIn', 'totalOut'));
    }

    public function profit(Request $request)
    {
        // Fetch Sales and Purchases for the period
        $salesQuery = \App\Models\Sale::query();
        $purchasesQuery = \App\Models\Purchase::query();
        $paymentsReceivedQuery = \App\Models\Transaction::where('transactionable_type', \App\Models\Customer::class)->where('type', 'payment_received');
        $paymentsMadeQuery = \App\Models\Transaction::where('transactionable_type', \App\Models\Supplier::class)->where('type', 'payment_made');

        if ($request->filled('start_date')) {
            $salesQuery->whereDate('created_at', '>=', $request->start_date);
            $purchasesQuery->whereDate('created_at', '>=', $request->start_date);
            $paymentsReceivedQuery->whereDate('transaction_date', '>=', $request->start_date);
            $paymentsMadeQuery->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $salesQuery->whereDate('created_at', '<=', $request->end_date);
            $purchasesQuery->whereDate('created_at', '<=', $request->end_date);
            $paymentsReceivedQuery->whereDate('transaction_date', '<=', $request->end_date);
            $paymentsMadeQuery->whereDate('transaction_date', '<=', $request->end_date);
        }

        $totalSales = $salesQuery->sum('total_amount');
        $totalPurchases = $purchasesQuery->sum('total_amount');
        
        $totalPaymentsReceived = $paymentsReceivedQuery->sum('paid_amount');
        $totalPaymentsMade = $paymentsMadeQuery->sum('paid_amount');

        // Global Debts
        $customersDebt = \App\Models\Customer::where('balance', '>', 0)->sum('balance');
        $suppliersDebt = \App\Models\Supplier::where('balance', '>', 0)->sum('balance');

        // Calculations
        $cashProfit = $totalPaymentsReceived - $totalPaymentsMade;
        $accrualProfit = $totalSales - $totalPurchases;

        return view('reports.profit', compact(
            'totalSales',
            'totalPurchases',
            'totalPaymentsReceived',
            'totalPaymentsMade',
            'customersDebt',
            'suppliersDebt',
            'cashProfit',
            'accrualProfit'
        ));
    }

    public function debts()
    {
        $customers = \App\Models\Customer::where('balance', '>', 0)->orderBy('id', 'desc')->get();
        $suppliers = \App\Models\Supplier::where('balance', '>', 0)->orderBy('id', 'desc')->get();

        $totalCustomersDebt = $customers->sum('balance');
        $totalSuppliersDebt = $suppliers->sum('balance');

        return view('reports.debts', compact('customers', 'suppliers', 'totalCustomersDebt', 'totalSuppliersDebt'));
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['customer', 'items.product', 'ledgerTransaction'])->orderBy('invoice_date', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        $sales = $query->get();

        $totalAmount = $sales->sum('total_amount');
        $totalPaid = $sales->sum('paid_amount');
        $totalRemaining = $sales->sum('remaining_amount');

        return view('reports.sales', compact('sales', 'totalAmount', 'totalPaid', 'totalRemaining'));
    }

    public function purchases(Request $request)
    {
        $query = Purchase::with(['supplier', 'items.product', 'ledgerTransaction'])->orderBy('invoice_date', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        $purchases = $query->get();

        $totalAmount = $purchases->sum('total_amount');
        $totalPaid = $purchases->sum('paid_amount');
        $totalRemaining = $purchases->sum('remaining_amount');

        return view('reports.purchases', compact('purchases', 'totalAmount', 'totalPaid', 'totalRemaining'));
    }

    public function customers(Request $request)
    {
        $customersList = Customer::orderBy('name')->get();
        $customer = null;
        $transactions = [];
        $totalsales = 0;
        $totalPayments = 0;

        if ($request->filled('customer_id')) {
            $customer = Customer::findOrFail($request->customer_id);

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
                    $query->where(function ($afterZero) use ($lastZeroTransaction) {
                        $afterZero->where('transaction_date', '>', $lastZeroTransaction->transaction_date)
                            ->orWhere(function ($sameDate) use ($lastZeroTransaction) {
                                $sameDate->whereDate('transaction_date', $lastZeroTransaction->transaction_date)
                                    ->where('id', '>', $lastZeroTransaction->id);
                            });
                    });
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
        $suppliersList = Supplier::orderBy('name')->get();
        $supplier = null;
        $transactions = [];
        $totalPurchases = 0;
        $totalPayments = 0;

        if ($request->filled('supplier_id')) {
            $supplier = Supplier::findOrFail($request->supplier_id);

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
                    $query->where(function ($afterZero) use ($lastZeroTransaction) {
                        $afterZero->where('transaction_date', '>', $lastZeroTransaction->transaction_date)
                            ->orWhere(function ($sameDate) use ($lastZeroTransaction) {
                                $sameDate->whereDate('transaction_date', $lastZeroTransaction->transaction_date)
                                    ->where('id', '>', $lastZeroTransaction->id);
                            });
                    });
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
            $totalPayments = $transactions->whereIn('type', ['payment_made', 'payment_sent'])->sum('paid_amount');
        }

        return view('reports.suppliers', compact('suppliersList', 'supplier', 'transactions', 'totalPurchases', 'totalPayments'));
    }

    public function products(Request $request)
    {
        $productsList = Product::orderBy('name')->get();
        $product = null;
        $transactions = collect();
        $totalIn = 0;
        $totalOut = 0;

        if ($request->filled('product_id')) {
            $product = Product::findOrFail($request->product_id);

            $query = $product->transactions()->orderBy('transaction_date', 'asc')->orderBy('id', 'asc');

            if ($request->filled('transaction_type') && $request->transaction_type !== 'all') {
                if ($request->transaction_type === 'in') {
                    $query->whereIn('type', ['purchase', 'return_sale', 'adjustment_add']);
                } elseif ($request->transaction_type === 'out') {
                    $query->whereIn('type', ['sale', 'return_purchase', 'adjustment_sub']);
                }
            }

            if ($request->filled('start_date')) {
                $query->whereDate('transaction_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('transaction_date', '<=', $request->end_date);
            }

            $transactions = $query->get();

            $totalIn = $product->transactions()
                ->whereIn('type', ['purchase', 'return_sale', 'adjustment_add'])
                ->when($request->start_date, fn ($q) => $q->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->end_date, fn ($q) => $q->whereDate('transaction_date', '<=', $request->end_date))
                ->sum('quantity');

            $totalOut = $product->transactions()
                ->whereIn('type', ['sale', 'return_purchase', 'adjustment_sub'])
                ->when($request->start_date, fn ($q) => $q->whereDate('transaction_date', '>=', $request->start_date))
                ->when($request->end_date, fn ($q) => $q->whereDate('transaction_date', '<=', $request->end_date))
                ->sum('quantity');
        }

        return view('reports.products', compact('productsList', 'product', 'transactions', 'totalIn', 'totalOut'));
    }

    public function profit(Request $request)
    {
        $salesQuery = Sale::query();
        $purchasesQuery = Purchase::query();
        $cashInQuery = Transaction::query()->where(function ($query) {
            $query->where(function ($customerCash) {
                $customerCash->where('transactionable_type', Customer::class)
                    ->whereIn('type', ['sale', 'payment_received']);
            })->orWhere(function ($supplierReturnCash) {
                $supplierReturnCash->where('transactionable_type', Supplier::class)
                    ->where('type', 'return_purchase');
            });
        });
        $cashOutQuery = Transaction::query()->where(function ($query) {
            $query->where(function ($supplierCash) {
                $supplierCash->where('transactionable_type', Supplier::class)
                    ->whereIn('type', ['purchase', 'payment_made', 'payment_sent']);
            })->orWhere(function ($customerReturnCash) {
                $customerReturnCash->where('transactionable_type', Customer::class)
                    ->where('type', 'return_sale');
            });
        });

        if ($request->filled('start_date')) {
            $salesQuery->whereDate('invoice_date', '>=', $request->start_date);
            $purchasesQuery->whereDate('invoice_date', '>=', $request->start_date);
            $cashInQuery->whereDate('transaction_date', '>=', $request->start_date);
            $cashOutQuery->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $salesQuery->whereDate('invoice_date', '<=', $request->end_date);
            $purchasesQuery->whereDate('invoice_date', '<=', $request->end_date);
            $cashInQuery->whereDate('transaction_date', '<=', $request->end_date);
            $cashOutQuery->whereDate('transaction_date', '<=', $request->end_date);
        }

        $totalSales = $salesQuery->sum('total_amount');
        $totalPurchases = $purchasesQuery->sum('total_amount');

        $totalPaymentsReceived = $cashInQuery->sum('paid_amount');
        $totalPaymentsMade = $cashOutQuery->sum('paid_amount');

        // Global Debts
        $customersDebt = Customer::where('balance', '>', 0)->sum('balance');
        $suppliersDebt = Supplier::where('balance', '>', 0)->sum('balance');

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
        $customers = Customer::where('balance', '>', 0)->orderBy('id', 'desc')->get();
        $suppliers = Supplier::where('balance', '>', 0)->orderBy('id', 'desc')->get();

        $totalCustomersDebt = $customers->sum('balance');
        $totalSuppliersDebt = $suppliers->sum('balance');

        return view('reports.debts', compact('customers', 'suppliers', 'totalCustomersDebt', 'totalSuppliersDebt'));
    }
}

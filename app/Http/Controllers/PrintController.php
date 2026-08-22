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
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (empty($q) || mb_strlen($q) < 1) {
            return response()->json([
                'customers' => [],
                'suppliers' => [],
                'products' => [],
                'sales' => [],
                'purchases' => [],
                'transactions' => [],
                'total_count' => 0,
            ]);
        }

        // 1. Customers
        $customers = Customer::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->name,
                    'subtitle' => $c->phone ? 'هاتف: ' . $c->phone : 'عميل مسجل',
                    'balance' => $c->balance,
                    'balance_text' => $c->balance == 0 ? 'خالص' : ($c->balance > 0 ? number_format($c->balance, 0) . ' ج.م (مطلوب منه)' : number_format(abs($c->balance), 0) . ' ج.م (له عندنا)'),
                    'balance_type' => $c->balance == 0 ? 'zero' : ($c->balance > 0 ? 'debt' : 'credit'),
                    'url' => route('customers.show', $c->id),
                    'category' => 'العملاء',
                ];
            });

        // 2. Suppliers
        $suppliers = Supplier::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'title' => $s->name,
                    'subtitle' => $s->phone ? 'هاتف: ' . $s->phone : 'مورد مسجل',
                    'balance' => $s->balance,
                    'balance_text' => $s->balance == 0 ? 'خالص' : ($s->balance > 0 ? number_format($s->balance, 0) . ' ج.م (له علينا)' : number_format(abs($s->balance), 0) . ' ج.م (لنا عنده)'),
                    'balance_type' => $s->balance == 0 ? 'zero' : ($s->balance > 0 ? 'supplier_liability' : 'supplier_debit'),
                    'url' => route('suppliers.show', $s->id),
                    'category' => 'الموردين',
                ];
            });

        // 3. Products
        $products = Product::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->name,
                    'subtitle' => 'الرصيد بالمخزن: ' . number_format($p->stock, 0) . ' ' . ($p->unit ?? 'ك'),
                    'url' => route('products.index'),
                    'category' => 'المنتجات والمخزون',
                ];
            });

        // 4. Sales Invoices
        $sales = Sale::with('customer')
            ->where(function ($query) use ($q) {
                $query->where('invoice_number', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%")
                      ->orWhereHas('customer', function ($cq) use ($q) {
                          $cq->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%");
                      });
            })
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'title' => 'فاتورة مبيعات #' . $sale->invoice_number,
                    'subtitle' => ($sale->customer ? $sale->customer->name : 'عميل نقدي') . ' - ' . number_format($sale->total_amount, 0) . ' ج.م',
                    'date' => $sale->created_at ? $sale->created_at->format('Y-m-d') : '',
                    'url' => route('print.sale', $sale->id),
                    'category' => 'فواتير المبيعات',
                ];
            });

        // 5. Purchases Invoices
        $purchases = Purchase::with('supplier')
            ->where(function ($query) use ($q) {
                $query->where('invoice_number', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%")
                      ->orWhereHas('supplier', function ($sq) use ($q) {
                          $sq->where('name', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%");
                      });
            })
            ->orderBy('id', 'desc')
            ->take(5)
            ->get()
            ->map(function ($purchase) {
                return [
                    'id' => $purchase->id,
                    'title' => 'فاتورة مشتريات #' . $purchase->invoice_number,
                    'subtitle' => ($purchase->supplier ? $purchase->supplier->name : 'مورد نقدي') . ' - ' . number_format($purchase->total_amount, 0) . ' ج.م',
                    'date' => $purchase->created_at ? $purchase->created_at->format('Y-m-d') : '',
                    'url' => route('print.purchase', $purchase->id),
                    'category' => 'فواتير المشتريات',
                ];
            });

        // 6. Transactions
        $transactions = Transaction::with('transactionable')
            ->where(function ($query) use ($q) {
                if (is_numeric($q)) {
                    $query->where('id', $q);
                }
                $query->orWhere('notes', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->take(4)
            ->get()
            ->map(function ($t) {
                $typeLabels = [
                    'payment_received' => 'إيصال تحصيل',
                    'payment_made' => 'إيصال سداد',
                    'return_sale' => 'مرتجع مبيعات',
                    'return_purchase' => 'مرتجع مشتريات',
                    'sale' => 'عملية بيع',
                    'purchase' => 'عملية شراء',
                ];
                return [
                    'id' => $t->id,
                    'title' => ($typeLabels[$t->type] ?? 'عملية') . ' #' . str_pad($t->id, 4, '0', STR_PAD_LEFT),
                    'subtitle' => ($t->transactionable->name ?? '---') . ' - ' . number_format($t->total_amount > 0 ? $t->total_amount : $t->paid_amount, 0) . ' ج.م',
                    'url' => route('transactions.print', $t->id),
                    'category' => 'الإيصالات والعمليات',
                ];
            });

        $totalCount = $customers->count() + $suppliers->count() + $products->count() + $sales->count() + $purchases->count() + $transactions->count();

        return response()->json([
            'customers' => $customers,
            'suppliers' => $suppliers,
            'products' => $products,
            'sales' => $sales,
            'purchases' => $purchases,
            'transactions' => $transactions,
            'total_count' => $totalCount,
        ]);
    }
}

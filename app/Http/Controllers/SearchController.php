<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = mb_substr(trim((string) $request->input('q', '')), 0, 100);

        if ($query === '') {
            return response()->json($this->emptyResponse());
        }

        $terms = $this->searchTerms($query);
        $digits = $this->normalizeDigits($query);
        $numericId = ctype_digit($digits) && (int) $digits > 0 ? (int) $digits : null;

        $customerModels = Customer::query()
            ->where(function (Builder $builder) use ($terms, $digits, $numericId) {
                $this->addTextMatches($builder, ['name'], $terms);
                if ($digits !== '') {
                    $builder->orWhere('phone', 'like', '%'.$digits.'%');
                }
                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
            })
            ->orderByRaw(
                'CASE WHEN id = ? THEN 0 WHEN phone = ? THEN 1 WHEN name = ? THEN 2 WHEN name LIKE ? THEN 3 ELSE 4 END',
                [$numericId ?? -1, $digits, $query, $query.'%']
            )
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $supplierModels = Supplier::query()
            ->where(function (Builder $builder) use ($terms, $digits, $numericId) {
                $this->addTextMatches($builder, ['name'], $terms);
                if ($digits !== '') {
                    $builder->orWhere('phone', 'like', '%'.$digits.'%');
                }
                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
            })
            ->orderByRaw(
                'CASE WHEN id = ? THEN 0 WHEN phone = ? THEN 1 WHEN name = ? THEN 2 WHEN name LIKE ? THEN 3 ELSE 4 END',
                [$numericId ?? -1, $digits, $query, $query.'%']
            )
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $productModels = Product::query()
            ->where(function (Builder $builder) use ($terms, $numericId) {
                $this->addTextMatches($builder, ['name', 'notes'], $terms);
                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
            })
            ->orderByRaw(
                'CASE WHEN id = ? THEN 0 WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END',
                [$numericId ?? -1, $query, $query.'%']
            )
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $sales = $this->salesResults(
            $terms,
            $numericId,
            $customerModels->pluck('id'),
            $productModels->pluck('id')
        );
        $purchases = $this->purchaseResults(
            $terms,
            $numericId,
            $supplierModels->pluck('id'),
            $productModels->pluck('id')
        );
        $transactions = $this->transactionResults(
            $terms,
            $numericId,
            $customerModels->pluck('id'),
            $supplierModels->pluck('id'),
            $productModels->pluck('id')
        );

        $customers = $customerModels->map(fn (Customer $customer) => [
            'id' => $customer->id,
            'title' => $customer->name,
            'subtitle' => 'كود العميل: #'.$customer->id.($customer->phone ? ' • هاتف: '.$customer->phone : ''),
            'balance' => (float) $customer->balance,
            'balance_text' => $this->customerBalanceText($customer),
            'balance_type' => $customer->balance == 0 ? 'zero' : ($customer->balance > 0 ? 'debt' : 'credit'),
            'url' => route('customers.show', $customer),
            'category' => 'العملاء',
        ]);

        $suppliers = $supplierModels->map(fn (Supplier $supplier) => [
            'id' => $supplier->id,
            'title' => $supplier->name,
            'subtitle' => 'كود المورد: #'.$supplier->id.($supplier->phone ? ' • هاتف: '.$supplier->phone : ''),
            'balance' => (float) $supplier->balance,
            'balance_text' => $this->supplierBalanceText($supplier),
            'balance_type' => $supplier->balance == 0 ? 'zero' : ($supplier->balance > 0 ? 'supplier_liability' : 'supplier_debit'),
            'url' => route('suppliers.show', $supplier),
            'category' => 'الموردين',
        ]);

        $products = $productModels->map(fn (Product $product) => [
            'id' => $product->id,
            'title' => $product->name,
            'subtitle' => 'كود الصنف: #'.$product->id.' • الرصيد: '.format_quantity($product->stock).' '.($product->unit ?? 'ك'),
            'url' => route('products.show', $product),
            'category' => 'المنتجات والمخزون',
        ]);

        $pages = $this->pageResults($query);
        $totalCount = collect([$pages, $customers, $suppliers, $products, $sales, $purchases, $transactions])
            ->sum(fn (Collection $items) => $items->count());

        return response()->json([
            'pages' => $pages,
            'customers' => $customers,
            'suppliers' => $suppliers,
            'products' => $products,
            'sales' => $sales,
            'purchases' => $purchases,
            'transactions' => $transactions,
            'total_count' => $totalCount,
        ]);
    }

    private function salesResults(Collection $terms, ?int $numericId, Collection $customerIds, Collection $productIds): Collection
    {
        $results = Sale::with('customer')
            ->where(function (Builder $builder) use ($terms, $numericId) {
                $this->addTextMatches($builder, ['invoice_number', 'notes'], $terms);
                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
            })
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        foreach ($customerIds as $customerId) {
            $results = $results->concat(
                Sale::with('customer')->where('customer_id', $customerId)->orderByDesc('id')->limit(2)->get()
            );
        }

        foreach ($productIds as $productId) {
            $results = $results->concat(
                Sale::with('customer')
                    ->whereHas('items', fn (Builder $items) => $items->where('product_id', $productId))
                    ->orderByDesc('id')
                    ->limit(2)
                    ->get()
            );
        }

        return $results->unique('id')->sortByDesc('id')->take(12)->values()->map(fn (Sale $sale) => [
            'id' => $sale->id,
            'title' => 'فاتورة مبيعات #'.$sale->invoice_number,
            'subtitle' => ($sale->customer?->name ?? 'عميل').' • '.format_amount($sale->total_amount).' ج.م',
            'date' => ($sale->invoice_date ?? $sale->created_at)?->format('Y-m-d') ?? '',
            'url' => route('print.sale', $sale),
            'category' => 'فواتير المبيعات',
        ]);
    }

    private function purchaseResults(Collection $terms, ?int $numericId, Collection $supplierIds, Collection $productIds): Collection
    {
        $results = Purchase::with('supplier')
            ->where(function (Builder $builder) use ($terms, $numericId) {
                $this->addTextMatches($builder, ['invoice_number', 'notes'], $terms);
                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
            })
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        foreach ($supplierIds as $supplierId) {
            $results = $results->concat(
                Purchase::with('supplier')->where('supplier_id', $supplierId)->orderByDesc('id')->limit(2)->get()
            );
        }

        foreach ($productIds as $productId) {
            $results = $results->concat(
                Purchase::with('supplier')
                    ->whereHas('items', fn (Builder $items) => $items->where('product_id', $productId))
                    ->orderByDesc('id')
                    ->limit(2)
                    ->get()
            );
        }

        return $results->unique('id')->sortByDesc('id')->take(12)->values()->map(fn (Purchase $purchase) => [
            'id' => $purchase->id,
            'title' => 'فاتورة مشتريات #'.$purchase->invoice_number,
            'subtitle' => ($purchase->supplier?->name ?? 'مورد').' • '.format_amount($purchase->total_amount).' ج.م',
            'date' => ($purchase->invoice_date ?? $purchase->created_at)?->format('Y-m-d') ?? '',
            'url' => route('print.purchase', $purchase),
            'category' => 'فواتير المشتريات',
        ]);
    }

    private function transactionResults(
        Collection $terms,
        ?int $numericId,
        Collection $customerIds,
        Collection $supplierIds,
        Collection $productIds
    ): Collection {
        return Transaction::with(['transactionable', 'product'])
            ->where(function (Builder $builder) use ($terms, $numericId, $customerIds, $supplierIds, $productIds) {
                $this->addTextMatches($builder, ['notes'], $terms);

                if ($numericId) {
                    $builder->orWhere('id', $numericId);
                }
                if ($customerIds->isNotEmpty()) {
                    $builder->orWhere(function (Builder $party) use ($customerIds) {
                        $party->where('transactionable_type', Customer::class)
                            ->whereIn('transactionable_id', $customerIds);
                    });
                }
                if ($supplierIds->isNotEmpty()) {
                    $builder->orWhere(function (Builder $party) use ($supplierIds) {
                        $party->where('transactionable_type', Supplier::class)
                            ->whereIn('transactionable_id', $supplierIds);
                    });
                }
                if ($productIds->isNotEmpty()) {
                    $builder->orWhereIn('product_id', $productIds);
                }
            })
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(fn (Transaction $transaction) => [
                'id' => $transaction->id,
                'title' => $transaction->type_name.' #'.str_pad((string) $transaction->id, 4, '0', STR_PAD_LEFT),
                'subtitle' => ($transaction->transactionable?->name ?? '---').' • '.
                    format_amount($transaction->total_amount > 0 ? $transaction->total_amount : $transaction->paid_amount).' ج.م',
                'date' => $transaction->transaction_date?->format('Y-m-d') ?? '',
                'url' => route('transactions.print', $transaction),
                'category' => 'الإيصالات والعمليات',
            ]);
    }

    private function addTextMatches(Builder $builder, array $columns, Collection $terms): void
    {
        foreach ($terms as $term) {
            foreach ($columns as $column) {
                $builder->orWhere($column, 'like', '%'.$term.'%');
            }
        }
    }

    private function searchTerms(string $query): Collection
    {
        $lower = mb_strtolower($query);
        $normalized = strtr($lower, [
            'أ' => 'ا',
            'إ' => 'ا',
            'آ' => 'ا',
            'ى' => 'ي',
        ]);

        return collect([
            $query,
            $lower,
            $normalized,
            str_replace('ا', 'أ', $normalized),
            str_replace('ا', 'إ', $normalized),
            str_replace('ي', 'ى', $normalized),
        ])->filter(fn ($term) => $term !== '')->unique()->values();
    }

    private function normalizeDigits(string $value): string
    {
        $western = strtr($value, [
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);

        return preg_replace('/\D+/u', '', $western) ?? '';
    }

    private function customerBalanceText(Customer $customer): string
    {
        if ((float) $customer->balance === 0.0) {
            return 'خالص';
        }

        return $customer->balance > 0
            ? format_amount($customer->balance).' ج.م (مطلوب منه)'
            : format_amount(abs($customer->balance)).' ج.م (له عندنا)';
    }

    private function supplierBalanceText(Supplier $supplier): string
    {
        if ((float) $supplier->balance === 0.0) {
            return 'خالص';
        }

        return $supplier->balance > 0
            ? format_amount($supplier->balance).' ج.م (له علينا)'
            : format_amount(abs($supplier->balance)).' ج.م (لنا عنده)';
    }

    private function pageResults(string $query): Collection
    {
        $pages = [
            ['title' => 'لوحة التحكم', 'url' => route('dashboard'), 'keywords' => 'الرئيسية داشبورد dashboard'],
            ['title' => 'إدارة العملاء', 'url' => route('customers.index'), 'keywords' => 'عملاء زبائن customers'],
            ['title' => 'إدارة الموردين', 'url' => route('suppliers.index'), 'keywords' => 'موردين تجار suppliers'],
            ['title' => 'إدارة المنتجات', 'url' => route('products.index'), 'keywords' => 'منتجات أصناف مخزن products'],
            ['title' => 'فواتير المبيعات', 'url' => route('sales.index'), 'keywords' => 'مبيعات بيع sales'],
            ['title' => 'فواتير المشتريات', 'url' => route('purchases.index'), 'keywords' => 'مشتريات شراء purchases'],
            ['title' => 'الديون والأرصدة', 'url' => route('debts.index'), 'keywords' => 'ديون أرصدة debts'],
            ['title' => 'التقارير الشاملة', 'url' => route('reports.index'), 'keywords' => 'تقارير إحصائيات reports'],
        ];
        $needle = strtr(mb_strtolower($query), ['أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا', 'ى' => 'ي']);

        return collect($pages)
            ->filter(function (array $page) use ($needle) {
                $haystack = strtr(mb_strtolower($page['title'].' '.$page['keywords']), ['أ' => 'ا', 'إ' => 'ا', 'آ' => 'ا', 'ى' => 'ي']);

                return mb_strpos($haystack, $needle) !== false;
            })
            ->map(fn (array $page) => [
                'id' => 'page_'.md5($page['url']),
                'title' => $page['title'],
                'subtitle' => 'صفحة في النظام',
                'url' => $page['url'],
                'category' => 'صفحات النظام',
            ])
            ->values();
    }

    private function emptyResponse(): array
    {
        return [
            'pages' => [],
            'customers' => [],
            'suppliers' => [],
            'products' => [],
            'sales' => [],
            'purchases' => [],
            'transactions' => [],
            'total_count' => 0,
        ];
    }
}

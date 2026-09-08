<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'stock',
        'opening_stock',
        'unit',
        'notes',
    ];

    public function transactions()
    {
        return $this->hasMany(ProductTransaction::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Add the latest historical selling price to product rows without an N+1 query.
     */
    public function scopeWithLastSalePrice(Builder $query): Builder
    {
        return $query->addSelect([
            'last_sale_price' => SaleItem::query()
                ->select('sale_items.unit_price')
                ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->whereColumn('sale_items.product_id', 'products.id')
                ->whereNull('sales.deleted_at')
                ->orderByDesc('sales.invoice_date')
                ->orderByDesc('sales.id')
                ->limit(1),
        ]);
    }

    /**
     * Sales KPIs for this product. The average is weighted by sold quantity.
     */
    public function salesSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $summary = $this->saleItems()
            ->whereHas('sale', function (Builder $query) use ($startDate, $endDate) {
                $query
                    ->when($startDate, fn (Builder $query) => $query->whereDate('invoice_date', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('invoice_date', '<=', $endDate));
            })
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity')
            ->selectRaw('COALESCE(SUM(total), 0) as total_sales')
            ->selectRaw('MIN(unit_price) as lowest_price')
            ->selectRaw('MAX(unit_price) as highest_price')
            ->first();

        $totalQuantity = (float) $summary->total_quantity;
        $totalSales = (float) $summary->total_sales;

        return [
            'average_price' => $totalQuantity > 0 ? $totalSales / $totalQuantity : null,
            'lowest_price' => $summary->lowest_price !== null ? (float) $summary->lowest_price : null,
            'highest_price' => $summary->highest_price !== null ? (float) $summary->highest_price : null,
            'total_sales' => $totalSales,
            'total_quantity' => $totalQuantity,
        ];
    }
}

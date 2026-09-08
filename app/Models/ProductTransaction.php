<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'transaction_date',
        'quantity',
        'balance_after',
        'related_id',
        'related_type',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function getTypeNameAttribute(): string
    {
        return transaction_type_label($this->type, 'product');
    }

    public function getUnitPriceAttribute(): ?float
    {
        $related = $this->related;

        if ($related instanceof Sale || $related instanceof Purchase) {
            $item = $related->items->firstWhere('product_id', $this->product_id);

            return $item ? (float) $item->unit_price : null;
        }

        if ($related instanceof Transaction) {
            if ($related->unit_price !== null) {
                return (float) $related->unit_price;
            }

            return (float) $related->quantity > 0
                ? (float) $related->total_amount / (float) $related->quantity
                : null;
        }

        return null;
    }

    public function getOperationTotalAttribute(): ?float
    {
        return $this->unit_price === null
            ? null
            : abs((float) $this->quantity) * $this->unit_price;
    }

    public function getReferenceNumberAttribute(): string
    {
        return match (true) {
            $this->related instanceof Sale, $this->related instanceof Purchase => $this->related->invoice_number,
            $this->related instanceof Transaction => 'عملية #'.$this->related->id,
            default => '-',
        };
    }

    public function getPartyNameAttribute(): string
    {
        return match (true) {
            $this->related instanceof Sale => $this->related->customer?->name ?? '-',
            $this->related instanceof Purchase => $this->related->supplier?->name ?? '-',
            $this->related instanceof Transaction => $this->related->transactionable?->name ?? '-',
            default => '-',
        };
    }

    public function getIsIncomingAttribute(): bool
    {
        return in_array($this->type, ['purchase', 'return_sale', 'adjustment_add', 'opening_balance', 'رصيد افتتاحي'], true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transactionable_type',
        'transactionable_id',
        'type',
        'product_id',
        'quantity',
        'unit_price',
        'total_amount',
        'paid_amount',
        'balance_after',
        'transaction_date',
        'source_type',
        'source_id',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function getInvoiceIdAttribute()
    {
        if (in_array($this->type, ['purchase', 'sale', 'return_purchase', 'return_sale'])) {
            if (preg_match('/رقم (\S+)/', $this->notes, $matches)) {
                $invoiceNumber = trim($matches[1]);
                if (str_starts_with($this->notes, 'فاتورة مشتريات')) {
                    $purchase = Purchase::where('invoice_number', $invoiceNumber)->first();

                    return $purchase ? $purchase->id : null;
                } elseif (str_starts_with($this->notes, 'فاتورة مبيعات')) {
                    $sale = Sale::where('invoice_number', $invoiceNumber)->first();

                    return $sale ? $sale->id : null;
                }
            }
        }

        return null;
    }

    /**
     * Line items of the invoice behind this ledger row (purchase/sale), empty otherwise.
     */
    public function getInvoiceItemsAttribute()
    {
        $source = in_array($this->type, ['purchase', 'sale'], true) && $this->relationLoaded('source')
            ? $this->source
            : null;

        return ($source instanceof Purchase || $source instanceof Sale) && $source->relationLoaded('items')
            ? $source->items
            : collect();
    }

    /**
     * Statement description: the product name for returns, plus any user notes.
     */
    public function getStatementDescriptionAttribute(): string
    {
        $productName = in_array($this->type, ['return_purchase', 'return_sale'], true)
            ? $this->product?->name
            : null;

        if (! $productName) {
            return $this->notes ?? '-';
        }

        if (! $this->notes || str_contains($this->notes, $productName)) {
            return $this->notes ?: $productName;
        }

        return $productName.' - '.$this->notes;
    }

    public function getTypeNameAttribute(): string
    {
        return transaction_type_label($this->type, 'financial');
    }
}

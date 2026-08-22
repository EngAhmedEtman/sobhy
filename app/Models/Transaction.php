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
}

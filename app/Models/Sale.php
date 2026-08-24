<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function ledgerTransaction()
    {
        return $this->morphOne(Transaction::class, 'source');
    }

    public function productTransactions()
    {
        return $this->morphMany(ProductTransaction::class, 'related');
    }

    public function getPartyNameAttribute()
    {
        return 'مبيعات لـ: '.($this->customer->name ?? 'عميل');
    }

    public function getTransactionAttribute()
    {
        if ($this->relationLoaded('ledgerTransaction')) {
            return $this->getRelation('ledgerTransaction');
        }

        return $this->ledgerTransaction()->first() ?? Transaction::where('transactionable_type', Customer::class)
            ->where('transactionable_id', $this->customer_id)
            ->where('notes', 'like', '%فاتورة مبيعات رقم '.$this->invoice_number.'%')
            ->first();
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) ($this->transaction?->paid_amount ?? 0);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, round((float) $this->total_amount - $this->paid_amount, 2));
    }
}

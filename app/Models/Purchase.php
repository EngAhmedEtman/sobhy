<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function ledgerTransaction()
    {
        return $this->morphOne(Transaction::class, 'source');
    }

    public function getTransactionAttribute()
    {
        if ($this->relationLoaded('ledgerTransaction')) {
            return $this->getRelation('ledgerTransaction');
        }

        return $this->ledgerTransaction()->first() ?? Transaction::where('transactionable_type', Supplier::class)
            ->where('transactionable_id', $this->supplier_id)
            ->where('notes', 'like', '%فاتورة مشتريات رقم '.$this->invoice_number.'%')
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

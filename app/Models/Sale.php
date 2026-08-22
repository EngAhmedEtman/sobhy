<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'total_amount',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function productTransactions()
    {
        return $this->morphMany(ProductTransaction::class, 'related');
    }

    public function getPartyNameAttribute()
    {
        return 'مبيعات لـ: ' . ($this->customer->name ?? 'عميل');
    }

    public function getTransactionAttribute()
    {
        return Transaction::where('transactionable_type', Customer::class)
            ->where('transactionable_id', $this->customer_id)
            ->where('notes', 'like', '%فاتورة مبيعات رقم ' . $this->invoice_number . '%')
            ->first();
    }
}

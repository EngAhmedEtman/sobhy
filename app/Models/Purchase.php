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
        'total_amount',
        'notes',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getTransactionAttribute()
    {
        return Transaction::where('transactionable_type', Supplier::class)
            ->where('transactionable_id', $this->supplier_id)
            ->where('notes', 'like', '%فاتورة مشتريات رقم ' . $this->invoice_number . '%')
            ->first();
    }
}

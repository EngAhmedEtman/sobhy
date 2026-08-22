<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            
            $table->string('type'); 
            
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            
            $table->decimal('quantity', 15, 2)->nullable();
            $table->decimal('unit_price', 15, 2)->nullable();
            
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2)->default(0);
            
            $table->date('transaction_date');
            $table->string('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

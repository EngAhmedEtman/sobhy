<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stock', 15, 2)->default(0)->after('name');
        });

        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('type'); // opening_balance, purchase, sale, manual_adjustment
            $table->decimal('quantity', 15, 2); // Positive for in, negative for out
            $table->decimal('balance_after', 15, 2);
            $table->nullableMorphs('related'); // To link to a Sale or Purchase Invoice later
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_transactions');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};

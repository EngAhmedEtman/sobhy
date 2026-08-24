<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function recalculateProduct(Product $product): void
    {
        $movements = $product->transactions()
            ->orderByRaw('CASE WHEN transaction_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $running = $this->normalize($product->opening_stock ?? 0);

        foreach ($movements as $movement) {
            $running = $this->normalize($running + $this->movementDelta($movement));

            if ($running < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'الحركة ستجعل رصيد المخزون سالبًا في تاريخها. راجع الكمية أو تاريخ الحركة.',
                ]);
            }

            if ($this->normalize($movement->balance_after) !== $running) {
                DB::table('product_transactions')
                    ->where('id', $movement->id)
                    ->update(['balance_after' => $running]);
            }
        }

        if ($running < 0) {
            throw ValidationException::withMessages([
                'quantity' => 'الكمية المطلوبة أكبر من الرصيد المتاح في المخزون.',
            ]);
        }

        if ($this->normalize($product->stock) !== $running) {
            $product->forceFill(['stock' => $running])->saveQuietly();
        }
    }

    public function movementDelta(ProductTransaction $movement): float
    {
        $quantity = $this->normalize($movement->quantity);

        return match ($movement->type) {
            'purchase', 'return_sale', 'adjustment_add' => $quantity,
            'sale', 'return_purchase', 'adjustment_sub' => -$quantity,
            default => 0.0,
        };
    }

    public function assertAvailable(Product $product, mixed $quantity, string $field = 'quantity'): void
    {
        if ($this->normalize($quantity) > $this->normalize($product->stock)) {
            throw ValidationException::withMessages([
                $field => 'الكمية المطلوبة أكبر من الرصيد المتاح في المخزون.',
            ]);
        }
    }

    private function normalize(mixed $quantity): float
    {
        return round((float) $quantity, 2, PHP_ROUND_HALF_UP);
    }
}

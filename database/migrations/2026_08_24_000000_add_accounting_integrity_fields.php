<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('opening_balance', 15, 2)->default(0)->after('balance');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->decimal('opening_balance', 15, 2)->default(0)->after('balance');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('opening_stock', 15, 2)->default(0)->after('stock');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->date('invoice_date')->nullable()->after('invoice_number')->index();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->date('invoice_date')->nullable()->after('invoice_number')->index();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->nullableMorphs('source');
        });

        Schema::table('product_transactions', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('type')->index();
        });

        $this->backfillInvoiceLinksAndDates();
        $this->backfillReturnStockMovements();
        $this->backfillPartyLedgers('customers', 'App\\Models\\Customer');
        $this->backfillPartyLedgers('suppliers', 'App\\Models\\Supplier');
        $this->backfillProductLedgers();
    }

    public function down(): void
    {
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->dropIndex(['transaction_date']);
            $table->dropColumn('transaction_date');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropMorphs('source');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['invoice_date']);
            $table->dropColumn('invoice_date');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['invoice_date']);
            $table->dropColumn('invoice_date');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('opening_stock');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('opening_balance');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('opening_balance');
        });
    }

    private function backfillInvoiceLinksAndDates(): void
    {
        foreach ([
            ['table' => 'purchases', 'type' => 'purchase', 'party_type' => 'App\\Models\\Supplier', 'source_type' => 'App\\Models\\Purchase', 'party_key' => 'supplier_id'],
            ['table' => 'sales', 'type' => 'sale', 'party_type' => 'App\\Models\\Customer', 'source_type' => 'App\\Models\\Sale', 'party_key' => 'customer_id'],
        ] as $definition) {
            DB::table($definition['table'])->orderBy('id')->eachById(function ($invoice) use ($definition) {
                $transaction = DB::table('transactions')
                    ->whereNull('deleted_at')
                    ->where('transactionable_type', $definition['party_type'])
                    ->where('transactionable_id', $invoice->{$definition['party_key']})
                    ->where('type', $definition['type'])
                    ->where('notes', 'like', '%'.$invoice->invoice_number.'%')
                    ->orderBy('id')
                    ->first();

                $invoiceDate = $transaction?->transaction_date ?: substr((string) $invoice->created_at, 0, 10);

                DB::table($definition['table'])
                    ->where('id', $invoice->id)
                    ->update(['invoice_date' => $invoiceDate]);

                if ($transaction) {
                    DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->update([
                            'source_type' => $definition['source_type'],
                            'source_id' => $invoice->id,
                        ]);
                }
            });
        }

        DB::table('product_transactions')->orderBy('id')->eachById(function ($movement) {
            $date = null;

            if ($movement->related_type === 'App\\Models\\Purchase' && $movement->related_id) {
                $date = DB::table('purchases')->where('id', $movement->related_id)->value('invoice_date');
            } elseif ($movement->related_type === 'App\\Models\\Sale' && $movement->related_id) {
                $date = DB::table('sales')->where('id', $movement->related_id)->value('invoice_date');
            } elseif ($movement->related_type === 'App\\Models\\Transaction' && $movement->related_id) {
                $date = DB::table('transactions')->where('id', $movement->related_id)->value('transaction_date');
            }

            DB::table('product_transactions')->where('id', $movement->id)->update([
                'transaction_date' => $date ?: substr((string) $movement->created_at, 0, 10),
            ]);
        });
    }

    private function backfillReturnStockMovements(): void
    {
        DB::table('transactions')
            ->whereNull('deleted_at')
            ->whereIn('type', ['return_sale', 'return_purchase'])
            ->whereNotNull('product_id')
            ->where('quantity', '>', 0)
            ->orderBy('id')
            ->eachById(function ($transaction) {
                $exists = DB::table('product_transactions')
                    ->where('related_type', 'App\\Models\\Transaction')
                    ->where('related_id', $transaction->id)
                    ->exists();

                if (! $exists) {
                    DB::table('product_transactions')->insert([
                        'product_id' => $transaction->product_id,
                        'type' => $transaction->type,
                        'transaction_date' => $transaction->transaction_date,
                        'quantity' => $transaction->quantity,
                        'balance_after' => 0,
                        'related_type' => 'App\\Models\\Transaction',
                        'related_id' => $transaction->id,
                        'notes' => $transaction->notes,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->updated_at,
                    ]);
                }
            });
    }

    private function backfillPartyLedgers(string $table, string $partyType): void
    {
        DB::table($table)->orderBy('id')->eachById(function ($party) use ($table, $partyType) {
            $transactions = DB::table('transactions')
                ->whereNull('deleted_at')
                ->where('transactionable_type', $partyType)
                ->where('transactionable_id', $party->id)
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();

            $netMovement = $transactions->sum(fn ($transaction) => $this->transactionDelta($transaction));
            $openingBalance = round((float) $party->balance - $netMovement, 2);
            $runningBalance = $openingBalance;

            DB::table($table)->where('id', $party->id)->update(['opening_balance' => $openingBalance]);

            foreach ($transactions as $transaction) {
                $runningBalance = round($runningBalance + $this->transactionDelta($transaction), 2);
                DB::table('transactions')->where('id', $transaction->id)->update(['balance_after' => $runningBalance]);
            }
        });
    }

    private function backfillProductLedgers(): void
    {
        DB::table('products')->orderBy('id')->eachById(function ($product) {
            $movements = DB::table('product_transactions')
                ->where('product_id', $product->id)
                ->orderByRaw('CASE WHEN transaction_date IS NULL THEN 1 ELSE 0 END')
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();

            $netMovement = $movements->sum(fn ($movement) => $this->stockDelta($movement));
            $openingStock = round((float) $product->stock - $netMovement, 2);
            $runningStock = $openingStock;

            DB::table('products')->where('id', $product->id)->update(['opening_stock' => $openingStock]);

            foreach ($movements as $movement) {
                $runningStock = round($runningStock + $this->stockDelta($movement), 2);
                DB::table('product_transactions')->where('id', $movement->id)->update(['balance_after' => $runningStock]);
            }
        });
    }

    private function transactionDelta(object $transaction): float
    {
        return match ($transaction->type) {
            'sale', 'purchase' => round((float) $transaction->total_amount - (float) $transaction->paid_amount, 2),
            'payment_received', 'payment_made', 'payment_sent' => round(-(float) $transaction->paid_amount, 2),
            'return_sale', 'return_purchase' => round(-(float) $transaction->total_amount + (float) $transaction->paid_amount, 2),
            'initial_balance' => round((float) $transaction->total_amount, 2),
            default => 0.0,
        };
    }

    private function stockDelta(object $movement): float
    {
        return match ($movement->type) {
            'purchase', 'return_sale', 'adjustment_add' => round((float) $movement->quantity, 2),
            'sale', 'return_purchase', 'adjustment_sub' => round(-(float) $movement->quantity, 2),
            default => 0.0,
        };
    }
};

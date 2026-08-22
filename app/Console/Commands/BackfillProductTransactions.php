<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\SaleItem;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class BackfillProductTransactions extends Command
{
    protected $signature = 'products:backfill-transactions';
    protected $description = 'Backfills missing product transactions for past sales and purchases';

    public function handle()
    {
        $this->info('Starting backfill of product transactions...');

        DB::transaction(function () {
            // First, delete all ProductTransactions that are related to sales or purchases
            // to ensure idempotency (can be run multiple times safely).
            ProductTransaction::whereIn('type', ['sale', 'purchase'])->delete();
            
            $products = Product::all();

            foreach ($products as $product) {
                $this->info('Processing Product: ' . $product->name);
                
                // Get opening balance transaction if it exists
                $openingTx = ProductTransaction::where('product_id', $product->id)
                    ->where('type', 'رصيد افتتاحي')
                    ->first();
                    
                $currentBalance = $openingTx ? $openingTx->quantity : 0;
                
                // Fetch all sale items and purchase items for this product
                $saleItems = SaleItem::with('sale')->where('product_id', $product->id)->get()->map(function ($item) {
                    return [
                        'type' => 'sale',
                        'quantity' => $item->quantity,
                        'date' => $item->sale->created_at,
                        'model' => $item->sale,
                    ];
                });
                
                $purchaseItems = PurchaseItem::with('purchase')->where('product_id', $product->id)->get()->map(function ($item) {
                    return [
                        'type' => 'purchase',
                        'quantity' => $item->quantity,
                        'date' => $item->purchase->created_at,
                        'model' => $item->purchase,
                    ];
                });
                
                // Merge and sort chronologically
                $allTransactions = $saleItems->concat($purchaseItems)->sortBy('date');
                
                foreach ($allTransactions as $tx) {
                    if ($tx['type'] === 'purchase') {
                        $currentBalance += $tx['quantity'];
                        ProductTransaction::create([
                            'product_id' => $product->id,
                            'type' => 'purchase',
                            'quantity' => $tx['quantity'],
                            'balance_after' => $currentBalance,
                            'related_id' => $tx['model']->id,
                            'related_type' => Purchase::class,
                            'notes' => 'فاتورة مشتريات رقم ' . $tx['model']->invoice_number,
                            'created_at' => $tx['date'],
                            'updated_at' => $tx['date'],
                        ]);
                    } elseif ($tx['type'] === 'sale') {
                        $currentBalance -= $tx['quantity'];
                        ProductTransaction::create([
                            'product_id' => $product->id,
                            'type' => 'sale',
                            'quantity' => $tx['quantity'],
                            'balance_after' => $currentBalance,
                            'related_id' => $tx['model']->id,
                            'related_type' => Sale::class,
                            'notes' => 'فاتورة مبيعات رقم ' . $tx['model']->invoice_number,
                            'created_at' => $tx['date'],
                            'updated_at' => $tx['date'],
                        ]);
                    }
                }
                
                // IMPORTANT: Since the controllers were previously updating the Product's stock properly,
                // the product's actual stock should ALREADY equal the final running balance.
                // However, just to guarantee data integrity, we will force sync it.
                $product->stock = $currentBalance;
                $product->save();
            }
        });

        $this->info('Backfill completed successfully!');
    }
}

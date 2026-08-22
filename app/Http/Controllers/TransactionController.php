<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Show the specified transaction details.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['product']);
        $transactionable = $transaction->transactionable;
        
        return response()->json([
            'id' => $transaction->id,
            'type' => $transaction->type,
            'transaction_date' => $transaction->transaction_date->format('Y-m-d'),
            'amount' => in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent']) ? $transaction->paid_amount : $transaction->total_amount,
            'quantity' => $transaction->quantity,
            'unit_price' => $transaction->unit_price,
            'notes' => $transaction->notes,
            'balance_after' => $transaction->balance_after,
            'product_name' => $transaction->product ? $transaction->product->name : null,
            'party_name' => $transactionable->name ?? '-',
        ]);
    }

    /**
     * Print the specified transaction.
     */
    public function print(Transaction $transaction)
    {
        $transaction->load(['product']);
        $transactionable = $transaction->transactionable;
        return view('print.transaction', compact('transaction', 'transactionable'));
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        // For simplicity and safety, we only allow updating specific fields for payments and returns.
        // It's dangerous to change the product of a return, so we assume product_id is fixed, only quantity/amount/date/notes change.
        
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'quantity' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            // Revert the stock effect of the OLD transaction
            $this->revertStockEffect($transaction);

            // Update the transaction details
            if (in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent'])) {
                $transaction->paid_amount = $request->amount;
            } else {
                $transaction->total_amount = $request->amount;
            }
            
            if ($request->has('quantity') && in_array($transaction->type, ['return_sale', 'return_purchase', 'sale', 'purchase'])) {
                $transaction->quantity = $request->quantity;
                if ($request->quantity > 0) {
                    $transaction->unit_price = $request->amount / $request->quantity;
                }
            }

            $transaction->transaction_date = $request->date;
            if ($request->has('notes')) {
                $transaction->notes = $request->notes;
            }

            $transaction->save();

            // Apply the new stock effect
            $this->applyStockEffect($transaction);

            // Recalculate ledger for the customer/supplier
            $this->recalculateLedger($transaction->transactionable);
        });

        return back()->with('success', 'تم تعديل العملية بنجاح');
    }

    /**
     * Remove the specified transaction from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // Revert stock effect
            $this->revertStockEffect($transaction);
            
            $transactionable = $transaction->transactionable;
            
            // Delete the transaction
            $transaction->delete();

            // Recalculate ledger
            if ($transactionable) {
                $this->recalculateLedger($transactionable);
            }
        });

        return back()->with('success', 'تم حذف العملية بنجاح');
    }

    /**
     * Revert the effect this transaction had on product stock.
     */
    private function revertStockEffect(Transaction $transaction)
    {
        if (!$transaction->product_id || !$transaction->quantity) {
            return;
        }

        $product = $transaction->product;
        if (!$product) return;

        if ($transaction->type === 'return_sale') {
            // return_sale originally INCREASED stock. To revert, DECREASE.
            $product->decrement('stock', $transaction->quantity);
        } elseif ($transaction->type === 'return_purchase') {
            // return_purchase originally DECREASED stock. To revert, INCREASE.
            $product->increment('stock', $transaction->quantity);
        } elseif ($transaction->type === 'sale') {
            // sale originally DECREASED stock. To revert, INCREASE.
            $product->increment('stock', $transaction->quantity);
        } elseif ($transaction->type === 'purchase') {
            // purchase originally INCREASED stock. To revert, DECREASE.
            $product->decrement('stock', $transaction->quantity);
        }
    }

    /**
     * Apply the effect this transaction has on product stock.
     */
    private function applyStockEffect(Transaction $transaction)
    {
        if (!$transaction->product_id || !$transaction->quantity) {
            return;
        }

        $product = $transaction->product;
        if (!$product) return;

        if ($transaction->type === 'return_sale') {
            // return_sale INCREASES stock.
            $product->increment('stock', $transaction->quantity);
        } elseif ($transaction->type === 'return_purchase') {
            // return_purchase DECREASES stock.
            $product->decrement('stock', $transaction->quantity);
        } elseif ($transaction->type === 'sale') {
            // sale DECREASES stock.
            $product->decrement('stock', $transaction->quantity);
        } elseif ($transaction->type === 'purchase') {
            // purchase INCREASES stock.
            $product->increment('stock', $transaction->quantity);
        }
    }

    /**
     * Recalculate the entire ledger (balance) for a Customer or Supplier.
     */
    private function recalculateLedger($transactionable)
    {
        $transactions = $transactionable->transactions()
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $runningBalance = 0;

        foreach ($transactions as $t) {
            // Increases to balance (Customer debt increases, Supplier credit increases)
            if (in_array($t->type, ['sale', 'purchase'])) {
                $runningBalance += $t->total_amount;
            } 
            // Decreases to balance
            elseif (in_array($t->type, ['payment_received', 'payment_made', 'payment_sent'])) {
                $runningBalance -= $t->paid_amount;
            } 
            elseif (in_array($t->type, ['return_sale', 'return_purchase'])) {
                $runningBalance -= $t->total_amount;
            }

            // Update balance_after if it has changed
            if ($t->balance_after != $runningBalance) {
                // Use DB query to avoid triggering model events
                DB::table('transactions')
                    ->where('id', $t->id)
                    ->update(['balance_after' => $runningBalance]);
            }
        }

        // Update the main balance on the customer/supplier model
        $transactionable->update(['balance' => $runningBalance]);
    }
}

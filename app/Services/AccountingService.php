<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    public function recalculateParty(Model $party): void
    {
        $transactions = $party->transactions()
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $runningCents = $this->toCents($party->opening_balance ?? 0);

        foreach ($transactions as $transaction) {
            $runningCents += $this->transactionDeltaCents($transaction);
            $balance = $this->fromCents($runningCents);

            if ($this->toCents($transaction->balance_after) !== $runningCents) {
                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update(['balance_after' => $balance]);
            }
        }

        $balance = $this->fromCents($runningCents);
        if ($this->toCents($party->balance) !== $runningCents) {
            $party->forceFill(['balance' => $balance])->saveQuietly();
        }
    }

    public function transactionDeltaCents(Transaction $transaction): int
    {
        $total = $this->toCents($transaction->total_amount);
        $paid = $this->toCents($transaction->paid_amount);

        // An invoice-level payment is already added to the invoice's own ledger row
        // paid_amount, so counting it here as well would deduct it twice.
        if ($this->isInvoiceLinkedPayment($transaction)) {
            return 0;
        }

        return match ($transaction->type) {
            'sale', 'purchase' => $total - $paid,
            'payment_received', 'payment_made', 'payment_sent' => -$paid,
            'return_sale', 'return_purchase' => -$total + $paid,
            'initial_balance' => $total,
            'cash_withdrawal' => $paid,
            default => 0,
        };
    }

    public function isInvoiceLinkedPayment(Transaction $transaction): bool
    {
        return in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent'], true)
            && in_array($transaction->source_type, [Purchase::class, Sale::class], true)
            && $transaction->source_id;
    }

    public function toCents(mixed $amount): int
    {
        return (int) round((float) $amount * 100, 0, PHP_ROUND_HALF_UP);
    }

    public function fromCents(int $amount): float
    {
        return $amount / 100;
    }
}

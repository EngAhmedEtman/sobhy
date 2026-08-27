<?php

namespace App\Services;

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

        return match ($transaction->type) {
            'sale', 'purchase' => $total - $paid,
            'payment_received', 'payment_made', 'payment_sent' => -$paid,
            'return_sale', 'return_purchase' => -$total + $paid,
            'initial_balance' => $total,
            'cash_withdrawal' => $paid,
            default => 0,
        };
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

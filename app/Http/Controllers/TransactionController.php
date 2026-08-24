<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProductTransaction;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Services\AccountingService;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    private const EDITABLE_TYPES = [
        'payment_received',
        'payment_made',
        'payment_sent',
        'return_sale',
        'return_purchase',
    ];

    public function show(Transaction $transaction)
    {
        $this->authorizeRead($transaction);
        $transaction->load(['product', 'transactionable']);

        return response()->json([
            'id' => $transaction->id,
            'type' => $transaction->type,
            'type_label' => $transaction->type_name,
            'transaction_date' => $transaction->transaction_date?->format('Y-m-d') ?? '',
            'amount' => in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent'], true)
                ? $transaction->paid_amount
                : $transaction->total_amount,
            'quantity' => $transaction->quantity,
            'unit_price' => $transaction->unit_price,
            'notes' => $transaction->notes,
            'balance_after' => $transaction->balance_after,
            'product_name' => $transaction->product?->name,
            'party_name' => $transaction->transactionable?->name ?? '-',
        ]);
    }

    public function print(Transaction $transaction)
    {
        $this->authorizeRead($transaction);
        $transaction->load(['product', 'transactionable']);
        $transactionable = $transaction->transactionable;

        return view('print.transaction', compact('transaction', 'transactionable'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->ensureEditable($transaction);
        $this->authorizeMutation($transaction);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255',
            'quantity' => in_array($transaction->type, ['return_sale', 'return_purchase'], true)
                ? 'required|numeric|min:0.01'
                : 'nullable|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            $transaction = Transaction::query()->lockForUpdate()->findOrFail($transaction->id);
            $party = $transaction->transactionable()->lockForUpdate()->firstOrFail();
            $isPayment = in_array($transaction->type, ['payment_received', 'payment_made', 'payment_sent'], true);

            $transaction->forceFill([
                'paid_amount' => $isPayment ? $validated['amount'] : $transaction->paid_amount,
                'total_amount' => $isPayment ? $transaction->total_amount : $validated['amount'],
                'quantity' => $isPayment ? $transaction->quantity : $validated['quantity'],
                'unit_price' => $isPayment ? $transaction->unit_price : $validated['amount'] / $validated['quantity'],
                'transaction_date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
            ])->save();

            app(AccountingService::class)->recalculateParty($party);

            if (! $isPayment && $transaction->product_id) {
                $product = $transaction->product()->lockForUpdate()->firstOrFail();
                ProductTransaction::query()->updateOrCreate(
                    [
                        'related_type' => Transaction::class,
                        'related_id' => $transaction->id,
                    ],
                    [
                        'product_id' => $product->id,
                        'type' => $transaction->type,
                        'transaction_date' => $validated['date'],
                        'quantity' => $validated['quantity'],
                        'balance_after' => $product->stock,
                        'notes' => $transaction->notes,
                    ]
                );
                app(InventoryService::class)->recalculateProduct($product);
            }
        });

        return back()->with('success', 'تم تعديل العملية وإعادة احتساب الحساب والمخزون بنجاح');
    }

    public function destroy(Transaction $transaction)
    {
        $this->ensureEditable($transaction);
        $this->authorizeMutation($transaction);

        DB::transaction(function () use ($transaction) {
            $transaction = Transaction::query()->lockForUpdate()->findOrFail($transaction->id);
            $party = $transaction->transactionable()->lockForUpdate()->firstOrFail();
            $product = $transaction->product_id
                ? $transaction->product()->lockForUpdate()->first()
                : null;

            ProductTransaction::query()
                ->where('related_type', Transaction::class)
                ->where('related_id', $transaction->id)
                ->delete();
            $transaction->delete();

            app(AccountingService::class)->recalculateParty($party);
            if ($product) {
                app(InventoryService::class)->recalculateProduct($product);
            }
        });

        return back()->with('success', 'تم حذف العملية وإعادة احتساب الحساب والمخزون بنجاح');
    }

    private function ensureEditable(Transaction $transaction): void
    {
        if (! in_array($transaction->type, self::EDITABLE_TYPES, true)) {
            throw ValidationException::withMessages([
                'transaction' => 'قيود الفواتير لا تُعدّل أو تُحذف منفردة؛ عدّل الفاتورة نفسها للحفاظ على سلامة الحسابات والمخزون.',
            ]);
        }
    }

    private function authorizeMutation(Transaction $transaction): void
    {
        $permission = match ($transaction->transactionable_type) {
            Customer::class => 'customers.update',
            Supplier::class => 'suppliers.update',
            default => null,
        };

        abort_unless($permission && auth()->user()?->hasPermission($permission), 403);
    }

    private function authorizeRead(Transaction $transaction): void
    {
        $permission = match ($transaction->transactionable_type) {
            Customer::class => 'customers.view',
            Supplier::class => 'suppliers.view',
            default => null,
        };

        abort_unless($permission && auth()->user()?->hasPermission($permission), 403);
    }
}

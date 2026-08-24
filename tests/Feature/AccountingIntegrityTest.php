<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountingIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['email' => 'admin@gmail.com']);
        $this->actingAs($this->admin);
    }

    public function test_editing_an_old_settled_purchase_rebuilds_every_later_balance(): void
    {
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 0, 'opening_stock' => 0]);
        $firstDate = Carbon::today()->subDays(2)->toDateString();
        $secondDate = Carbon::today()->subDay()->toDateString();

        $this->postPurchase($supplier, $product, $firstDate, 10, 100, 1000);
        $this->postPurchase($supplier, $product, $secondDate, 10, 100, 500);

        $purchases = Purchase::orderBy('invoice_date')->get();
        $this->assertSame(500.0, (float) $supplier->fresh()->balance);

        $this->put(route('purchases.update', $purchases->first()), [
            'supplier_id' => $supplier->id,
            'date' => $firstDate,
            'paid_amount' => 1000,
            'items' => [['product_id' => $product->id, 'quantity' => 12, 'price' => 100]],
        ])->assertSessionHasNoErrors();

        $ledger = $supplier->transactions()->orderBy('transaction_date')->orderBy('id')->get();
        $this->assertSame(700.0, (float) $supplier->fresh()->balance);
        $this->assertSame([200.0, 700.0], $ledger->pluck('balance_after')->map(fn ($v) => (float) $v)->all());
        $this->assertSame(22.0, (float) $product->fresh()->stock);
    }

    public function test_recalculation_includes_opening_balance_invoice_cash_and_return_cash(): void
    {
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 300, 'opening_balance' => 300]);
        $supplier->transactions()->create([
            'type' => 'purchase', 'total_amount' => 1000, 'paid_amount' => 400,
            'balance_after' => 0, 'transaction_date' => Carbon::today()->subDays(3),
        ]);
        $supplier->transactions()->create([
            'type' => 'return_purchase', 'total_amount' => 200, 'paid_amount' => 50,
            'balance_after' => 0, 'transaction_date' => Carbon::today()->subDays(2),
        ]);
        $supplier->transactions()->create([
            'type' => 'payment_made', 'paid_amount' => 250,
            'balance_after' => 0, 'transaction_date' => Carbon::today()->subDay(),
        ]);

        app(AccountingService::class)->recalculateParty($supplier);

        $this->assertSame(500.0, (float) $supplier->fresh()->balance);
        $this->assertSame([900.0, 750.0, 500.0], $supplier->transactions()->orderBy('transaction_date')->pluck('balance_after')->map(fn ($v) => (float) $v)->all());
    }

    public function test_editing_an_old_settled_sale_rebuilds_customer_balance_and_stock(): void
    {
        $customer = Customer::create(['name' => 'Customer', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 50, 'opening_stock' => 50]);
        $firstDate = Carbon::today()->subDays(2)->toDateString();
        $secondDate = Carbon::today()->subDay()->toDateString();

        foreach ([[$firstDate, 1000], [$secondDate, 500]] as [$date, $paid]) {
            $this->post(route('sales.store'), [
                'customer_id' => $customer->id,
                'date' => $date,
                'items' => [['product_id' => $product->id, 'quantity' => 10, 'price' => 100]],
                'paid_amount' => $paid,
            ])->assertSessionHasNoErrors();
        }

        $firstSale = Sale::orderBy('invoice_date')->firstOrFail();
        $this->put(route('sales.update', $firstSale), [
            'customer_id' => $customer->id,
            'date' => $firstDate,
            'paid_amount' => 1000,
            'items' => [['product_id' => $product->id, 'quantity' => 12, 'price' => 100]],
        ])->assertSessionHasNoErrors();

        $ledger = $customer->transactions()->orderBy('transaction_date')->orderBy('id')->get();
        $this->assertSame(700.0, (float) $customer->fresh()->balance);
        $this->assertSame([200.0, 700.0], $ledger->pluck('balance_after')->map(fn ($v) => (float) $v)->all());
        $this->assertSame(28.0, (float) $product->fresh()->stock);
    }

    public function test_sale_and_purchase_return_cannot_make_stock_negative(): void
    {
        $customer = Customer::create(['name' => 'Customer', 'balance' => 0, 'opening_balance' => 0]);
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 2, 'opening_stock' => 2]);
        $date = Carbon::today()->toDateString();

        $this->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'date' => $date,
            'items' => [['product_id' => $product->id, 'quantity' => 3, 'price' => 10]],
            'paid_amount' => 0,
        ])->assertSessionHasErrors('items.0.quantity');

        $this->post(route('suppliers.return', $supplier->id), [
            'product_id' => $product->id,
            'quantity' => 3,
            'amount' => 30,
            'paid_amount' => 0,
            'date' => $date,
        ])->assertSessionHasErrors('quantity');

        $this->assertSame(2.0, (float) $product->fresh()->stock);
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_invoice_date_and_source_link_are_saved_and_updated(): void
    {
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 0, 'opening_stock' => 0]);
        $oldDate = Carbon::today()->subDays(5)->toDateString();
        $newDate = Carbon::today()->subDays(4)->toDateString();

        $this->postPurchase($supplier, $product, $oldDate, 2, 25, 10);
        $purchase = Purchase::firstOrFail();
        $this->assertSame($oldDate, $purchase->invoice_date->toDateString());
        $this->assertSame($purchase->id, $purchase->ledgerTransaction?->source_id);

        $this->put(route('purchases.update', $purchase), [
            'supplier_id' => $supplier->id,
            'date' => $newDate,
            'paid_amount' => 10,
            'items' => [['product_id' => $product->id, 'quantity' => 2, 'price' => 25]],
        ])->assertSessionHasNoErrors();

        $this->assertSame($newDate, $purchase->fresh()->invoice_date->toDateString());
        $this->assertSame($newDate, $purchase->fresh()->ledgerTransaction->transaction_date->toDateString());
    }

    public function test_editing_and_deleting_a_return_rebuilds_both_ledgers(): void
    {
        $customer = Customer::create(['name' => 'Customer', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 10, 'opening_stock' => 10]);
        $date = Carbon::today()->subDay()->toDateString();

        $this->post(route('customers.return', $customer->id), [
            'product_id' => $product->id,
            'quantity' => 2,
            'amount' => 200,
            'paid_amount' => 50,
            'date' => $date,
        ])->assertSessionHasNoErrors();

        $return = $customer->transactions()->where('type', 'return_sale')->firstOrFail();
        $this->put(route('transactions.update', $return), [
            'amount' => 300,
            'quantity' => 3,
            'date' => $date,
        ])->assertSessionHasNoErrors();

        $this->assertSame(-250.0, (float) $customer->fresh()->balance);
        $this->assertSame(13.0, (float) $product->fresh()->stock);

        $this->delete(route('transactions.destroy', $return))->assertSessionHasNoErrors();
        $this->assertSame(0.0, (float) $customer->fresh()->balance);
        $this->assertSame(10.0, (float) $product->fresh()->stock);
    }

    public function test_purchase_cannot_be_deleted_if_later_sales_depend_on_its_stock(): void
    {
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $customer = Customer::create(['name' => 'Customer', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 0, 'opening_stock' => 0]);
        $purchaseDate = Carbon::today()->subDay()->toDateString();
        $saleDate = Carbon::today()->toDateString();

        $this->postPurchase($supplier, $product, $purchaseDate, 2, 10, 20);
        $purchase = Purchase::firstOrFail();
        $this->post(route('sales.store'), [
            'customer_id' => $customer->id,
            'date' => $saleDate,
            'items' => [['product_id' => $product->id, 'quantity' => 2, 'price' => 20]],
            'paid_amount' => 40,
        ])->assertSessionHasNoErrors();

        $this->delete(route('purchases.destroy', $purchase))->assertSessionHasErrors('quantity');

        $this->assertNotNull($purchase->fresh());
        $this->assertSame(0.0, (float) $product->fresh()->stock);
        $this->assertDatabaseCount('purchases', 1);
    }

    public function test_invoice_ledger_entry_cannot_be_deleted_directly(): void
    {
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 0, 'opening_stock' => 0]);
        $this->postPurchase($supplier, $product, Carbon::today()->toDateString(), 1, 100, 0);
        $transaction = $supplier->transactions()->where('type', 'purchase')->firstOrFail();

        $this->delete(route('transactions.destroy', $transaction))->assertSessionHasErrors('transaction');

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'deleted_at' => null]);
        $this->assertSame(100.0, (float) $supplier->fresh()->balance);
    }

    public function test_reports_include_cash_paid_inside_invoices_and_returns(): void
    {
        $customer = Customer::create(['name' => 'Customer', 'balance' => 0, 'opening_balance' => 0]);
        $supplier = Supplier::create(['name' => 'Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $date = Carbon::today()->toDateString();

        foreach ([
            [$customer, 'sale', 300],
            [$customer, 'payment_received', 50],
            [$customer, 'return_sale', 40],
            [$supplier, 'purchase', 100],
            [$supplier, 'payment_made', 20],
            [$supplier, 'return_purchase', 10],
        ] as [$party, $type, $paid]) {
            $party->transactions()->create([
                'type' => $type,
                'paid_amount' => $paid,
                'transaction_date' => $date,
                'balance_after' => 0,
            ]);
        }

        $this->get(route('reports.profit', ['start_date' => $date, 'end_date' => $date]))
            ->assertOk()
            ->assertViewHas('totalPaymentsReceived', 360)
            ->assertViewHas('totalPaymentsMade', 160)
            ->assertViewHas('cashProfit', 200);
    }

    public function test_only_each_partys_latest_invoice_is_marked_as_editable(): void
    {
        $firstCustomer = Customer::create(['name' => 'First Customer', 'balance' => 0, 'opening_balance' => 0]);
        $secondCustomer = Customer::create(['name' => 'Second Customer', 'balance' => 0, 'opening_balance' => 0]);
        $firstSupplier = Supplier::create(['name' => 'First Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $secondSupplier = Supplier::create(['name' => 'Second Supplier', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'Steel', 'stock' => 100, 'opening_stock' => 100]);
        $date = Carbon::today()->toDateString();

        foreach ([$firstCustomer, $firstCustomer, $secondCustomer] as $customer) {
            $this->post(route('sales.store'), [
                'customer_id' => $customer->id,
                'date' => $date,
                'items' => [['product_id' => $product->id, 'quantity' => 1, 'price' => 10]],
                'paid_amount' => 10,
            ])->assertSessionHasNoErrors();
        }

        foreach ([$firstSupplier, $firstSupplier, $secondSupplier] as $supplier) {
            $this->postPurchase($supplier, $product, $date, 1, 10, 10);
        }

        [$oldSale, $latestFirstCustomerSale, $latestSecondCustomerSale] = Sale::orderBy('id')->get();
        [$oldPurchase, $latestFirstSupplierPurchase, $latestSecondSupplierPurchase] = Purchase::orderBy('id')->get();

        $this->get(route('sales.index'))->assertOk()->assertViewHas('sales', function ($sales) use ($oldSale, $latestFirstCustomerSale, $latestSecondCustomerSale) {
            $byId = $sales->getCollection()->keyBy('id');

            return (int) $byId[$oldSale->id]->latest_party_invoice_id === $latestFirstCustomerSale->id
                && (int) $byId[$latestFirstCustomerSale->id]->latest_party_invoice_id === $latestFirstCustomerSale->id
                && (int) $byId[$latestSecondCustomerSale->id]->latest_party_invoice_id === $latestSecondCustomerSale->id;
        });

        $this->get(route('purchases.index'))->assertOk()->assertViewHas('purchases', function ($purchases) use ($oldPurchase, $latestFirstSupplierPurchase, $latestSecondSupplierPurchase) {
            $byId = $purchases->getCollection()->keyBy('id');

            return (int) $byId[$oldPurchase->id]->latest_party_invoice_id === $latestFirstSupplierPurchase->id
                && (int) $byId[$latestFirstSupplierPurchase->id]->latest_party_invoice_id === $latestFirstSupplierPurchase->id
                && (int) $byId[$latestSecondSupplierPurchase->id]->latest_party_invoice_id === $latestSecondSupplierPurchase->id;
        });

        $this->getJson(route('sales.show', $oldSale))->assertJsonPath('can_edit', false);
        $this->getJson(route('sales.show', $latestFirstCustomerSale))->assertJsonPath('can_edit', true);
    }

    private function postPurchase(Supplier $supplier, Product $product, string $date, float $quantity, float $price, float $paid): void
    {
        $this->post(route('purchases.store'), [
            'supplier_id' => $supplier->id,
            'date' => $date,
            'items' => [['product_id' => $product->id, 'quantity' => $quantity, 'price' => $price]],
            'paid_amount' => $paid,
        ])->assertSessionHasNoErrors();
    }
}

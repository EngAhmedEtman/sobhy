<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThermalInvoicePrintTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Customer $customer;
    protected Supplier $supplier;
    protected Product $product;
    protected Sale $sale;
    protected Purchase $purchase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'admin@gmail.com']);

        \App\Models\Setting::set('company_phone', '01018152900');
        \App\Models\Setting::set('phone', '01018152900');

        $this->customer = Customer::create([
            'name' => 'العميل التجريبي',
            'phone' => '01012345678',
            'balance' => 1500,
            'opening_balance' => 0,
        ]);

        $this->supplier = Supplier::create([
            'name' => 'المورد التجريبي',
            'phone' => '01198765432',
            'balance' => 3000,
            'opening_balance' => 0,
        ]);

        $this->product = Product::create([
            'name' => 'صنف تجريبي رقم 1',
            'stock' => 100,
            'opening_stock' => 100,
            'unit' => 'ك',
        ]);

        $this->sale = Sale::create([
            'customer_id' => $this->customer->id,
            'invoice_number' => 'INV-TEST-001',
            'invoice_date' => now()->toDateString(),
            'total_amount' => 500,
            'notes' => 'ملاحظات بيع تجريبية',
        ]);

        SaleItem::create([
            'sale_id' => $this->sale->id,
            'product_id' => $this->product->id,
            'quantity' => 5,
            'unit_price' => 100,
            'total' => 500,
        ]);

        $this->purchase = Purchase::create([
            'supplier_id' => $this->supplier->id,
            'invoice_number' => 'PUR-TEST-001',
            'invoice_date' => now()->toDateString(),
            'total_amount' => 800,
            'notes' => 'ملاحظات شراء تجريبية',
        ]);

        PurchaseItem::create([
            'purchase_id' => $this->purchase->id,
            'product_id' => $this->product->id,
            'quantity' => 10,
            'unit_price' => 80,
            'total' => 800,
        ]);
    }

    public function test_sale_thermal_print_route_renders_successfully_with_80mm_layout(): void
    {
        $response = $this->actingAs($this->user)->get(route('print.sale.thermal', $this->sale));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('فاتورة مبيعات', false);
        $response->assertSee('العميل التجريبي');
        $response->assertSee('INV-TEST-001');
        $response->assertSee('صنف تجريبي رقم 1');
        $response->assertSee('500');
    }

    public function test_purchase_thermal_print_route_renders_successfully_with_80mm_layout(): void
    {
        $response = $this->actingAs($this->user)->get(route('print.purchase.thermal', $this->purchase));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('فاتورة مشتريات', false);
        $response->assertSee('المورد التجريبي');
        $response->assertSee('PUR-TEST-001');
        $response->assertSee('صنف تجريبي رقم 1');
        $response->assertSee('800');
    }

    public function test_standard_print_route_with_thermal_query_param_renders_thermal_view(): void
    {
        $response = $this->actingAs($this->user)->get(route('print.sale', $this->sale) . '?thermal=1');

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('INV-TEST-001');

        $responsePurchase = $this->actingAs($this->user)->get(route('print.purchase', $this->purchase) . '?thermal=1');
        $responsePurchase->assertOk();
        $responsePurchase->assertSee('80mm', false);
        $responsePurchase->assertSee('PUR-TEST-001');
    }

    public function test_standard_print_route_without_thermal_param_renders_a4_view(): void
    {
        $response = $this->actingAs($this->user)->get(route('print.sale', $this->sale));

        $response->assertOk();
        $response->assertSee('A4 portrait', false);
        $response->assertSee('INV-TEST-001');
    }

    public function test_invoice_details_modal_contains_both_a4_and_thermal_80mm_buttons(): void
    {
        $salesHtml = $this->actingAs($this->user)->get(route('sales.index'))->getContent();
        $this->assertStringContainsString('طباعة A4', $salesHtml);
        $this->assertStringContainsString('طباعة حراري (80mm)', $salesHtml);
        $this->assertStringContainsString('thermal=1', $salesHtml);

        $purchasesHtml = $this->actingAs($this->user)->get(route('purchases.index'))->getContent();
        $this->assertStringContainsString('طباعة A4', $purchasesHtml);
        $this->assertStringContainsString('طباعة حراري (80mm)', $purchasesHtml);
        $this->assertStringContainsString('thermal=1', $purchasesHtml);
    }

    public function test_customer_thermal_statement_route_renders_successfully(): void
    {
        $this->customer->update(['balance' => 1500]);
        $response = $this->actingAs($this->user)->get(route('print.customer.thermal', $this->customer));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('كشف حساب عميل');
        $response->assertSee($this->customer->name);
        $response->assertSee('لنا فلوس عند العميل');
        $response->assertSee('مطلوب منه');
    }

    public function test_supplier_thermal_statement_route_renders_successfully(): void
    {
        $this->supplier->update(['balance' => 2000]);
        $response = $this->actingAs($this->user)->get(route('print.supplier.thermal', $this->supplier));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('كشف حساب مورد');
        $response->assertSee($this->supplier->name);
        $response->assertSee('علينا فلوس للمورد');
        $response->assertSee('مستحق له');
    }

    public function test_customer_statement_with_thermal_query_param_renders_thermal_view(): void
    {
        $this->customer->update(['balance' => 1200]);
        $response = $this->actingAs($this->user)->get(route('print.customer', $this->customer) . '?format=thermal');

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee($this->customer->name);
        $response->assertSee('لنا فلوس عند العميل');
    }

    public function test_detailed_operations_thermal_statement_renders_properly(): void
    {
        $this->customer->update(['balance' => 500]);
        $transaction = \App\Models\Transaction::create([
            'transactionable_type' => Customer::class,
            'transactionable_id' => $this->customer->id,
            'type' => 'sale',
            'source_type' => Sale::class,
            'source_id' => $this->sale->id,
            'total_amount' => 500,
            'paid_amount' => 0,
            'balance_after' => 500,
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get(
            route('print.customer.thermal', $this->customer) . '?filter=selected_operations&transaction_ids=' . $transaction->id
        );

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('كشف حساب عمليات وفواتير محددة');
        $response->assertSee($this->sale->invoice_number);
        $response->assertSee('لنا فلوس عند العميل');
    }

    public function test_print_statement_modal_contains_both_standard_and_thermal_buttons(): void
    {
        $customerHtml = $this->actingAs($this->user)->get(route('customers.show', $this->customer))->getContent();
        $this->assertStringContainsString('طباعة عادية (A4)', $customerHtml);
        $this->assertStringContainsString('طباعة حرارية (80mm)', $customerHtml);
        $this->assertStringContainsString("generatePrint('thermal')", $customerHtml);
        $this->assertStringContainsString("generatePrint('standard')", $customerHtml);
    }

    public function test_transaction_thermal_print_renders_payment_receipt(): void
    {
        $transaction = \App\Models\Transaction::create([
            'transactionable_type' => Customer::class,
            'transactionable_id' => $this->customer->id,
            'type' => 'payment_received',
            'paid_amount' => 350,
            'total_amount' => 0,
            'balance_after' => 1150,
            'transaction_date' => now()->toDateString(),
            'notes' => 'تحصيل دفعة نقدية تجريبية',
        ]);

        $response = $this->actingAs($this->user)->get(route('transactions.print.thermal', $transaction));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('إيصال استلام نقدية (تحصيل)');
        $response->assertSee('REC-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT));
        $response->assertSee($this->customer->name);
        $response->assertSee('350');
        $response->assertSee('01018152900');
    }

    public function test_transaction_thermal_print_renders_return_sale_with_item_details(): void
    {
        $transaction = \App\Models\Transaction::create([
            'transactionable_type' => Customer::class,
            'transactionable_id' => $this->customer->id,
            'type' => 'return_sale',
            'product_id' => $this->product->id,
            'quantity' => 2,
            'unit_price' => 100,
            'paid_amount' => 0,
            'total_amount' => 200,
            'balance_after' => 1300,
            'transaction_date' => now()->toDateString(),
            'notes' => 'مرتجع صنف تجريبي',
        ]);

        $response = $this->actingAs($this->user)->get(route('transactions.print.thermal', $transaction));

        $response->assertOk();
        $response->assertSee('80mm', false);
        $response->assertSee('إيصال مرتجع مبيعات');
        $response->assertSee('RET-S-' . str_pad($transaction->id, 5, '0', STR_PAD_LEFT));
        $response->assertSee($this->product->name);
        $response->assertSee('200');
    }

    public function test_invoice_and_statements_display_phone_from_settings_and_not_developer_phone(): void
    {
        \App\Models\Setting::set('company_phone', '01018152900');

        $response = $this->actingAs($this->user)->get(route('print.sale.thermal', $this->sale));
        $response->assertOk();
        $response->assertSee('01018152900');
        $this->assertFalse(str_contains($response->getContent(), '01070191977'));

        $responseStmt = $this->actingAs($this->user)->get(route('print.customer.thermal', $this->customer));
        $responseStmt->assertOk();
        $responseStmt->assertSee('01018152900');
        $this->assertFalse(str_contains($responseStmt->getContent(), '01070191977'));
    }

    public function test_customer_payment_with_print_thermal_flashes_session_keys(): void
    {
        $response = $this->actingAs($this->user)->post(route('customers.payment', $this->customer), [
            'transaction_type' => 'payment_received',
            'amount' => 450,
            'date' => now()->toDateString(),
            'notes' => 'دفعة نقدية مع طباعة حراري',
            'print_thermal' => '1',
        ]);

        $response->assertSessionHas('printed_transaction_id');
        $response->assertSessionHas('auto_print_thermal', true);

        $transactionId = session('printed_transaction_id');
        $transaction = \App\Models\Transaction::findOrFail($transactionId);
        $this->assertEquals(450, $transaction->paid_amount);

        // Verify customer show view renders thermal print modal trigger
        $showResponse = $this->actingAs($this->user)
            ->withSession([
                'printed_transaction_id' => $transactionId,
                'auto_print_thermal' => true,
            ])
            ->get(route('customers.show', $this->customer));

        $showResponse->assertOk();
        $showResponse->assertSee('طباعة إيصال حراري (80mm)');
        $showResponse->assertSee('حفظ وطباعة إيصال حراري');
    }

    public function test_supplier_payment_with_print_thermal_flashes_session_keys(): void
    {
        $response = $this->actingAs($this->user)->post(route('suppliers.payment', $this->supplier), [
            'transaction_type' => 'payment_made',
            'amount' => 600,
            'date' => now()->toDateString(),
            'notes' => 'سداد دفعة مع طباعة حراري',
            'print_thermal' => '1',
        ]);

        $response->assertSessionHas('printed_transaction_id');
        $response->assertSessionHas('auto_print_thermal', true);

        $transactionId = session('printed_transaction_id');
        $transaction = \App\Models\Transaction::findOrFail($transactionId);
        $this->assertEquals(600, $transaction->paid_amount);

        // Verify supplier show view renders thermal print modal trigger
        $showResponse = $this->actingAs($this->user)
            ->withSession([
                'printed_transaction_id' => $transactionId,
                'auto_print_thermal' => true,
            ])
            ->get(route('suppliers.show', $this->supplier));

        $showResponse->assertOk();
        $showResponse->assertSee('طباعة إيصال حراري (80mm)');
        $showResponse->assertSee('حفظ وطباعة إيصال حراري');
    }
}


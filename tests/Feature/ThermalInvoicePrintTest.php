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
}

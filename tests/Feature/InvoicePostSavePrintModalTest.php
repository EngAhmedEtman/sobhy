<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePostSavePrintModalTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Customer $customer;
    protected Supplier $supplier;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'admin@gmail.com']);

        $this->customer = Customer::create([
            'name' => 'عميل الاختبار',
            'phone' => '01011112222',
            'balance' => 0,
            'opening_balance' => 0,
        ]);

        $this->supplier = Supplier::create([
            'name' => 'مورد الاختبار',
            'phone' => '01233334444',
            'balance' => 0,
            'opening_balance' => 0,
        ]);

        $this->product = Product::create([
            'name' => 'منتج تجريبي رقم 1',
            'stock' => 50,
            'opening_stock' => 50,
            'unit' => 'قطعة',
        ]);
    }

    public function test_sale_creation_flashes_invoice_saved_session_data(): void
    {
        $response = $this->actingAs($this->user)->post(route('sales.store'), [
            'customer_id' => $this->customer->id,
            'date' => now()->toDateString(),
            'paid_amount' => 100,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                    'price' => 150,
                ],
            ],
            'notes' => 'فاتورة تجريبية لاختبار المودل',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'تم تسجيل فاتورة المبيعات بنجاح');
        $response->assertSessionHas('invoice_saved');

        $invoiceData = session('invoice_saved');
        $this->assertIsArray($invoiceData);
        $this->assertEquals('sale', $invoiceData['type']);
        $this->assertEquals('فاتورة مبيعات', $invoiceData['type_name']);
        $this->assertEquals(300.0, $invoiceData['total_amount']);
        $this->assertEquals('عميل الاختبار', $invoiceData['party_name']);
        $this->assertStringContainsString('/sales/' . $invoiceData['id'] . '/print', $invoiceData['print_url']);
        $this->assertStringContainsString('/sales/' . $invoiceData['id'] . '/print-thermal', $invoiceData['print_thermal_url']);
    }

    public function test_purchase_creation_flashes_invoice_saved_session_data(): void
    {
        $response = $this->actingAs($this->user)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'date' => now()->toDateString(),
            'paid_amount' => 200,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                    'price' => 80,
                ],
            ],
            'notes' => 'فاتورة مشتريات تجريبية لاختبار المودل',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'تم تسجيل فاتورة المشتريات بنجاح');
        $response->assertSessionHas('invoice_saved');

        $invoiceData = session('invoice_saved');
        $this->assertIsArray($invoiceData);
        $this->assertEquals('purchase', $invoiceData['type']);
        $this->assertEquals('فاتورة مشتريات', $invoiceData['type_name']);
        $this->assertEquals(400.0, $invoiceData['total_amount']);
        $this->assertEquals('مورد الاختبار', $invoiceData['party_name']);
        $this->assertStringContainsString('/purchases/' . $invoiceData['id'] . '/print', $invoiceData['print_url']);
        $this->assertStringContainsString('/purchases/' . $invoiceData['id'] . '/print-thermal', $invoiceData['print_thermal_url']);
    }

    public function test_application_layout_renders_post_save_print_modal_with_options(): void
    {
        $invoiceData = [
            'type' => 'sale',
            'type_name' => 'فاتورة مبيعات',
            'id' => 999,
            'invoice_number' => 'INV-000999',
            'total_amount' => 1250.0,
            'party_name' => 'عميل تجريبي للعرض',
            'party_label' => 'العميل',
            'print_url' => '/sales/999/print',
            'print_thermal_url' => '/sales/999/print-thermal',
        ];

        $response = $this->actingAs($this->user)
            ->withSession(['invoice_saved' => $invoiceData])
            ->get(route('sales.index'));

        $response->assertOk();
        $response->assertSee('تم حفظ الفاتورة بنجاح');
        $response->assertSee('طباعة الفاتورة حرارية');
        $response->assertSee('طباعة الفاتورة عادية');
        $response->assertSee('إغلاق');
        $response->assertSee('INV-000999');
        $response->assertSee('عميل تجريبي للعرض');
    }
}

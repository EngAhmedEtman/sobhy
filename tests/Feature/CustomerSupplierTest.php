<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerSupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_pages_and_transaction_details_can_be_rendered(): void
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $customer = Customer::first() ?? Customer::create(['name' => 'عميل تجريبي', 'phone' => '01000000000', 'balance' => 0]);
        $supplier = Supplier::first() ?? Supplier::create(['name' => 'مورد تجريبي', 'phone' => '01100000000', 'balance' => 0]);
        $product = Product::first() ?? Product::create(['name' => 'حديد تسليح', 'stock' => 100]);

        // 1. Customers index
        $response = $this->get('/customers');
        $response->assertStatus(200);

        // 2. Customers show
        $response = $this->get('/customers/' . $customer->id);
        $response->assertStatus(200);
        $response->assertDontSee('res.json()) .then(data =>');

        // 3. Suppliers index
        $response = $this->get('/suppliers');
        $response->assertStatus(200);

        // 4. Suppliers show
        $response = $this->get('/suppliers/' . $supplier->id);
        $response->assertStatus(200);
        $response->assertDontSee('res.json()) .then(data =>');

        // 5. Store customer payment
        $response = $this->post('/customers/' . $customer->id . '/payment', [
            'amount' => 500,
            'date' => date('Y-m-d'),
            'notes' => 'سداد تجريبي'
        ]);
        $response->assertSessionHasNoErrors();
        
        $customerTransaction = $customer->transactions()->first();
        $this->assertNotNull($customerTransaction);

        // 6. Test transaction json endpoint (view-transaction modal)
        $response = $this->getJson('/transactions/' . $customerTransaction->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id', 'type', 'transaction_date', 'amount', 'party_name'
        ]);

        // 7. Test transaction print endpoint
        $response = $this->get('/transactions/' . $customerTransaction->id . '/print');
        $response->assertStatus(200);

        // 8. Store customer return
        $response = $this->post('/customers/' . $customer->id . '/return', [
            'product_id' => $product->id,
            'quantity' => 5,
            'amount' => 200,
            'paid_amount' => 0,
            'date' => date('Y-m-d'),
            'notes' => 'مرتجع تجريبي'
        ]);
        $response->assertSessionHasNoErrors();

        // 9. Store supplier payment
        $response = $this->post('/suppliers/' . $supplier->id . '/payment', [
            'amount' => 300,
            'date' => date('Y-m-d'),
            'notes' => 'سداد للمورد تجريبي'
        ]);
        $response->assertSessionHasNoErrors();

        $supplierTransaction = $supplier->transactions()->first();
        $this->assertNotNull($supplierTransaction);

        // 10. Test supplier transaction json & print
        $response = $this->getJson('/transactions/' . $supplierTransaction->id);
        $response->assertStatus(200);

        $response = $this->get('/transactions/' . $supplierTransaction->id . '/print');
        $response->assertStatus(200);
    }

    public function test_ajax_filters_work_across_modules(): void
    {
        $user = User::first() ?? User::factory()->create();
        $this->actingAs($user);

        $customer = Customer::create(['name' => 'أحمد علي حسن', 'phone' => '01011112222', 'balance' => 1500]);
        $supplier = Supplier::create(['name' => 'شركة الأهرام للصلب', 'phone' => '01233334444', 'balance' => 5000]);
        $product = Product::create(['name' => 'حديد عز 12 مم', 'stock' => 50]);

        // 1. AJAX search customers
        $res = $this->get('/customers?search=أحمد&ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);
        $res->assertSee('أحمد علي حسن');

        // 2. AJAX filter customers by debt
        $res = $this->get('/customers?balance_status=debt&ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);
        $res->assertSee('1,500');

        // 3. AJAX search suppliers
        $res = $this->get('/suppliers?search=الأهرام&ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);
        $res->assertSee('شركة الأهرام للصلب');

        // 4. AJAX filter suppliers by debt
        $res = $this->get('/suppliers?balance_status=debt&ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);
        $res->assertSee('5,000');

        // 5. AJAX search sales
        $res = $this->get('/sales?ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);

        // 6. AJAX search purchases
        $res = $this->get('/purchases?ajax=1', ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200);
    }
}

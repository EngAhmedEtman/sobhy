<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_global_search_returns_categorized_results(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'name' => 'محمد رضوان',
            'phone' => '01099887766',
            'balance' => 2500,
        ]);

        $supplier = Supplier::create([
            'name' => 'شركة النصر للصلب',
            'phone' => '01122334455',
            'balance' => 10000,
        ]);

        $product = Product::create([
            'name' => 'حديد تسليح 12 مم',
            'stock' => 1200,
            'unit' => 'ك',
        ]);

        $sale = Sale::create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-8899',
            'total_amount' => 5000,
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $supplier->id,
            'invoice_number' => 'PUR-5544',
            'total_amount' => 12000,
        ]);

        // Search for customer by name
        $response = $this->actingAs($user)->getJson('/api/global-search?q=محمد');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'محمد رضوان']);

        // Search for supplier by phone
        $response = $this->actingAs($user)->getJson('/api/global-search?q=01122334455');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'شركة النصر للصلب']);

        // Search for product
        $response = $this->actingAs($user)->getJson('/api/global-search?q=تسليح');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'حديد تسليح 12 مم']);

        // Search for sale invoice
        $response = $this->actingAs($user)->getJson('/api/global-search?q=8899');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'فاتورة مبيعات #INV-8899']);

        // Search for purchase invoice
        $response = $this->actingAs($user)->getJson('/api/global-search?q=5544');
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'فاتورة مشتريات #PUR-5544']);
    }
}

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

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_can_be_rendered_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $customer = Customer::create([
            'name' => 'عميل تجريبي',
            'phone' => '01012345678',
            'balance' => 1500,
        ]);

        $supplier = Supplier::create([
            'name' => 'مورد تجريبي',
            'phone' => '01112345678',
            'balance' => 3200,
        ]);

        $product = Product::create([
            'name' => 'حديد سكراب',
            'stock' => 500,
            'unit' => 'ك',
        ]);

        Transaction::create([
            'transactionable_type' => Customer::class,
            'transactionable_id' => $customer->id,
            'type' => 'sale',
            'total_amount' => 1500,
            'paid_amount' => 500,
            'balance_after' => 1000,
            'transaction_date' => now(),
            'notes' => 'فاتورة مبيعات',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('لوحة التحكم والمعلومات');
        $response->assertSee('تواصل مع الدعم الفني');
        $response->assertSee('01070191977');
        $response->assertSee('coderaEg.com');
        $response->assertSee('عميل تجريبي');
        $response->assertSee('مورد تجريبي');
    }
}

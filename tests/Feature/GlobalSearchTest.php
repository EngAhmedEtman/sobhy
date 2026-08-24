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

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_header_invoice_results_open_the_details_modal(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee("openInvoice(item, 'sale')", false)
            ->assertSee("openInvoice(item, 'purchase')", false)
            ->assertSee('global-invoice-modal-title', false)
            ->assertSee('invoicePrintUrl', false);
    }

    public function test_header_search_finds_parties_and_their_latest_two_invoices(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        $customer = Customer::create([
            'name' => 'أحمد رضوان',
            'phone' => '01099887766',
            'balance' => 2500,
            'opening_balance' => 2500,
        ]);
        $otherCustomer = Customer::create([
            'name' => 'عميل آخر',
            'phone' => '01000000000',
            'balance' => 0,
            'opening_balance' => 0,
        ]);
        $supplier = Supplier::create([
            'name' => 'شركة النصر للصلب',
            'phone' => '01122334455',
            'balance' => 10000,
            'opening_balance' => 10000,
        ]);
        $product = Product::create([
            'name' => 'حديد تسليح 12 مم',
            'stock' => 1200,
            'opening_stock' => 1200,
            'unit' => 'ك',
        ]);

        $customerSales = collect();
        foreach (range(1, 3) as $index) {
            $sale = Sale::create([
                'customer_id' => $customer->id,
                'invoice_number' => 'INV-A'.$index,
                'invoice_date' => now()->subDays(4 - $index),
                'total_amount' => 100 * $index,
            ]);
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => 100 * $index,
                'total' => 100 * $index,
            ]);
            $customerSales->push($sale);
        }

        Sale::create([
            'customer_id' => $otherCustomer->id,
            'invoice_number' => 'INV-OTHER',
            'invoice_date' => now(),
            'total_amount' => 999,
        ]);

        $supplierPurchases = collect();
        foreach (range(1, 3) as $index) {
            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'invoice_number' => 'PUR-S'.$index,
                'invoice_date' => now()->subDays(4 - $index),
                'total_amount' => 200 * $index,
            ]);
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => 200 * $index,
                'total' => 200 * $index,
            ]);
            $supplierPurchases->push($purchase);
        }

        $customerResponse = $this->getJson('/api/global-search?q='.urlencode('احمد'))
            ->assertOk()
            ->assertJsonPath('customers.0.id', $customer->id);
        $this->assertSame(
            [$customerSales[2]->id, $customerSales[1]->id],
            collect($customerResponse->json('sales'))->pluck('id')->all()
        );

        $supplierResponse = $this->getJson('/api/global-search?q='.urlencode('٠١١٢٢٣٣٤٤٥٥'))
            ->assertOk()
            ->assertJsonPath('suppliers.0.id', $supplier->id);
        $this->assertSame(
            [$supplierPurchases[2]->id, $supplierPurchases[1]->id],
            collect($supplierResponse->json('purchases'))->pluck('id')->all()
        );

        $this->getJson('/api/global-search?q='.urlencode('#'.$customer->id))
            ->assertOk()
            ->assertJsonFragment(['title' => 'أحمد رضوان']);
    }

    public function test_product_search_returns_product_and_latest_related_sales_and_purchases(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        $customer = Customer::create(['name' => 'عميل', 'balance' => 0, 'opening_balance' => 0]);
        $supplier = Supplier::create(['name' => 'مورد', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create([
            'name' => 'حديد تسليح مميز',
            'stock' => 50,
            'opening_stock' => 50,
            'unit' => 'ك',
        ]);

        foreach (range(1, 3) as $index) {
            $sale = Sale::create([
                'customer_id' => $customer->id,
                'invoice_number' => 'PRODUCT-SALE-'.$index,
                'invoice_date' => now(),
                'total_amount' => $index * 10,
            ]);
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $index * 10,
                'total' => $index * 10,
            ]);

            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'invoice_number' => 'PRODUCT-PURCHASE-'.$index,
                'invoice_date' => now(),
                'total_amount' => $index * 5,
            ]);
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $index * 5,
                'total' => $index * 5,
            ]);
        }

        $response = $this->getJson('/api/global-search?q='.urlencode('تسليح'))
            ->assertOk()
            ->assertJsonPath('products.0.id', $product->id)
            ->assertJsonCount(2, 'sales')
            ->assertJsonCount(2, 'purchases');

        $this->assertStringContainsString('/products/'.$product->id, $response->json('products.0.url'));
        $this->assertSame([3, 2], collect($response->json('sales'))->pluck('id')->all());
        $this->assertSame([3, 2], collect($response->json('purchases'))->pluck('id')->all());
    }

    public function test_search_finds_invoice_number_and_returns_empty_shape_safely(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));
        $customer = Customer::create(['name' => 'عميل', 'balance' => 0, 'opening_balance' => 0]);
        Sale::create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-8899',
            'invoice_date' => now(),
            'total_amount' => 5000,
        ]);

        $this->getJson('/api/global-search?q=8899')
            ->assertOk()
            ->assertJsonFragment(['title' => 'فاتورة مبيعات #INV-8899']);

        $this->getJson('/api/global-search?q=')
            ->assertOk()
            ->assertExactJson([
                'pages' => [],
                'customers' => [],
                'suppliers' => [],
                'products' => [],
                'sales' => [],
                'purchases' => [],
                'transactions' => [],
                'total_count' => 0,
            ]);
    }
}

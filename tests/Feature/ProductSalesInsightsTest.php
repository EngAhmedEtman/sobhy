<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSalesInsightsTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_pages_show_historical_prices_and_sales_summary(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        $customer = Customer::create(['name' => 'عميل الأسعار', 'balance' => 0, 'opening_balance' => 0]);
        $product = Product::create(['name' => 'منتج التحليلات', 'stock' => 50, 'opening_stock' => 55, 'unit' => 'كيلو']);

        $oldSale = $this->createSale($customer->id, $product->id, 'INV-000014', '2026-08-01', 2, 100);
        $latestSale = $this->createSale($customer->id, $product->id, 'INV-000015', '2026-08-10', 3, 150);

        foreach ([$oldSale => ['2026-08-01', 2], $latestSale => ['2026-08-10', 3]] as $saleId => [$date, $quantity]) {
            ProductTransaction::create([
                'product_id' => $product->id,
                'type' => 'sale',
                'transaction_date' => $date,
                'quantity' => $quantity,
                'balance_after' => 50,
                'related_type' => Sale::class,
                'related_id' => $saleId,
                'notes' => 'فاتورة بيع اختبارية',
            ]);
        }

        $this->get(route('products.index'))
            ->assertOk()
            ->assertViewHas('products', function ($products) {
                return (float) $products->first()->last_sale_price === 150.0;
            });

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('INV-000014')
            ->assertSee('INV-000015')
            ->assertViewHas('salesSummary', function (array $summary) {
                return round($summary['average_price'], 2) === 130.0
                    && $summary['lowest_price'] === 100.0
                    && $summary['highest_price'] === 150.0
                    && $summary['total_sales'] === 650.0
                    && $summary['total_quantity'] === 5.0;
            });

        $this->get(route('reports.products', [
            'product_id' => $product->id,
            'start_date' => '2026-08-10',
            'end_date' => '2026-08-10',
        ]))
            ->assertOk()
            ->assertSee('INV-000015')
            ->assertViewHas('salesSummary', fn (array $summary) => $summary['average_price'] === 150.0
                && $summary['total_sales'] === 450.0);
    }

    private function createSale(
        int $customerId,
        int $productId,
        string $invoiceNumber,
        string $date,
        float $quantity,
        float $unitPrice
    ): int {
        $sale = Sale::create([
            'customer_id' => $customerId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $date,
            'total_amount' => $quantity * $unitPrice,
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $quantity * $unitPrice,
        ]);

        return $sale->id;
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceDetailsModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_details_modals_scroll_only_the_items_and_show_quantity_unit_after_number(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'admin@gmail.com']));

        $html = $this->get(route('sales.index'))
            ->assertOk()
            ->getContent();

        $this->assertSame(2, substr_count($html, 'data-invoice-items-scroll'));
        $this->assertStringContainsString('<span x-text="item.quantity"></span><span>ك</span>', $html);
        $this->assertStringContainsString('<span x-text="invoiceItem.quantity"></span><span>ك</span>', $html);
        $this->assertStringNotContainsString("item.quantity + ' ك'", $html);
        $this->assertStringContainsString('col-span-2 text-center text-sm text-slate-600', $html);
    }
}

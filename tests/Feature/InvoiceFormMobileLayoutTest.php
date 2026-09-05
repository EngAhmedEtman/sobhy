<?php

namespace Tests\Feature;

use Tests\TestCase;

class InvoiceFormMobileLayoutTest extends TestCase
{
    public function test_sale_and_purchase_forms_use_one_touch_scroll_container_on_mobile(): void
    {
        foreach (['sale-form.blade.php', 'purchase-form.blade.php'] as $view) {
            $html = file_get_contents(resource_path('views/components/modals/'.$view));

            $this->assertStringContainsString(
                'touch-pan-y flex-col overflow-y-auto overscroll-contain lg:overflow-hidden',
                $html
            );
            $this->assertStringContainsString(
                'grid flex-none grid-cols-1 gap-4 overflow-visible lg:min-h-0 lg:flex-1 lg:grid-cols-12 lg:grid-rows-1 lg:gap-6 lg:overflow-hidden',
                $html
            );
            $this->assertStringContainsString(
                'flex min-h-[24rem] flex-col lg:min-h-0 lg:col-span-8',
                $html
            );
            $this->assertStringContainsString(
                'flex min-h-0 flex-1 flex-col overflow-visible rounded-xl border border-slate-200 bg-white shadow-sm lg:overflow-hidden',
                $html
            );
            $this->assertStringContainsString(
                'flex-1 touch-pan-y divide-y divide-slate-100 overflow-visible overscroll-auto lg:min-h-0 lg:overflow-y-auto lg:overscroll-contain',
                $html
            );
            $this->assertStringNotContainsString(
                'min-h-0 flex-1 divide-y divide-slate-100 overflow-y-auto overscroll-contain',
                $html
            );
        }
    }
}

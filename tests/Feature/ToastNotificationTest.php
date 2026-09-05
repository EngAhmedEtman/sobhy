<?php

namespace Tests\Feature;

use Tests\TestCase;

class ToastNotificationTest extends TestCase
{
    public function test_error_toasts_stay_longer_and_can_be_closed_manually(): void
    {
        $store = file_get_contents(resource_path('js/app.js'));
        $toast = file_get_contents(resource_path('views/components/toast.blade.php'));

        $this->assertStringContainsString("this.duration = type === 'error' ? 12000 : 6000", $store);
        $this->assertStringContainsString('}, this.duration);', $store);
        $this->assertStringContainsString('@click="$store.toast.hide()"', $toast);
        $this->assertStringContainsString('aria-label="إغلاق التنبيه"', $toast);
        $this->assertStringContainsString('$store.toast.duration - 100', $toast);
    }
}

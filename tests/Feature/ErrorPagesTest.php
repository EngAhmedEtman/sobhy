<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    public function test_404_error_page_can_be_rendered(): void
    {
        $response = $this->get('/non-existent-page-url-12345');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('الصفحة غير موجودة');
        $response->assertSee('01070191977');
        $response->assertSee('coderaEg.com');
        $response->assertSee('favicon.svg');
    }
}

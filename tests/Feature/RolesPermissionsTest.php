<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_page_renders_with_translated_permissions(): void
    {
        $role = Role::create([
            'name' => 'مدير النظام',
            'permissions' => ['dashboard.view', 'sales.view', 'customers.create'],
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $response = $this->actingAs($user)->get(route('roles.index'));

        $response->assertStatus(200);
        $response->assertSee('إدارة الأدوار والصلاحيات');
        $response->assertSee('عرض لوحة المعلومات (الرئيسية)');
        $response->assertSee('عرض فواتير المبيعات');
    }

    public function test_role_model_provides_translated_permissions(): void
    {
        $role = Role::create([
            'name' => 'محاسب',
            'permissions' => ['dashboard.view', 'purchases.create'],
        ]);

        $this->assertArrayHasKey('dashboard.view', $role->translated_permissions);
        $this->assertEquals('عرض لوحة المعلومات (الرئيسية)', $role->translated_permissions['dashboard.view']);
        $this->assertEquals('إنشاء فاتورة مشتريات', $role->translated_permissions['purchases.create']);
    }
}

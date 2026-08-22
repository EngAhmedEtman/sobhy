<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. All Permissions definition
        $allPermissions = [
            'dashboard.view',
            'sales.view', 'sales.create', 'sales.update', 'sales.delete',
            'purchases.view', 'purchases.create', 'purchases.update', 'purchases.delete',
            'products.view', 'products.create', 'products.update', 'products.delete',
            'customers.view', 'customers.create', 'customers.update', 'customers.delete',
            'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
            'debts.view',
            'reports.view',
            'settings.manage',
            'users.view', 'users.create', 'users.update', 'users.delete',
            'roles.view', 'roles.create', 'roles.update', 'roles.delete',
        ];

        // 2. Roles Seeding
        $adminRole = Role::updateOrCreate(
            ['name' => 'المسؤول'],
            ['permissions' => $allPermissions]
        );

        $accountantPermissions = [
            'dashboard.view',
            'sales.view', 'sales.create',
            'purchases.view', 'purchases.create',
            'products.view',
            'customers.view', 'customers.create', 'customers.update',
            'suppliers.view', 'suppliers.create', 'suppliers.update',
            'debts.view',
            'reports.view',
        ];

        Role::updateOrCreate(
            ['name' => 'محاسب'],
            ['permissions' => $accountantPermissions]
        );

        // 3. Developer Admin User (admin@gmail.com / 123456789)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'مطور النظام (Codera)',
                'password' => Hash::make('123456789'),
                'role_id' => $adminRole->id,
            ]
        );

        // 4. Default Settings Seeding
        Setting::set('company_name', 'مؤسسة صبحي رضا لتجارة الخردة', 'string', 'general');
        Setting::set('phone', '01070191977', 'string', 'general');
        Setting::set('currency', 'ج.م', 'string', 'general');
    }
}

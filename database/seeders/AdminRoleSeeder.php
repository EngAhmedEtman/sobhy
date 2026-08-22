<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        $adminRole = Role::updateOrCreate(
            ['name' => 'المسؤول'],
            ['permissions' => $allPermissions]
        );

        // Assign to first user if exists
        $user = User::first();
        if ($user) {
            $user->update(['role_id' => $adminRole->id]);
        }
    }
}

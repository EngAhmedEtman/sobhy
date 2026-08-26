<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class AccountantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountantPermissions = [
            'purchases.view', 'purchases.create', 'purchases.update', 'purchases.delete',
            'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
        ];

        Role::updateOrCreate(
            ['name' => 'المحاسب'],
            ['permissions' => $accountantPermissions]
        );
    }
}

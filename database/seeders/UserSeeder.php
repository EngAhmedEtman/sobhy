<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'المدير')->first();

        User::updateOrCreate(
            ['email' => 'sobhy@gmail.com'],
            [
                'name' => 'Sobhy Reda',
                'password' => Hash::make('123456789'),
                'role_id' => $adminRole ? $adminRole->id : 1,
            ]
        );
    }
}

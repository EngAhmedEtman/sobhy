<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

        $customer = Customer::create([
            'name' => 'أحمد الشامي',
            'phone' => '01012345678',
            'balance' => 28750
        ]);

        $customer->transactions()->createMany([
            [
                'type' => 'sale',
                'notes' => 'بلاستيك',
                'total_amount' => 7600,
                'paid_amount' => 0,
                'balance_after' => 7600,
                'transaction_date' => '2024-05-10',
            ],
            [
                'type' => 'payment_received',
                'notes' => 'تحويل',
                'total_amount' => 0,
                'paid_amount' => 3500,
                'balance_after' => 4100,
                'transaction_date' => '2024-05-12',
            ],
            [
                'type' => 'sale',
                'notes' => 'نحاس',
                'total_amount' => 9450,
                'paid_amount' => 0,
                'balance_after' => 13550,
                'transaction_date' => '2024-05-15',
            ]
        ]);

        // Just recreating exact math
        $c2 = Customer::create([
            'name' => 'أحمد الشامي',
            'phone' => '01012345678',
            'balance' => 28750
        ]);
        
        $c2->transactions()->createMany([
            ['type' => 'sale', 'notes' => 'كرتون', 'total_amount' => 12000, 'paid_amount' => 0, 'balance_after' => 28750, 'transaction_date' => '2024-05-28'],
            ['type' => 'payment_received', 'notes' => 'دفعة نقدية', 'total_amount' => 0, 'paid_amount' => 5000, 'balance_after' => 16750, 'transaction_date' => '2024-05-22'],
            ['type' => 'sale', 'notes' => 'حديد', 'total_amount' => 17650, 'paid_amount' => 0, 'balance_after' => 21750, 'transaction_date' => '2024-05-18'],
            ['type' => 'sale', 'notes' => 'نحاس', 'total_amount' => 9450, 'paid_amount' => 0, 'balance_after' => 4100, 'transaction_date' => '2024-05-15'],
            ['type' => 'payment_received', 'notes' => 'تحويل', 'total_amount' => 0, 'paid_amount' => 3500, 'balance_after' => -5350, 'transaction_date' => '2024-05-12'],
            ['type' => 'sale', 'notes' => 'بلاستيك', 'total_amount' => 7600, 'paid_amount' => 0, 'balance_after' => -1850, 'transaction_date' => '2024-05-10'],
        ]);
        
        // I will use mathematically correct values.
        $c2->transactions()->delete();
        $c2->delete();
        
        $c3 = Customer::create([
            'name' => 'أحمد الشامي',
            'phone' => '01012345678',
            'balance' => 28750
        ]);
        
        $c3->transactions()->createMany([
            ['type' => 'sale', 'notes' => 'بلاستيك', 'total_amount' => 7600, 'paid_amount' => 0, 'balance_after' => 7600, 'transaction_date' => '2024-05-10'],
            ['type' => 'payment_received', 'notes' => 'تحويل', 'total_amount' => 0, 'paid_amount' => 3500, 'balance_after' => 4100, 'transaction_date' => '2024-05-12'],
            ['type' => 'sale', 'notes' => 'نحاس', 'total_amount' => 9450, 'paid_amount' => 0, 'balance_after' => 13550, 'transaction_date' => '2024-05-15'],
            ['type' => 'sale', 'notes' => 'حديد', 'total_amount' => 17650, 'paid_amount' => 0, 'balance_after' => 31200, 'transaction_date' => '2024-05-18'],
            ['type' => 'payment_received', 'notes' => 'دفعة نقدية', 'total_amount' => 0, 'paid_amount' => 5000, 'balance_after' => 26200, 'transaction_date' => '2024-05-22'],
            ['type' => 'sale', 'notes' => 'كرتون', 'total_amount' => 2550, 'paid_amount' => 0, 'balance_after' => 28750, 'transaction_date' => '2024-05-28'],
        ]);
        
        $customer->delete();
        $customer->transactions()->delete();

        // Supplier
        $supplier = Supplier::create([
            'name' => 'محمود علي',
            'phone' => '01123456789',
            'balance' => 18400
        ]);

        $supplier->transactions()->createMany([
            ['type' => 'purchase', 'notes' => 'بلاستيك', 'total_amount' => 8900, 'paid_amount' => 0, 'balance_after' => 8900, 'transaction_date' => '2024-05-05'],
            ['type' => 'payment_made', 'notes' => 'نقدي', 'total_amount' => 0, 'paid_amount' => 12450, 'balance_after' => -3550, 'transaction_date' => '2024-05-09'],
            ['type' => 'purchase', 'notes' => 'نحاس', 'total_amount' => 14100, 'paid_amount' => 0, 'balance_after' => 10550, 'transaction_date' => '2024-05-12'],
            ['type' => 'payment_made', 'notes' => 'تحويل بنكي', 'total_amount' => 0, 'paid_amount' => 9000, 'balance_after' => 1550, 'transaction_date' => '2024-05-16'],
            ['type' => 'purchase', 'notes' => 'كرتون', 'total_amount' => 13750, 'paid_amount' => 0, 'balance_after' => 15300, 'transaction_date' => '2024-05-20'],
            ['type' => 'purchase', 'notes' => 'حديد', 'total_amount' => 3100, 'paid_amount' => 0, 'balance_after' => 18400, 'transaction_date' => '2024-05-27'],
        ]);
    }
}

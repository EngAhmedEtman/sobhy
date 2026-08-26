<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'حديد تقيل',
            'زهر مكن',
            'حديد',
            'زهر صحى',
            'بودى',
            'صفيح',
            'احمر',
            'احمر حلة',
            'أصفر',
            'ناشفة مكن',
            'ناشفة',
            'انية',
            'طرية',
            'تصفية مميزة',
            'تصفية عادية',
            'بطارية',
            'رصاص',
            'كاوتش',
            'بي بي سي',
            'ورق',
            'نعل',
            'كرتون',
            'باغة',
            'أكصدام',
            'برنيكة سمرة',
            'فبر',
            'كانز',
            'شفاف',
            'ريسيفر',
            'باغه خضرة',
            'بورسلين شفاف',
            'استالس',
            'تليفون',
            'بوردة',
            'ماتور',
            'بلاستيك',
            'رادتير',
            'مكواة',
            'مكواة',
            'تلفزيون كبير',
            'تلفزيون',
            'شاشة كبيرة',
            'شاشة وسط',
            'شاشة صغيرة',
            'شمبر'
        ];

        // Remove duplicates like 'مكواة' which is repeated
        $products = array_unique($products);

        // Empty the table first if we want to re-seed cleanly (optional, but good if they run db:seed alone)
        // DB::table('products')->truncate(); 

        foreach ($products as $productName) {
            Product::firstOrCreate([
                'name' => trim($productName),
            ], [
                'stock' => 0,
            ]);
        }
    }
}

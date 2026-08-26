<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'حديد تقيل', 'زهر مكن', 'حديد', 'زهر صحى', 'بودى', 'صفيح',
            'احمر', 'احمر حلة', 'أصفر', 'ناشفة مكن', 'ناشفة', 'انية',
            'طرية', 'تصفية مميزة', 'تصفية عادية', 'بطارية', 'رصاص',
            'كاوتش', 'بي بي سي', 'ورق', 'نعل', 'كرتون', 'باغة',
            'أكصدام', 'برنيكة سمرة', 'فبر', 'كانز', 'شفاف', 'ريسيفر',
            'باغه خضرة', 'بورسلين شفاف', 'استالس', 'تليفون', 'بوردة',
            'ماتور', 'بلاستيك', 'رادتير', 'مكواة', 'تلفزيون كبير',
            'تلفزيون', 'شاشة كبيرة', 'شاشة وسط', 'شاشة صغيرة', 'شمبر'
        ];

        // Remove duplicates if any
        $products = array_unique($products);

        foreach ($products as $productName) {
            Product::firstOrCreate([
                'name' => trim($productName),
            ], [
                'stock' => 0,
                'opening_stock' => 0,
                'unit' => 'كيلو',
            ]);
        }
    }
}

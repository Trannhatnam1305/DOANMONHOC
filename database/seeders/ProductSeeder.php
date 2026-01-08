<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Danh sách tên sản phẩm thực tế để test cho đẹp
        $productNames = [
            'iPhone 15 Pro Max', 'Samsung Galaxy S23 Ultra', 'MacBook Air M2', 
            'Sony WH-1000XM5', 'iPad Pro M2', 'Dell XPS 13', 
            'Apple Watch Series 9', 'AirPods Pro Gen 2', 'Canon EOS R6'
        ];

        foreach (range(1, 20) as $index) {
            $price = $faker->randomFloat(2, 500, 2000) * 10000; // Giá từ 5tr - 20tr
            
            DB::table('products')->insert([
                'name'           => $faker->randomElement($productNames) . ' ' . $faker->numerify('###'),
                'price'          => $price,
                'discount_price' => $price * 0.9, // Giảm 10%
                'description'    => $faker->paragraph(3),
                'image'          => 'product-' . ($index % 6 + 1) . '.jpg', // Sẽ lấy product-1.jpg đến product-6.jpg
                'category_id'    => rand(1, 5),
                'brand_id'       => rand(1, 5),
                'loai'           => rand(0, 3), // 0: Slider, 1: Bán chạy, 2: Xem gần đây, 3: Mới nhất
                'tags'           => 'electronics, mobile, trending',
                'status'         => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
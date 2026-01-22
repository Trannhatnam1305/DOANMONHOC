<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo "Cha" trước
       $name = 'Điện thoại';
       $categoryId = DB::table('categories')->insertGetId([
        'name'       => $name,
        'slug'       => Str::slug($name) . '-' . Str::random(5), // Tạo: dien-thoai-abcde
        'status'     => 1, // 1: Hiển thị trên menu/web
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        $brandId = DB::table('brands')->insertGetId([
            'name' => 'Apple',
            'slug' => 'apple-' . Str::random(3),
            'logo' => 'apple-logo.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $supplierId = DB::table('suppliers')->insertGetId([
            'name' => 'Công ty Xuất Nhập Khẩu A',
            'phone' => '0987654321',
            'email' => 'contact@' . Str::random(5) . '.com',
            'address' => '123 Đường ABC, TP.HCM',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Hàm hỗ trợ tải ảnh mẫu về storage
        Storage::disk('public')->makeDirectory('products');
        
        $generateImage = function($index) {
            $name = 'product_test_' . $index . '_' . time() . '.jpg';
            try {
                // Lấy ảnh từ Unsplash hoặc Picsum (600x600)
                $content = file_get_contents("https://picsum.photos/600/600?random=" . $index);
                Storage::disk('public')->put('products/' . $name, $content);
                return 'products/' . $name;
            } catch (\Exception $e) {
                return null;
            }
        };

        // 3. Tạo danh sách sản phẩm với đường dẫn ảnh đã lưu trong storage
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'price' => 34000000,
                'discount_price' => 32000000,
                'description' => 'Sản phẩm flagship mới nhất từ Apple.',
                'image' => $generateImage(1), // Lưu: products/tên-ảnh.jpg
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'supplier_id' => $supplierId,
                'loai' => 'High-end',
                'tags' => 'ios,apple',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'iPhone 14 Plus',
                'price' => 20000000,
                'discount_price' => 18500000,
                'description' => 'Cấu hình mạnh mẽ, màn hình lớn.',
                'image' => $generateImage(2),
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'supplier_id' => $supplierId,
                'loai' => 'Mid-range',
                'tags' => 'ios,apple',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'iPad Pro M2',
                'price' => 25000000,
                'discount_price' => 24000000,
                'description' => 'Hiệu năng cực đỉnh.',
                'image' => $generateImage(3),
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'supplier_id' => $supplierId,
                'loai' => 'Tablet',
                'tags' => 'ipad,apple',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
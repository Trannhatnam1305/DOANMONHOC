<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hệ thống & Users
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('name')->nullable(); 
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('birthday')->nullable(); 
            $table->tinyInteger('gender')->nullable()->comment('0:Nam, 1:Nữ, 2:Khác'); 
            $table->text('address')->nullable(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('status')->default(1);
            $table->integer('role')->default(0); 
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Bảng cha
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        // 3. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('loai')->nullable();
            $table->string('tags')->nullable();
            $table->integer('stock_quantity')->default(0); 
            $table->integer('status')->default(1);
            $table->integer('views')->default(0); 
            $table->softDeletes();
            $table->timestamps();
        });

        // 4. MỚI: Bảng Reviews (Phục vụ mục 12, 16, 17)
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('rating')->default(5); // Chấm điểm sao
            $table->text('comment')->nullable(); // Bình luận
            $table->timestamps();
        });

        // 5. MỚI: Bảng Wishlists (Phục vụ mục 14)
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Orders & Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id'); 
            $table->foreignId('product_id')->constrained('products'); // Sửa lại để link chuẩn
            $table->integer('quantity');
            $table->decimal('price', 11, 2);
            $table->timestamps();
        });
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Liên kết với người dùng
            $table->unsignedBigInteger('product_id'); // Liên kết với sản phẩm
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Khóa ngoại để đảm bảo dữ liệu chuẩn
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // ... (Các bảng phụ khác giữ nguyên như settings, contacts, jobs)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('full_name');
            $table->string('phone');
            $table->string('address');
            $table->text('note')->nullable();
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // Chờ xử lý
            $table->timestamps();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

    
 }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('users');
    }

    





};
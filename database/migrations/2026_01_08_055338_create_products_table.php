<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Tương ứng int(11) Primary Key, Auto Increment
            $table->string('name', 256);
            $table->decimal('price', 11, 2);
            $table->decimal('discount_price', 11, 2);
            $table->text('description');
            $table->text('image');
            
            // Khai báo category_id và brand_id
            $table->integer('category_id');
            $table->integer('brand_id');

            // Cột 'loai' có kèm comment như bạn yêu cầu
            $table->integer('loai')->comment('0-san pham moi nhat, 1-san pham ban nhieu nhat');
            
            $table->text('tags');
            $table->integer('status');
            
            $table->timestamps(); // Tự động tạo created_at và updated_at (Nên có trong Laravel)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
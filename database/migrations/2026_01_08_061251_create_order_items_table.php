<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // ID của đơn hàng chính
            $table->integer('order_id'); 
            // ID của sản phẩm được mua
            $table->integer('product_id'); 
            // Số lượng mua
            $table->integer('quantity');
            // Giá tại thời điểm mua (để tránh bị đổi giá sau này)
            $table->decimal('price', 11, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
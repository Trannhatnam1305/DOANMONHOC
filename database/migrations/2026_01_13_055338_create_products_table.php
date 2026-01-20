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
                $table->id();
                $table->string('name');
                $table->decimal('price', 15, 2);             // Khớp với SQL của bạn
                $table->decimal('discount_price', 15, 2)->nullable(); // Khớp với SQL của bạn
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                
                // Các cột khóa ngoại
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
                $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
                
                // Các cột phân loại
                $table->string('loai')->nullable();          // Khớp với 'loai' trong lỗi
                $table->string('tags')->nullable();          // Khớp với 'tags' trong lỗi
                $table->integer('status')->default(1);
                $table->timestamps();
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
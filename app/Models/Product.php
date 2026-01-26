<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'price', 'discount_price', 'description', 
        'category_id', 'brand_id', 'supplier_id', 
        'loai', 'tags', 'status', 'image'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id'); //
    }

    public function reviews() {
        return $this->hasMany(Review::class, 'product_id');
    }
}
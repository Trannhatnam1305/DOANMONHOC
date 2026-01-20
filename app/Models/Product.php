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
}

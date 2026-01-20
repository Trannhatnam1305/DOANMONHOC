<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'products'; // 👈 BẢNG THẬT TRONG DB

    protected $primaryKey = 'id';

    public $timestamps = false; // vì bảng products không có created_at, updated_at
}

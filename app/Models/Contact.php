<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    // Tên bảng (nếu bạn đặt tên bảng khác contact thì khai báo vào đây)
    protected $table = 'contacts'; 

    // QUAN TRỌNG: Danh sách các cột được phép lưu dữ liệu
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'title', 
        'content', 
        'status'
    ];

    // Mặc định Laravel đã để cái này là true, nên created_at và updated_at sẽ tự chạy
    public $timestamps = true; 
}
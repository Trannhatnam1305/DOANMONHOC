<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ResultToken;
use App\Http\Middleware\IsLogin;

// Route::get('/', function () {
//     return view('welcome');
// });


//--------------------------------------------Route User-----------------------------------------------

//Router Trang Chủ
Route::get('/', [WebController::class, 'index']);
//Route Trang Cart
Route::get('/cart', [WebController::class, 'cart']);
//Route Trang Checkout
Route::get('/checkout', [WebController::class, 'checkout']);
//Route Trang Shop
Route::get('/shop', [WebController::class, 'shop']);
//Route Trang Single Product
Route::get('/single-product', [WebController::class, 'singleproduct']);
//Route Trang Contact
Route::get('/contact', [WebController::class, 'contact']);
Route::post('/addContact', [ContactController::class, 'addContact']);



//--------------------------------------------Route Admin-----------------------------------------------

//Route Trang Chủ Admin + Kiểm Tra Login Bằng Middleware
Route::get('/admin',[AdminController::class,'LoadAdmin'])->name('admin.index')->middleware(IsLogin::class);
//Route Trang Login Admin
//Route::get('/admin/login',[AdminController::class,'loginPage'])->name('admin.login');
//Route::post("/admin/login",[AdminController::class,'login']);
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Sản Phẩm
Route::get('/admin/sanpham', [AdminController::class, 'SanPham'])->name('admin.sanpham');
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Nhà Cung Cấp
Route::get('/admin/nhacungcap', [AdminController::class, 'NhaCungCap'])->name('admin.nhacungcap');
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Loại Sản Phẩm
Route::get('/admin/loaisanpham', [AdminController::class, 'LoaiSanPham'])->name('admin.loaisanpham');
//Router SảnPhẩm Admin -> Người Dùng
Route::get('/admin/nguoidung', [AdminController::class, 'NguoiDung'])->name('admin.nguoidung');
//Router AddSanPham
Route::get('/admin/addsanpham', [AdminController::class, 'AddProduct'])->name('admin.addProduct');
Route::post('/admin/addsanpham', [AdminController::class, 'ThemSanPham']);
//Route Sản Phẩm Admin -> Xóa
Route::delete('/admin/sanpham/xoa/{id}', [AdminController::class, 'XoaSanPham'])->name('admin.sanpham.xoa');
//Route Trang sửa sản phẩm Admin 
Route::get('/admin/Edit-product/{id}', [AdminController::class, 'editSanPham'])->name('admin.edit-product');
Route::post('/admin/update-product/{id}', [AdminController::class, 'updateSanPham'])->name('admin.update-product');
Route::get('/fix-admin', function() {
    $admin = \App\Models\User::where('username', 'admin')->first();
    if ($admin) {
        $admin->password = \Hash::make('123456');
        $admin->save();
        return "Đã cập nhật mật khẩu Admin thành 123456 bằng code Laravel!";
    }
    return "Không tìm thấy user admin để sửa.";
});



//--------------------------------------------Middleware------------------------------------------------

//Fallback lỗi url
Route::fallback(function () {
    return "<h1>URL khong ton tai</h1>";
});
//middle ware lấy url, time , ip
Route::get('/middle', function () {

})->middleware(ResultToken::class);
//tail -f storage/logs/laravel.log


Route::get('/login', [WebController::class, 'login'])->name('login');
Route::post('/login', [WebController::class, 'postLogin'])->name('postLogin');
Route::get('/signup', [WebController::class, 'signup'])->name('signup');
Route::post('/signup', [WebController::class, 'postSignup'])->name('postSignup');
Route::get('/logout', [WebController::class, 'logout'])->name('logout');


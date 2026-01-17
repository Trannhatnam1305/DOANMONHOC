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


//Route Trang Login Admin
Route::get('/admin/login', [AdminController::class, 'loginPage'])->name('admin.login');
Route::post("/admin/login", [AdminController::class, 'login']);

Route::middleware(['auth', 'admin.auth'])->prefix('admin')->group(function () {
    //Router DashBoard
    Route::get('/', [AdminController::class, 'LoadAdmin'])->name('admin.index');
    //Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Sản Phẩm
    Route::get('/sanpham', [AdminController::class, 'SanPham'])->name('admin.sanpham');
    //Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Nhà Cung Cấp
    Route::get('/nhacungcap', [AdminController::class, 'NhaCungCap'])->name('admin.nhacungcap');
    //Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Loại Sản Phẩm
    Route::get('/loaisanpham', [AdminController::class, 'LoaiSanPham'])->name('admin.loaisanpham');
    //Router SảnPhẩm Admin -> Người Dùng
    Route::get('/nguoidung', [AdminController::class, 'NguoiDung'])->name('admin.nguoidung');
    //Router AddSanPham
    Route::get('/addsanpham', [AdminController::class, 'AddProduct'])->name('admin.addProduct');
    Route::post('/addsanpham', [AdminController::class, 'ThemSanPham']);
    //Route Sản Phẩm Admin -> Xóa
    Route::delete('/sanpham/xoa/{id}', [AdminController::class, 'XoaSanPham'])->name('admin.sanpham.xoa');
    // Route để vào xem trang Thùng rác
    Route::get('/sanpham/thung-rac', [AdminController::class, 'ThungRacSanPham'])->name('admin.sanpham.thungrac');
    // Route thực hiện lệnh khôi phục
    Route::get('/sanpham/phuc-hoi/{id}', [AdminController::class, 'PhucHoiSanPham'])->name('admin.sanpham.phuc-hoi');
    //Route Trang sửa sản phẩm Admin 
    Route::get('/Edit-product/{id}', [AdminController::class, 'editSanPham'])->name('admin.edit-product');
    Route::post('/update-product/{id}', [AdminController::class, 'updateSanPham'])->name('admin.update-product');
    //Route Admin -> DS Người dùng 
    Route::get('/nguoidung', [AdminController::class, 'danhSachNguoiDung'])->name('admin.nguoidung');
    // Route Admin hiển thị form tạo mới
    Route::get('/nguoidung/create', [AdminController::class, 'createQuanTriVien'])->name('admin.user.create');
    //Route xử lý lưu dữ liệu
    Route::post('/nguoidung/store', [AdminController::class, 'storeQuanTriVien'])->name('admin.user.store');
    // Route này sẽ nhận ID người dùng và trạng thái mới (0 là khóa, 1 là mở)
    Route::get('/user/status/{id}/{status}', [AdminController::class, 'doiTrangThai'])->name('admin.user.status');
    // Route để khóa/mở tài khoản (truyền id người dùng cần xử lý vào)
    Route::post('/nguoidung/toggle/{id}', [AdminController::class, 'toggleStatus'])->name('admin.toggleUser');
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


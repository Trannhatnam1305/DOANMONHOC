<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ResultToken;
use App\Http\Middleware\IsLogin;
use App\Http\Controllers\CartController;
use App\Models\Contact;
// Route::get('/', function () {
//     return view('welcome');
// });


//--------------------------------------------Route User-----------------------------------------------

//Router Trang Chủ
Route::get('/', [WebController::class, 'index']);
//Route Trang Checkout
Route::get('/checkout', [WebController::class, 'checkout']);
//Route Trang Shop
Route::get('/shop', [WebController::class, 'shop']);
//Route Trang Single Product
Route::get('/single-product', [WebController::class, 'singleproduct']);
//Route Trang Contact
Route::get('/contact', [WebController::class, 'contact']);

// Route xem trang giỏ hàng
Route::get('/cart', [WebController::class, 'cart'])->name('cart');
// Route thêm sản phẩm vào giỏ (nhận ID sản phẩm)
Route::get('/add-to-cart/{id}', [WebController::class, 'addToCart'])->name('add_to_cart');
// Route xóa sản phẩm khỏi giỏ hàng
Route::get('/delete-cart/{id}', [WebController::class, 'deleteCart'])->name('delete_cart');
// Thêm ->name('product_detail') vào cuối route của bạn
Route::get('/san-pham/{id}', [WebController::class, 'show'])->name('product_detail');
// Route xử lý tăng giảm số lượng
Route::get('/cart', [WebController::class, 'cart'])->name('cart');
Route::get('/add-to-cart/{id}', [WebController::class, 'addToCart'])->name('add_to_cart');
Route::get('/delete-cart/{id}', [WebController::class, 'deleteCart'])->name('delete_cart');
Route::get('/update-cart-quantity/{id}/{type}', [WebController::class, 'updateQuantity'])->name('update_cart_quantity');
Route::get('/checkout', [WebController::class, 'checkout'])->name('checkout');
Route::get('/profile', [WebController::class, 'editProfile'])->name('user.profile.edit');    
Route::post('/profile/update', [WebController::class, 'updateProfile'])->name('user.profile.update');
Route::post('/contact-send', [WebController::class, 'sendContact'])->name('contact.send');
//--------------------------------------------Route Admin-----------------------------------------------

//Route Trang Chủ Admin + Kiểm Tra Login Bằng Middleware
Route::get('/admin',[AdminController::class,'LoadAdmin'])->name('admin.index')->middleware(IsLogin::class);
//Route Trang Login Admin
Route::get('/admin/login',[AdminController::class,'loginPage'])->name('admin.login');
Route::post("/admin/login",[AdminController::class,'login']);
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Sản Phẩm
// Route cho trang Thêm người dùng 
Route::get('/admin/nguoidung/create', [AdminController::class, 'createQuanTriVien'])->name('admin.user.create');
// Route để xử lý Lưu người dùng mới 
Route::post('/admin/nguoidung/store', [AdminController::class, 'storeQuanTriVien'])->name('admin.user.store');
// Route để Khóa/Mở khóa tài khoản 
Route::post('/admin/nguoidung/toggle/{id}', [AdminController::class, 'toggleStatus'])->name('admin.toggleUser');
// Route xem danh sách sản phẩm đã xóa
Route::get('/admin/sanpham/thung-rac', [AdminController::class, 'ThungRacSanPham'])->name('admin.sanpham.thungrac');
// Route phục hồi sản phẩm
Route::get('/admin/sanpham/phuc-hoi/{id}', [AdminController::class, 'PhucHoiSanPham'])->name('admin.sanpham.phuc-hoi');
//Router SảnPhẩm Admin
Route::get('/admin/sanpham', [AdminController::class, 'SanPham'])->name('admin.sanpham');
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Nhà Cung Cấp
Route::get('/nhacungcap', [AdminController::class, 'NhaCungCap'])->name('admin.nhacungcap');
// 2. Trang hiển thị Form thêm mới
Route::get('/nhacungcap/them', [AdminController::class, 'ThemNhaCungCap'])->name('admin.addNhaCungCap');
// 3. Xử lý lưu dữ liệu (Method POST)
Route::post('/nhacungcap/luu', [AdminController::class, 'LuuNhaCungCap'])->name('admin.luu-nhacungcap');
//Router SảnPhẩm Admin -> Quản Lí Sản Phẩm -> Loại Sản Phẩm
Route::get('/admin/loaisanpham', [AdminController::class, 'LoaiSanPham'])->name('admin.loaisanpham');
Route::get('/loaisanpham/them', [AdminController::class, 'ThemLoaiSanPham'])->name('admin.them-loaisanpham');
Route::post('/loaisanpham/luu', [AdminController::class, 'LuuLoaiSanPham'])->name('admin.luu-loaisanpham');
//Router SảnPhẩm Admin -> Người Dùng
Route::get('/admin/nguoidung', [AdminController::class, 'danhSachNguoiDung'])->name('admin.nguoidung');
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
Route::get('/socials', [AdminController::class, 'editSocials'])->name('admin.socials');
Route::post('/socials/update', [AdminController::class, 'updateSocials'])->name('admin.socials.update');
Route::get('/admin/contacts', [AdminController::class, 'listContacts'])->name('admin.contact');
Route::get('/contacts/read/{id}',[AdminController::class, 'markAsRead'])->name('contact.read');
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
Route::get('/signup', [WebController::class, 'signup'])->name('register'); 
Route::post('/signup', [WebController::class, 'postSignup'])->name('postSignup');
Route::post('/logout', [WebController::class, 'logout'])->name('logout');

Route::post('/addContact', [ContactController::class, 'addContact']);
Route::get('/contacts', [ContactController::class, 'index'])->name('contact.index');
Route::get('/contacts/read/{id}', [ContactController::class, 'markAsRead'])->name('contact.read');
Route::delete('/contacts/delete/{id}', [ContactController::class, 'destroy'])->name('contact.delete');


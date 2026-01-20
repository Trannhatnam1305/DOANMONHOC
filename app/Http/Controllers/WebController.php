<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function cart()
    {
        return view("user.cart");
    }

    public function index()
    {
        // 1. Lấy dữ liệu cho Slider (Ví dụ lấy 3 sản phẩm mới nhất)
        $sliders = DB::table('products')->orderBy('id', 'desc')->limit(3)->get();

        // 2. Lấy dữ liệu theo các nhóm 'loai' của bạn
        $products_seller = DB::table('products')->where('loai', 1)->limit(9)->get();
        $products_recently_view = DB::table('products')->where('loai', 2)->limit(3)->get();
        $products_top_new = DB::table('products')->where('loai', 3)->limit(3)->get();

        // 3. Trả về view kèm theo tất cả các biến dữ liệu
        return view("user.index", [
            'sliders' => $sliders,
            'products_seller' => $products_seller,
            'products_recently_view' => $products_recently_view,
            'products_top_new' => $products_top_new
        ]);
    }
    public function shop()
    {
        $product = DB::table('products')->get();
        return view("user.shop", ['products' => $product]);
    }

    public function contact()
    {
        return view("user.contact");
    }

    public function signup()
    {
        return view('user.signup');
    }
    public function login()
    {
        return view('user.login');
    }
    public function postLogin(Request $request)
    {
        // 1. Lấy dữ liệu từ form
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // 2. Kiểm tra đăng nhập bằng Auth::attempt
        // Hàm này tự động so khớp username và mã hóa password để kiểm tra
        if (Auth::attempt($credentials)) {

            // --- ĐOẠN KIỂM TRA KHÓA TÀI KHOẢN ---
            // Lấy thông tin user vừa đăng nhập thành công
            $user = Auth::user();

            if ($user->status == 0) {
                // Nếu status = 0 (Bị khóa) -> Đăng xuất ngay lập tức
                Auth::logout();

                // Xóa session để an toàn
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Trả về trang đăng nhập với thông báo lỗi
                return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Admin!');
            }
            // -------------------------------------

            // Nếu tài khoản hoạt động bình thường (status = 1)
            // Chuyển hướng về trang chủ hoặc trang shop
            return redirect('/'); // Hoặc route('/') tùy project của bạn
        }

        // 3. Nếu đăng nhập thất bại (Sai username hoặc password)
        return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
    }
    public function logout(Request $request)
    {
        \Auth::logout();
        return redirect('/')->with('status', 'Đã đăng xuất!');
    }
    public function postSignup(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'username' => 'required|unique:users,username|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed', // 'confirmed' yêu cầu có ô password_confirmation
        ], [
            'username.unique' => 'Tên đăng nhập này đã có người sử dụng.',
            'email.unique' => 'Email này đã được đăng ký.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        // Tạo user mới
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu
            'role' => 0, // Mặc định là người dùng bình thường
        ]);

        // Sau khi đăng ký xong, tự động đăng nhập luôn
        Auth::login($user);

        return redirect('/')->with('status', 'Đăng ký tài khoản thành công!');
    }

}
;


<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function cart(){
        return view("user.cart");
    }

    public function index(){
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
    public function shop(){
        $product=DB::table('products')->get();
        return view("user.shop",['products'=>$product]);
    }

    public function contact(){
        return view("user.contact");
    }
  
    public function signup(){
        return view('user.signup');
    }
    public function login() {
        return view('user.login'); 
    }
    public function postLogin(Request $request) {
                $u = trim($request->username);
                $p = $request->password;

                $userInDb = \App\Models\User::where('username', $u)->first();

                // Vì chúng ta đã biết mật khẩu khớp, giờ chỉ cần viết logic chuẩn của Laravel
                if ($userInDb && \Hash::check($p, $userInDb->password)) {
                    
                    // Bước quan trọng nhất: Lưu trạng thái đăng nhập vào Session
                    \Auth::login($userInDb); 

                    // Kiểm tra quyền: Nếu role = 1 là Admin
                    if ($userInDb->role == 1) {
                        return redirect('/admin')->with('status', 'Chào mừng Admin quay trở lại!');
                    }

                    // Nếu là người dùng thường (role = 0)
                    return redirect('/')->with('status', 'Đăng nhập thành công!');
                }

                // Nếu quay lại đây nghĩa là sai thông tin
                return back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác!');
            }
    public function logout(Request $request) {
        \Auth::logout();
        return redirect('/')->with('status', 'Đã đăng xuất!');
    }
    public function postSignup(Request $request) {
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

};


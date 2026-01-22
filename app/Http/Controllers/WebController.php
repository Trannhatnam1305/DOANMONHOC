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
            // 1. Lấy dữ liệu giỏ hàng từ session
            $cart = session()->get('cart', []);

            // 2. Sidebar Products - Chỉ lấy những sản phẩm đang được BẬT (status = 1)
            $products_sidebar = DB::table('products')
                ->where('status', 1) // Thêm điều kiện này
                ->inRandomOrder()
                ->limit(8)
                ->get();

            // 3. You may be interested in - Chỉ lấy sản phẩm đang BẬT
            $products_interested = DB::table('products')
                ->where('status', 1) // Thêm điều kiện này
                ->inRandomOrder()
                ->limit(4)
                ->get();

            // 4. Recent Posts - Lấy 5 sản phẩm mới nhất và đang BẬT
            $recent_posts = DB::table('products')
                ->where('status', 1) // Thêm điều kiện này
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            // 5. Trả về view với tất cả các biến đã lọc
            return view('user.cart', compact('cart', 'products_sidebar', 'products_interested', 'recent_posts'));
        }

    public function addToCart($id)
    {
        // 1. Tìm sản phẩm trong database theo ID
        $product = DB::table('products')->find($id);

        // Nếu không tìm thấy sản phẩm thì báo lỗi 404
        if (!$product) {
            abort(404);
        }

        // 2. Lấy giỏ hàng hiện tại từ session (nếu chưa có thì là mảng rỗng)
        $cart = session()->get('cart', []);

        // 3. Kiểm tra: Nếu sản phẩm đã có trong giỏ rồi thì tăng số lượng lên
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // 4. Nếu chưa có thì thêm mới vào mảng cart
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->discount_price > 0 ? $product->discount_price : $product->price, // Ưu tiên lấy giá giảm
                "image" => $product->image
            ];
        }

        // 5. Lưu lại giỏ hàng mới vào Session
        session()->put('cart', $cart);

        // 6. Chuyển hướng người dùng đến trang Giỏ hàng để xem kết quả
        return redirect()->route('cart')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function index()
    {
        // 1. Lấy dữ liệu cho Slider (Ví dụ lấy 3 sản phẩm mới nhất)
        $sliders = DB::table('products')->orderBy('id', 'desc')->limit(3)->get();
        $socials = DB::table('settings')->pluck('value', 'key');
        // 2. Lấy dữ liệu theo các nhóm 'loai' của bạn
        $products_seller = DB::table('products')->where('loai', 1)->limit(9)->get();
        $products_recently_view = DB::table('products')->where('loai', 2)->limit(3)->get();
        $products_top_new = DB::table('products')->where('loai', 3)->limit(3)->get();

        // 3. Trả về view kèm theo tất cả các biến dữ liệu
        return view("user.index", [
            'sliders' => $sliders,
            'products_seller' => $products_seller,
            'products_recently_view' => $products_recently_view,
            'products_top_new' => $products_top_new,
            'socials' => $socials, 
        ]);
    }
    public function shop()
    {
        
        $products = DB::table('products')
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->paginate(12); 

        return view("user.shop", compact('products'));
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
    public function deleteCart($id)
    {
        $cart = session()->get('cart');

        // Kiểm tra xem sản phẩm có trong giỏ không thì mới xóa
        if (isset($cart[$id])) {
            unset($cart[$id]); // Hàm unset dùng để xóa phần tử khỏi mảng
            session()->put('cart', $cart); // Lưu lại giỏ hàng mới
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm thành công!');
    }
    public function updateQuantity($id, $type)
    {
        // 1. Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart');

        // 2. Kiểm tra xem sản phẩm có tồn tại trong giỏ không
        if(isset($cart[$id])) {
            if($type == 'plus') {
                // Tăng số lượng
                $cart[$id]['quantity']++;
            } elseif($type == 'minus') {
                // Giảm số lượng nhưng không được nhỏ hơn 1
                if($cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity']--;
                }
            }
            
            // 3. Lưu lại giỏ hàng mới vào session
            session()->put('cart', $cart);
        }

        // 4. Quay lại trang giỏ hàng với thông báo
        return redirect()->back()->with('success', 'Cập nhật số lượng thành công!');
    }
    public function checkout()
    {
        return view('user.checkout'); // Tạo file checkout.blade.php trống để hết lỗi
    }

}
;


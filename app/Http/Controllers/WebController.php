<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function cart()
        {
            // 1. Lấy dữ liệu giỏ hàng từ DATABASE thay vì Session
            // Chúng ta JOIN với bảng products để lấy tên, ảnh và giá hiện tại
            $cart = [];
            if (Auth::check()) {
                $cart = DB::table('carts')
                    ->join('products', 'carts.product_id', '=', 'products.id')
                    ->where('carts.user_id', Auth::id())
                    ->select('carts.*', 'products.name', 'products.image', 'products.price as current_product_price')
                    ->get();
            }

            // 2. Sidebar Products - Giữ nguyên logic của bạn
            $products_sidebar = DB::table('products')
                ->where('status', 1)
                ->inRandomOrder()
                ->limit(8)
                ->get();

            // 3. You may be interested in - Giữ nguyên logic của bạn
            $products_interested = DB::table('products')
                ->where('status', 1)
                ->inRandomOrder()
                ->limit(4)
                ->get();

            // 4. Recent Posts - Giữ nguyên logic của bạn
            $recent_posts = DB::table('products')
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();

            // 5. Trả về view
            return view('user.cart', compact('cart', 'products_sidebar', 'products_interested', 'recent_posts'));
        }

    public function addToCart(Request $request, $id)
        {
            // 1. Phải đăng nhập mới lưu vào DB được
            if (!Auth::check()) {
                return redirect()->route('login')->with('info', 'Vui lòng đăng nhập để lưu giỏ hàng!');
            }

        $products_sidebar = DB::table('products')->where('status', 1)->inRandomOrder()->limit(8)->get();
        $products_interested = DB::table('products')->where('status', 1)->inRandomOrder()->limit(4)->get();
        $recent_posts = DB::table('products')->where('status', 1)->orderBy('id', 'desc')->limit(5)->get();

        return view('user.cart', compact('cart', 'totalAll', 'products_sidebar', 'products_interested', 'recent_posts'));
    }

    public function addToCart(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Vui lòng đăng nhập để lưu giỏ hàng!');
        }

        $userId = Auth::id();
        $quantity = $request->input('quantity', 1);
        
        $product = DB::table('products')->where('id', $id)->first();
        if (!$product) return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');

        $currentPrice = (isset($product->discount_price) && $product->discount_price > 0) ? $product->discount_price : $product->price;

        $cartItem = DB::table('carts')
            ->where('user_id', $userId)
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            DB::table('carts')->where('id', $cartItem->id)->increment('quantity', $quantity);
        } else {
            DB::table('carts')->insert([
                'user_id'    => $userId,
                'product_id' => $id,
                'quantity'   => $quantity,
                'price'      => $currentPrice,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('cart')->with('success', 'Đã thêm vào giỏ hàng!');
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
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa!');
            }
            return redirect('/');
        }
    public function logout(Request $request) 
        {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
         }
    public function postSignup(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);

        Auth::login($user);
        return redirect('/')->with('status', 'Đăng ký thành công!');
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
            $cart = session()->get('cart');

            if(isset($cart[$id])) {
                if($type == 'plus') {
                    $cart[$id]['quantity']++;
                } elseif($type == 'minus' && $cart[$id]['quantity'] > 1) {
                    $cart[$id]['quantity']--;
                }
                session()->put('cart', $cart);
            }

            // Tính toán lại tổng tiền để gửi về cho giao diện
            $total = 0;
            foreach($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'status'   => 'success',
                'quantity' => $cart[$id]['quantity'],
                'subtotal' => number_format($cart[$id]['price'] * $cart[$id]['quantity']) . 'đ',
                'total'    => number_format($total) . 'đ'
            ]);
        }
    public function checkout()
        {
            return view('user.checkout'); // Tạo file checkout.blade.php trống để hết lỗi
        }

    public function editProfile()
        {
            $user = Auth::user();
            // Phải có chữ return ở đây!
            return view('user.profile', compact('user')); 
        }
    public function updateProfile(Request $request)
        {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. Kiểm tra dữ liệu đầu vào
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $user->id,
                'phone'    => 'nullable|string|max:15',
                'birthday' => 'nullable|date',
                'gender'   => 'nullable|in:0,1,2',
                'address'  => 'nullable|string|max:500',
                'password' => 'nullable|string|min:8',
            ], [
                'name.required' => 'Họ tên không được để trống.',
                'email.unique'  => 'Email này đã tồn tại trong hệ thống.',
                'password.min'  => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            ]);
            // 2. Cập nhật các trường dữ liệu
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->phone    = $request->phone;
            $user->birthday = $request->birthday;
            $user->gender   = $request->gender;
            $user->address  = $request->address;
            // 3. Kiểm tra và đổi mật khẩu nếu có nhập
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            // 4. Lưu vào Database
            $user->save();
            // 5. Quay lại trang cũ kèm thông báo thành công
            return redirect()->back()->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
        }

    public function sendContact(Request $request) 
        {
            // 1. Kiểm tra dữ liệu
            $request->validate([
                'name'    => 'required',
                'email'   => 'required|email',
                'phone'   => 'required',
                'title'   => 'required',
                'content' => 'required',
                ]);

                // 2. Thực hiện lưu vào DB
                \App\Models\Contact::create($request->all());

                // 3. ĐÂY LÀ DÒNG GÂY LỖI NẾU VIẾT SAI:
                // SAI: return view('viewContact');  <-- Laravel sẽ đi tìm file viewContact.blade.php
                // ĐÚNG: Quay về trang chủ
                return redirect('/')->with('success', 'Gửi liên hệ thành công!');
         }

    public function show($id)
        {
            // Lấy thông tin sản phẩm và danh mục (Mục 10)
            $product = \App\Models\Product::with('category')->findOrFail($id);

            // Tăng lượt xem (Mục 17)
            $product->increment('views');

            // Lấy sản phẩm liên quan (Mục 13)
            $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit(4)
                ->get();

            return view('user.single-product', compact('product', 'relatedProducts'));
        }

    public function getStockQuantity($id) 
        {
            $stock = DB::table('products')->where('id', $id)->value('stock_quantity');
            return response()->json(['stock' => $stock ?? 0]);
        }
                    

       
}
;


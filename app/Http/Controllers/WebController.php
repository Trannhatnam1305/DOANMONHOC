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
        $cart = [];
        $totalAll = 0;

        if (Auth::check()) {
            $cartQuery = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.user_id', Auth::id());

            $totalAll = (clone $cartQuery)->sum(DB::raw('carts.quantity * products.price'));

            $cart = $cartQuery->select(
                'carts.*', 
                'products.name', 
                'products.image', 
                'products.price as current_product_price',
                'products.stock_quantity'
            )->paginate(3);
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
        $sliders = DB::table('products')->orderBy('id', 'desc')->limit(3)->get();
        $socials = DB::table('settings')->pluck('value', 'key');
        $products_seller = DB::table('products')->where('loai', 1)->limit(9)->get();
        $products_recently_view = DB::table('products')->where('loai', 2)->limit(3)->get();
        $products_top_new = DB::table('products')->where('loai', 3)->limit(3)->get();

        return view("user.index", compact('sliders', 'products_seller', 'products_recently_view', 'products_top_new', 'socials'));
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

        return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng!');
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
        // Nếu dùng Database cho giỏ hàng:
        if (Auth::check()) {
            DB::table('carts')->where('id', $id)->where('user_id', Auth::id())->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
        }
        
        // Nếu dùng Session:
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Đã xóa sản phẩm!');
    }

    // Đã đổi tên hàm từ 'show' thành 'singleproduct' cho khớp với Route bạn đã có
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $product->increment('views');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('user.single-product', compact('product', 'relatedProducts'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'title'   => 'required',
            'content' => 'required',
        ]);

        Contact::create($request->all());
        return redirect('/')->with('success', 'Gửi liên hệ thành công!');
    }

    public function getStockQuantity($id) 
    {
        $stock = DB::table('products')->where('id', $id)->value('stock_quantity');
        return response()->json(['stock' => $stock ?? 0]);
    }
}
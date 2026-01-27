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
            // 1. Khởi tạo biến mặc định
            $cart = [];
            $totalAll = 0;

            if (Auth::check()) {
                // Query kết nối bảng carts và products để lấy dữ liệu realtime
                $cartQuery = DB::table('carts')
                    ->join('products', 'carts.product_id', '=', 'products.id')
                    ->where('carts.user_id', Auth::id());

                // Tính tổng tiền cho TOÀN BỘ giỏ hàng (Không bị ảnh hưởng bởi phân trang)
                $totalAll = (clone $cartQuery)->sum(DB::raw('carts.quantity * products.price'));

                // Lấy dữ liệu và thực hiện phân trang (5 món/trang)
                $cart = $cartQuery->select(
                    'carts.*', 
                    'products.name', 
                    'products.image', 
                    'products.price as current_product_price', // Đặt alias để View gọi được
                    'products.stock_quantity' // Kiểm tra tồn kho realtime
                )->paginate(3);
            }

            // 2. Các biến Sidebar giữ nguyên logic
            $products_sidebar = DB::table('products')->where('status', 1)->inRandomOrder()->limit(8)->get();
            $products_interested = DB::table('products')->where('status', 1)->inRandomOrder()->limit(4)->get();
            $recent_posts = DB::table('products')->where('status', 1)->orderBy('id', 'desc')->limit(5)->get();

            return view('user.cart', compact('cart', 'totalAll', 'products_sidebar', 'products_interested', 'recent_posts'));
        }
    public function addToCart(Request $request, $id)
        {
            // 1. Phải đăng nhập mới lưu vào DB được
            if (!Auth::check()) {
                return redirect()->route('login')->with('info', 'Vui lòng đăng nhập để lưu giỏ hàng!');
            }

            $userId = Auth::id();
            $quantity = $request->input('quantity', 1);
            
            // 2. Lấy giá sản phẩm (giá gốc hoặc giá giảm)
            $product = DB::table('products')->where('id', $id)->first();
            $currentPrice = $product->discount_price > 0 ? $product->discount_price : $product->price;

            // 3. Kiểm tra sản phẩm đã tồn tại trong giỏ của user này chưa
            $cartItem = DB::table('carts')
                ->where('user_id', $userId)
                ->where('product_id', $id)
                ->first();

            if ($cartItem) {
                // Nếu có rồi thì UPDATE số lượng
                DB::table('carts')->where('id', $cartItem->id)->increment('quantity', $quantity);
            } else {
                // Nếu chưa có thì INSERT mới
                DB::table('carts')->insert([
                    'user_id'    => $userId,
                    'product_id' => $id,
                    'quantity'   => $quantity,
                    'price'      => $currentPrice,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return redirect()->route('cart')->with('success', 'Đã cập nhật giỏ hàng hệ thống!');
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
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
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
            // Tìm sản phẩm trong bảng carts theo ID của dòng đó
            $cartItem = DB::table('carts')->where('id', $id)->first();

            if ($cartItem) {
                $newQty = $cartItem->quantity;

                if ($type == 'plus') {
                    $newQty++;
                } elseif ($type == 'minus' && $cartItem->quantity > 1) {
                    $newQty--;
                }

                // Cập nhật lại số lượng vào Database
                DB::table('carts')->where('id', $id)->update(['quantity' => $newQty]);

                // Tính toán lại tổng tiền của cả giỏ hàng (cho user hiện tại)
                $cartItems = DB::table('carts')
                    ->where('user_id', Auth::id())
                    ->get();

                $total = $cartItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });

                return response()->json([
                    'status'   => 'success',
                    'quantity' => $newQty,
                    'subtotal' => number_format($cartItem->price * $newQty, 0, ',', '.') . ' VNĐ',
                    'total'    => number_format($total, 0, ',', '.') . ' VNĐ'
                ]);
            }

            return response()->json(['status' => 'error'], 404);
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


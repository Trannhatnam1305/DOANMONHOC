<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Nhớ thêm dòng này ở trên cùng file
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Contact;

class AdminController extends Controller
{

    //
    public function LoadAdmin()
    {
        // Tổng người dùng
        $totalUsers = User::count();

        // Tổng số đơn hàng (đếm số order_id khác nhau trong order_items)
        $totalOrders = DB::table('order_items')
            ->select('order_id')
            ->distinct()
            ->count();

        // Tổng số sản phẩm
        $totalProducts = Product::count();

        // Tổng doanh thu (tổng price * quantity)
        $totalRevenue = OrderItem::select(DB::raw('SUM(price * quantity) as total'))->value('total');

        $stats = [
            'users' => $totalUsers,
            'orders' => $totalOrders,
            'products' => $totalProducts,
            'revenue' => $totalRevenue,
        ];

        return view('admin.HomeAdmin', compact('stats'));
    }

    public function loginPage()
    {
        return view('admin.loginAdmin');
    }

    public function login(Request $request)
        {
            // 1. Validate
            $credentials = $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            // 2. Thử đăng nhập
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Kiểm tra quyền và trạng thái cùng lúc
                if ($user->status == 0 || $user->role < 1) {
                    $message = ($user->status == 0) 
                                ? 'Tài khoản quản trị của bạn đã bị khóa!' 
                                : 'Tài khoản của bạn không có quyền quản trị!';

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('admin.login')->with('error', $message);
                }

                $request->session()->regenerate();
                return redirect()->route('admin.index')->with('status', 'Chào mừng Admin quay trở lại!');
            }

            // 3. Sai thông tin
            return back()->withErrors([
                'username' => 'Thông tin đăng nhập không chính xác.',
            ])->withInput($request->only('username')); // Giữ lại tên đăng nhập để user không phải nhập lại
        }
    public function SanPham(Request $request)
        {
            $query = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->whereNull('products.deleted_at')
                ->select(
                    'products.*', 
                    'categories.name as category_name', 
                    'brands.name as brand_name'
                );

            // Tìm kiếm theo tên sản phẩm
            if ($request->keyword) {
                $query->where('products.name', 'like', '%' . $request->keyword . '%');
            }

            $sanpham = $query->orderBy('products.id', 'desc')->paginate(10);

            return view('admin.SanPhamAdmin', compact('sanpham'));
        }
        
    public function LoaiSanPham()
        {
            $dsLoaisanpham = DB::table('categories')->get();

            return view('admin.LoaiSanPhamAdmin', ['dsLoaisanpham' => $dsLoaisanpham]);
        }

    public function danhSachNguoiDung()
        {
            // Lấy toàn bộ người dùng từ bảng 'users' (hoặc tên bảng tương ứng của bạn)
            $dsNguoiDung = DB::table('users')->paginate(10);

            return view('admin.NguoiDungAdmin', compact('dsNguoiDung'));
        }

    public function AddProduct()
        {
            $categories = DB::table('categories')->select('id', 'name')->get();
            $suppliers = DB::table('suppliers')->select('id', 'name')->get();
            $brands = DB::table('brands')->select('id', 'name')->get();

            // compact sẽ tự hiểu là tạo mảng với key giống tên biến
            return view('admin.addProduct', compact('categories', 'suppliers', 'brands'));
        }

   public function ThemSanPham(Request $request)
        {
            // 1. Ràng buộc dữ liệu
            $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer|min:0', // Mục 11: Bắt buộc nhập số lượng kho
                'description' => 'required',
                'category_id' => 'required|integer',
                'brand_id' => 'required|integer',
                'supplier_id' => 'required|integer',
                'loai' => 'required',
                'tags' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'required' => ':attribute không được để trống.',
                'numeric' => ':attribute phải là con số.',
                'integer' => ':attribute phải là số nguyên.',
                'image' => 'Vui lòng chọn file hình ảnh hợp lệ.',
            ]);

            // 2. Xử lý lưu ảnh
            $dbPath = 'products/no-image.png';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/products'), $fileName);
                $dbPath = 'products/' . $fileName; 
            }

            // 3. Insert dữ liệu
            DB::table('products')->insert([
                'name' => $request->name,
                'price' => $request->price,
                'discount_price' => $request->discount_price ?? 0,
                'stock_quantity' => $request->stock_quantity, // Lưu số lượng kho (Mục 11)
                'description' => $request->description,
                'image' => $dbPath,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'supplier_id' => $request->supplier_id, 
                'loai' => $request->loai,
                'tags' => $request->tags,
                'status' => $request->status ?? 1,
                'views' => 0, // Mục 17: Khởi tạo lượt xem mặc định là 0
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.sanpham')->with('status', 'Thêm sản phẩm thành công!');
        }

    // 3. Trang Phục hồi (Thùng rác)
    public function ThungRacSanPham()
        {
            $dsDaXoa = DB::table('products')
                ->whereNotNull('deleted_at') // Lấy những sản phẩm đã bị xóa
                ->get();
            return view('admin.ThungRacAdmin', compact('dsDaXoa'));
        }

    // 4. Hàm Phục hồi sản phẩm
    public function PhucHoiSanPham($id)
        {
            DB::table('products')->where('id', $id)->update([
                'deleted_at' => null // Trả về trạng thái NULL để hiển thị lại
            ]);

            return redirect()->route('admin.sanpham.thungrac')->with('status', 'Phục hồi thành công');
        }

    //Controller xóa sản phẩm
    public function XoaSanPham($id)
        {
            DB::table('products')->where('id', $id)->update([
                'deleted_at' => now() // Cập nhật ngày xóa thay vì xóa vĩnh viễn
            ]);

            return redirect()->route('admin.sanpham')->with('status', 'Đã chuyển vào thùng rác');
        }
    public function editSanPham($id)
        {
            // 1. Lấy thông tin sản phẩm (Bảng này đã có trong DB)
            $sanpham = DB::table('products')->where('id', $id)->first();

            // Kiểm tra nếu không tìm thấy sản phẩm
            if (!$sanpham) {
                return redirect()->route('admin.sanpham')->with('error', 'Sản phẩm không tồn tại!');
            }

            // 2. Xử lý các bảng chưa có trong Database
            // Thay vì gọi DB::table()->get() gây lỗi, ta gán mảng rỗng để View không bị lỗi biến
            $dsLoai = [];
            $dsThuongHieu = [];
            $dsNhaCungCap = [];

            /* Ghi chú: Khi nào bạn tạo bảng trong DB thì mới dùng lại các dòng này:
            $dsLoai = DB::table('categories')->get();
            $dsThuongHieu = DB::table('brands')->get();
            $dsNhaCungCap = DB::table('suppliers')->get();
            */

            return view('admin.EditSanPhamAdmin', compact('sanpham', 'dsLoai', 'dsThuongHieu', 'dsNhaCungCap'));
        }

    public function updateSanPham(Request $request, $id)
        {
            // 1. Thiết lập ràng buộc (Validation)
            $request->validate([
                'name' => 'required|max:256', // Không được bỏ trống
                'price' => 'required|numeric', // Không được bỏ trống và phải là số
                'category_id' => 'required',        // Không được bỏ trống
                'brand_id' => 'required',        // Không được bỏ trống
                'loai' => 'required',        // Không được bỏ trống
                'tags' => 'required',        // Không được bỏ trống
                'description' => 'required',        // Không được bỏ trống
                // 'discount_price' không có 'required' nên có thể để trống
            ], [
                // Thông báo lỗi tiếng Việt tùy chỉnh
                'required' => ':attribute không được để trống!',
                'numeric' => ':attribute phải là con số!',
            ]);

            // 2. Chuẩn bị dữ liệu cập nhật
            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'discount_price' => $request->discount_price ?? 0, // Nếu trống thì để là 0
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'loai' => $request->loai,
                'tags' => $request->tags,
                'status' => $request->status,
                'description' => $request->description,
                'updated_at' => now(),
            ];

            // 3. Xử lý ảnh (nếu có chọn ảnh mới)
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $fileName);
                $data['image'] = $fileName;
            }

            // 4. Thực hiện cập nhật vào database
            DB::table('products')->where('id', $id)->update($data);

            return redirect()->route('admin.sanpham')->with('status', 'Cập nhật sản phẩm thành công!');
        }
    public function createQuanTriVien()
        {
            // Sửa lại đường dẫn view theo yêu cầu của bạn: admin/create.blade.php
            // Thêm check Auth để tránh lỗi "property on null"
            if (Auth::check() && Auth::user()->role == 2) {
                return view('admin.create');
            }
            return redirect()->route('admin.user.index')->with('error', 'Bạn không có quyền thực hiện chức năng này!');
        }

    public function storeQuanTriVien(Request $request)
        {
            // 1. Kiểm tra dữ liệu (Validation)
            $request->validate([
                'username' => 'required|unique:users,username|min:4',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone' => 'nullable|numeric|digits_between:10,11'
            ], [
                'username.unique' => 'Tên đăng nhập này đã tồn tại!',
                'email.unique' => 'Email này đã được sử dụng!',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'phone.numeric' => 'Số điện thoại chỉ được chứa chữ số.'
            ]);

            try {
                $user = new \App\Models\User();
                $user->username = $request->username;
                $user->email = $request->email;
                $user->phone = $request->phone; // Lưu số điện thoại riêng biệt
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                $user->role = 1; // Mặc định là Quản trị viên
                $user->save();

                // Sửa dòng 282 trong AdminController.php
                return redirect()->route('admin.nguoidung')->with('success', 'Đã tạo thành công Quản trị viên!');

            } catch (\Exception $e) {
                // Nếu MySQL bị tắt trong XAMPP, nó sẽ nhảy vào đây
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi lưu dữ liệu. Vui lòng kiểm tra lại kết nối Database!');
            }
        }
    public function doiTrangThai($id, $status)
        {
            $user = \App\Models\User::find($id);

            if ($user) {
                // Bảo vệ: Role 1 không được phép khóa Role 2 hoặc Role 1 khác qua URL
                if (Auth::user()->role == 1 && $user->role >= 1) {
                    return redirect()->back()->with('error', 'Bạn không có quyền khóa tài khoản quản trị khác!');
                }

                $user->status = $status;
                $user->save();

                $message = ($status == 0) ? 'Đã khóa tài khoản thành công!' : 'Đã mở khóa tài khoản!';
                return redirect()->back()->with('success', $message);
            }

            return redirect()->back()->with('error', 'Không tìm thấy người dùng!');
        }
    public function toggleStatus($id) // Tên function có thể khác tùy code bạn đặt
        {
            $user = User::find($id); // Tìm tài khoản cần khóa
            $currentUser = Auth::user(); // Lấy thông tin Admin đang đăng nhập

            // 1. Chặn không cho tự khóa chính mình
            if ($user->id == $currentUser->id) {
                return redirect()->back()->with('error', 'Bạn không thể tự khóa chính mình!');
            }

            // 2. Xử lý logic phân quyền Admin
            // Nếu tài khoản bị tác động là một ADMIN (role = 1 hoặc quản trị viên)
            if ($user->role == 1) {

                // Kiểm tra: Người thực hiện có phải là "Trùm cuối" (ID = 1) không?
                if ($currentUser->id != 1) {
                    // Nếu không phải ID 1, thì báo lỗi không đủ quyền
                    return redirect()->back()->with('error', 'Chỉ Admin cấp cao mới được quyền khóa quản trị viên khác!');
                }

                // Nếu là ID 1 thì cho phép đi tiếp xuống dưới để khóa
            }

            // --- ĐOẠN XỬ LÝ ĐỔI TRẠNG THÁI (Giữ nguyên code cũ của bạn ở dưới) ---
            $user->status = !$user->status; // Đảo ngược trạng thái 0 <-> 1
            $user->save();

            $action = $user->status ? 'Mở khóa' : 'Khóa';
            return redirect()->back()->with('status', "Đã $action tài khoản {$user->username} thành công!");
        }
    // Thêm hàm này vào nếu chưa có
    public function NhaCungCap()
        {
        $dsNhacungcap = DB::table('suppliers')->get();
            return view('admin.NhaCungCapAdmin', compact('dsNhacungcap'));
        }
    public function ThemNhaCungCap() {
    return view('admin.addNhaCungCap'); // Tên file blade của bạn
}
    public function LuuNhaCungCap(Request $request)
            {
                // 1. Kiểm tra dữ liệu (Validate) bằng tiếng Việt
                $request->validate([
                    'name'           => 'required|max:255',
                    'phone'          => 'required|numeric',
                    'email'          => 'required|email|unique:suppliers,email',
                    'contact_person' => 'nullable|max:255',
                ], [
                    'name.required'  => 'Vui lòng nhập tên công ty.',
                    'phone.required' => 'Vui lòng nhập số điện thoại.',
                    'phone.numeric'  => 'Số điện thoại phải là định dạng số.',
                    'email.required' => 'Vui lòng nhập địa chỉ email.',
                    'email.email'    => 'Email không đúng định dạng.',
                    'email.unique'   => 'Email nhà cung cấp này đã tồn tại trong hệ thống!',
                ]);

                // 2. Lưu vào Database
                DB::table('suppliers')->insert([
                    'name'           => $request->name,
                    'contact_person' => $request->contact_person,
                    'phone'          => $request->phone,
                    'email'          => $request->email,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // 3. Quay lại trang danh sách và thông báo
                return redirect()->route('admin.nhacungcap')->with('success', 'Đã thêm nhà cung cấp thành công!');
            }

        public function ThemLoaiSanPham() {
    return view('admin.addLoaiSanPham');
}

    public function LuuLoaiSanPham(Request $request) {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
            'status' => 'required|integer'
        ], [
            'name.required' => 'Bạn chưa nhập tên loại sản phẩm.',
            'name.unique' => 'Tên loại sản phẩm này đã tồn tại.',
        ]);

        // Lưu vào bảng categories (hoặc bảng loai_san_pham tùy bạn đặt tên)
        DB::table('categories')->insert([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Tự động tạo slug: "Laptop Gaming" -> "laptop-gaming"
            'status' => $request->status, // 1: Hiển thị, 0: Ẩn
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.loaisanpham')->with('success', 'Thêm loại sản phẩm thành công!');
    }

    public function editSocials()
        {
            $socials = DB::table('settings')->pluck('value', 'key');
            return view('admin.socials', compact('socials'));
        }

    public function updateSocials(Request $request)
        {
            $platforms = ['facebook', 'twitter', 'youtube', 'linkedin', 'pinterest'];
            foreach ($platforms as $platform) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $platform],
                    ['value' => $request->input($platform)]
                );
            }
            return redirect()->back()->with('success', 'Đã cập nhật link thành công!');
        }
    public function listContacts()
        {
            $contacts = \App\Models\Contact::orderBy('created_at', 'desc')->paginate(10);
            
            // Thử dùng cách truyền mảng này cho chắc chắn
            return view('admin.contact', [
                'contacts' => $contacts
            ]);
        }

    public function markAsRead($id)
        {
            // Tìm tin nhắn theo ID
            $contact = \App\Models\Contact::findOrFail($id);
            
            // Cập nhật trạng thái thành đã xem
            $contact->update(['status' => 1]);
            
            // Quay lại trang danh sách kèm thông báo
            return back()->with('success', 'Đã đánh dấu tin nhắn là đã đọc.');
        }
}








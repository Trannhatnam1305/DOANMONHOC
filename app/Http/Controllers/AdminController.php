<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;


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
        // 1. Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // 2. Thử đăng nhập
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Lấy thông tin user vừa đăng nhập
            $user = Auth::user();

            // --- MỚI THÊM: KIỂM TRA TRẠNG THÁI (STATUS) ---
            // Nếu status = 0 (Bị khóa) thì đăng xuất ngay
            if ($user->status == 0) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with('error', 'Tài khoản quản trị của bạn đã bị khóa!');
            }
            // ----------------------------------------------

            // --- KIỂM TRA QUYỀN (ROLE) ---
            // Nếu KHÔNG PHẢI ADMIN (role < 1)
            if ($user->role < 1) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with('error', 'Tài khoản của bạn không có quyền quản trị!');
            }
            // -----------------------------

            // Nếu mọi thứ OK (Admin và Không bị khóa) -> Vào Dashboard
            return redirect()->route('admin.index')->with('status', 'Đăng nhập thành công!');
        }

        // 3. Nếu sai username hoặc password
        return back()->withErrors([
            'username' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function SanPham(Request $request)
    {
        $query = DB::table('products')->whereNull('deleted_at'); //

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $sanpham = $query->paginate(10);
        // Lưu ý: Đặt tên biến là $sanpham để khớp với file Blade của bạn

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
        #$category = DB::table('categories')->select('id', 'name')->get();
        #$suppliers = DB::table('suppliers')->select('id', 'name')->get();
        #$brands = DB::table('brands')->select('id', 'name')->get();
        #return view('admin.addProduct', ['categories' => $category, 'suppliers' => $suppliers, 'brands' => $brands]);
        return view('admin.addProduct');
    }

    public function ThemSanPham(Request $request)
    {
        // 1. Ràng buộc không được để trống (ngoại trừ discount_price)
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'loai' => 'required|integer',
            'tags' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Bắt buộc chọn ảnh khi thêm mới
        ], [
            'required' => ':attribute không được để trống.',
            'numeric' => ':attribute phải là con số.',
            'image' => 'Vui lòng chọn file hình ảnh hợp lệ.',
        ]);

        // 2. Xử lý lưu ảnh
        $fileName = 'no-image.png';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
        }

        // 3. Insert dữ liệu
        DB::table('products')->insert([
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price ?? 0,
            'description' => $request->description,
            'image' => $fileName,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'loai' => $request->loai,
            'tags' => $request->tags,
            'status' => $request->status ?? 1,
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
}








<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    //
    public function LoadAdmin()
    {
        return view('admin.HomeAdmin');
    }

    public function loginPage()
    {
        return view('admin.loginAdmin');
    }

    public function login(AdminLoginRequest $request)
    {

        $user = DB::table('users')->where('username', $request->username)->where('password', $request->password)->first();
        if (!$user)
            return redirect()->route('admin.login')->with('status', "Username hoặc password không đúng");
        session()->put('login', true);
        session()->put('user_role', $user->role);
        return redirect()->route('admin.index');
    }

    public function SanPham()
    {
        $dssanpham = DB::table('products')->get();

        return view('admin.SanPhamAdmin', ['dssanpham' => $dssanpham]);
    }

    public function NhaCungCap()
    {
        $dsNhacungcap = DB::table('suppliers')->get();

        return view('admin.NhaCungCapAdmin', ['dsNhacungcap' => $dsNhacungcap]);
    }

    public function LoaiSanPham()
    {
        $dsLoaisanpham = DB::table('categories')->get();

        return view('admin.LoaiSanPhamAdmin', ['dsLoaisanpham' => $dsLoaisanpham]);
    }

    public function NguoiDung()
    {
        $dsNguoiDung = DB::table('users')->get();

        return view('admin.NguoiDungAdmin', ['dsNguoiDung' => $dsNguoiDung]);
    }

    public function AddProduct()
    {
        $category = DB::table('categories')->select('id', 'name')->get();
        $suppliers = DB::table('suppliers')->select('id', 'name')->get();
        $brands = DB::table('brands')->select('id', 'name')->get();
        return view('admin.addProduct', ['categories' => $category, 'suppliers' => $suppliers, 'brands' => $brands]);
    }

    public function ThemSanPham(AddProductRequest $request)
    {
        $validate = $request->validated();
        $data = [
            'name' => $validate['name'],
            'discount_price' => $validate['discount_price'] ?? 0,
            'price' => $validate['price'] ?? 0,
            'description' => $validate['description'] ?? null,
            'category_id' => $validate['category'],
            'loai' => $validate['type'],
            'tags' => $validate['tag'] ?? null,
            'status' => $validate['status'] ?? 0,
            'brand_id' => $validate['brand'] ?? null,
            'supplier_id' => $validate['supplier'],
            'image' => $validate['image'] ?? null,
        ];
        if ($request->hasFile('image')) {
            // store('uploads', 'public') dùng để lưu file
            // file('image') là lấy file upload từ input
            $imagePath = $request->file('image')->store('uploads', 'public');
            $data['image'] = $imagePath;
        }
        DB::table('products')->insert($data);

        return redirect()->route('admin.sanpham')->with('status', 'Thêm sản phẩm thành công');
    }

    //Controller xóa sản phẩm
    public function XoaSanPham($id)
    {
        // Tìm sản phẩm theo ID
        $product = DB::table('products')->where('id', $id)->first();

        if (!$product) {
            return redirect()->route('admin.sanpham')->with('error', 'Sản phẩm không tồn tại');
        }

        // Nếu có ảnh thì xóa khỏi storage
        if (!empty($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        DB::table('products')->where('id', $id)->delete();

        return redirect()->route('admin.sanpham')->with('status', 'Xóa sản phẩm thành công');
    }
    public function editSanPham($id)
    {
        $sanpham = DB::table('products')->where('id', $id)->first();
        $dsLoai = DB::table('categories')->get();
        $dsThuongHieu = DB::table('brands')->get();
        $dsNhaCungCap = DB::table('suppliers')->get();

        return view('admin.EditSanPhamAdmin', compact('sanpham', 'dsLoai', 'dsThuongHieu', 'dsNhaCungCap'));
    }

    public function updateSanPham(Request $request, $id)
    {
        //  KIỂM TRA DỮ LIỆU NHẬP VÀO
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0',
            'loai' => 'required',
            'tags' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string',
        ], [
            'required' => ':attribute không được để trống!',
            'numeric' => ':attribute phải là số!',
        ], [
            'name' => 'Tên sản phẩm',
            'price' => 'Giá gốc',
            'discount_price' => 'Giá giảm',
            'loai' => 'Loại',
            'tags' => 'Tag',
            'description' => 'Mô tả',
            'image' => 'Hình ảnh',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName(); // tên file duy nhất
            $file->move(public_path('uploads/products'), $filename); // lưu file vào thư mục public/uploads/products

            $imagePath = 'uploads/products/' . $filename;
        } else {
            $imagePath = $request->old_image ?? ''; // nếu không chọn file mới, giữ nguyên file cũ
        }
        //  CẬP NHẬT DỮ LIỆU
        DB::table('products')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'description' => $request->description,
                'image' => $request->image,
                'category_id' => $request->category_id,
                'loai' => $request->loai,
                'tags' => $request->tags,
                'status' => $request->status,
                'brand_id' => $request->brand_id,
                'supplier_id' => $request->supplier_id,
            ]);

        //  THÔNG BÁO CẬP NHẬT THÀNH CÔNG
        return redirect('/admin/sanpham')->with('success', 'Cập nhật sản phẩm thành công!');
    }
}

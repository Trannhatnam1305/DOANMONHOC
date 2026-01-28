<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckOutController extends Controller
{
    public function checkout() 
    {
        // 1. Kiểm tra đăng nhập. Nếu chưa có route 'login', hãy dùng redirect('/login')
        if (!Auth::check()) return redirect('/login');
        
        $user = Auth::user(); // Thông tin tài khoản nhatnam
        $uid = Auth::id();

        // 2. Lấy giỏ hàng khớp với User ID 2
        $cartItems = DB::table('carts')
            ->leftJoin('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.user_id', $uid)
            ->select('carts.*', 'products.name', 'products.image')
            ->get();

        // 3. Tính tổng tiền dựa trên cột price trong bảng carts
        $totalAll = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // 4. Gán biến current_price để View không lỗi (Sử dụng giá đã lưu trong giỏ hàng)
        foreach($cartItems as $item) {
            $item->current_price = $item->price;
        }

        return view('user.checkout', compact('cartItems', 'totalAll', 'user'));
    }

    public function placeOrder(Request $request)
    {
        if (!Auth::check()) return redirect('/login');
        $userId = Auth::id();

        // Lấy lại giỏ hàng để lưu vào chi tiết đơn hàng
        $cartItems = DB::table('carts')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect('/')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền thực tế
        $totalPrice = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Dùng Transaction để bảo vệ dữ liệu 
        DB::transaction(function () use ($request, $userId, $cartItems, $totalPrice) {
            // A. Lưu vào bảng orders
            $orderId = DB::table('orders')->insertGetId([
                'user_id'     => $userId, // ID = 2
                'full_name'   => $request->full_name,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'note'        => $request->note,
                'total_price' => $totalPrice, // Ví dụ: 98.470.240 VNĐ
                'status'      => 'pending',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // B. Lưu chi tiết sản phẩm
            foreach ($cartItems as $item) {
                DB::table('order_details')->insert([
                    'order_id'   => $orderId,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                    'created_at' => now(),
                ]);
            }

            // C. Xóa giỏ hàng của user sau khi đặt xong
            DB::table('carts')->where('user_id', $userId)->delete();
        });

        // Quay về trang chủ và gửi thông báo thành công
        return redirect('/')->with('success', 'Đặt hàng thành công! Cảm ơn bạn đã mua sắm.');
    }

       public function updateQuantity($id, $type)
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        // 2. Tìm dòng sản phẩm trong giỏ hàng theo ID của dòng đó
        $cartItem = DB::table('carts')->where('id', $id)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity;

            if ($type == 'plus') 
            {
                $product = DB::table('products')->where('id', $cartItem->product_id)->first();
                
                // Thêm dòng này để kiểm tra xem $product có lấy được không
                if (!$product) {
                    return response()->json(['status' => 'error', 'message' => 'Lỗi: Không tìm thấy sản phẩm ID ' . $cartItem->product_id], 500);
                }

                if ($newQty >= $product->stock_quantity) {
                    return response()->json(['status' => 'error', 'message' => 'Hết hàng'], 400);
                }
                $newQty++;
            }
        elseif ($type == 'minus' && $cartItem->quantity > 1) {
                $newQty--;
            }

            // 4. Cập nhật số lượng mới vào Database
            DB::table('carts')->where('id', $id)->update(['quantity' => $newQty]);

            // 5. Tính lại tổng tiền của cả giỏ hàng cho User này
            $total = DB::table('carts')
                ->where('user_id', Auth::id())
                ->sum(DB::raw('price * quantity'));

            return response()->json([
                'status'   => 'success',
                'quantity' => $newQty,
                'subtotal' => number_format($cartItem->price * $newQty, 0, ',', '.') . ' VNĐ',
                'total'    => number_format($total, 0, ',', '.') . ' VNĐ'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy mục này'], 404);
    }
}
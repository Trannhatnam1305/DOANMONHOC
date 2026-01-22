@extends('layout.user_layout')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@section('main')
<div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shopping Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page title area -->


    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!--<div class="single-sidebar">
                        <h2 class="sidebar-title">Search Products</h2>
                        <form action="#">
                            <input type="text" placeholder="Search products...">
                            <input type="submit" value="Search">
                        </form>
                    </div> !-->

                    <div class="single-sidebar">
                        <h2 class="sidebar-title">Products</h2>
                        
                        {{-- Bắt đầu vòng lặp lấy dữ liệu từ Controller --}}
                        @foreach($products_sidebar as $item)
                            <div class="thubmnail-recent" style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div class="recent-thumb-wrapper" style="flex-shrink: 0; margin-right: 15px;">
                                    <a href="{{ route('add_to_cart', $item->id) }}">
                                        {{-- Sửa lại đường dẫn giống trang index của bạn --}}
                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                            class="recent-thumb" 
                                            alt="{{ $item->name }}" 
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                    </a>
                                </div>

                                <div class="recent-content">
                                    <h2 style="font-size: 15px; margin: 0 0 5px 0; line-height: 1.4;">
                                        <a href="{{ route('add_to_cart', $item->id) }}" style="text-decoration: none; color: #333;">
                                            {{ $item->name }}
                                        </a>
                                    </h2>   
                                    
                                    <div class="product-sidebar-price">
                                        <ins style="color: #fe5d5d; font-weight: bold; text-decoration: none;">
                                            {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                        </ins>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                   <div class="single-sidebar">
    <h2 class="sidebar-title">Sản phẩm gần đây</h2>
    <ul>
        @foreach($recent_posts as $post)
        <li>
            {{-- Trỏ về trang chi tiết sản phẩm --}}
            <a href="{{ route('product_detail', $post->id) }}">{{ $post->name }}</a>
        </li>
        @endforeach
    </ul>
</div>
</div> {{-- Đóng sidebar wrap --}}

<div class="col-md-8">
    <div class="product-content-right">
        <div class="woocommerce">
            {{-- Nên dùng route cập nhật giỏ hàng nếu bạn có làm hàm update --}}
            <form method="post" action="#">
                @csrf
                <table cellspacing="0" class="shop_table cart">
                    <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name">Sản phẩm</th>
                            <th class="product-price">Giá</th>
                            <th class="product-quantity">Số lượng</th>
                            <th class="product-subtotal">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp

                        @if(session('cart') && count(session('cart')) > 0)
                            @foreach(session('cart') as $id => $details)
                                @php 
                                    $subtotal = $details['price'] * $details['quantity']; 
                                    $total += $subtotal;
                                @endphp

                                <tr class="cart_item">
                                    <td class="product-remove">
                                        <a title="Xóa sản phẩm này" class="remove" href="{{ route('delete_cart', $id) }}" onclick="return confirm('Bạn có chắc muốn xóa?')">×</a>
                                    </td>

                                    <td class="product-thumbnail">
                                        <a href="{{ route('product_detail', $id) }}">
                                            {{-- Đã sửa đường dẫn ảnh giống trang index --}}
                                            <img width="145" height="145" alt="{{ $details['name'] }}" class="shop_thumbnail" src="{{ asset('storage/' . $details['image']) }}">
                                        </a>
                                    </td>

                                    <td class="product-name">
                                        <a href="{{ route('product_detail', $id) }}">{{ $details['name'] }}</a> 
                                    </td>

                                    <td class="product-price">
                                        <span class="amount">{{ number_format($details['price'], 0, ',', '.') }} VNĐ</span> 
                                    </td>

                                    <td class="product-quantity">
                                        <div class="quantity buttons_added">
                                            <input type="button" class="minus" value="-" onclick="location.href='{{ route('update_cart_quantity', ['id' => $id, 'type' => 'minus']) }}'">
                                            <input type="number" size="4" class="input-text qty text" title="Qty" value="{{ $details['quantity'] }}" min="1" readonly>
                                            <input type="button" class="plus" value="+" onclick="location.href='{{ route('update_cart_quantity', ['id' => $id, 'type' => 'plus']) }}'">
                                        </div>
                                    </td>

                                    <td class="product-subtotal">
                                        <span class="amount">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span> 
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 40px; font-size: 18px;">
                                    <p>Giỏ hàng của bạn đang trống!</p>
                                    <a href="{{ url('/') }}" class="button">Quay lại cửa hàng</a>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="actions" colspan="6">
                                <div class="coupon">
                                    <label for="coupon_code">Mã giảm giá:</label>
                                    <input type="text" placeholder="Nhập mã..." value="" id="coupon_code" class="input-text" name="coupon_code">
                                    <input type="submit" value="Áp dụng" name="apply_coupon" class="button">
                                </div>
                                <input type="submit" value="Cập nhật giỏ hàng" name="update_cart" class="button">
                                <a href="{{ route('checkout') }}" class="checkout-button button alt wc-forward">Thanh toán</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <div class="cart-collaterals">
                <div class="cross-sells">
                    <h2>Có thể bạn quan tâm...</h2>
                    <ul class="products">
                        @foreach($products_interested as $item)
                        <li class="product">
                            <a href="{{ route('product_detail', $item->id) }}">
                                <img width="325" height="325" alt="{{ $item->name }}" class="attachment-shop_catalog wp-post-image" src="{{ asset('storage/' . $item->image) }}">
                                
                                <h3 style="min-height: 45px; line-height: 1.4em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-top: 10px;">
                                    {{ $item->name }}
                                </h3>

                                <span class="price">
                                    <span class="amount" style="color: #fe5d5d; font-weight: bold;">
                                        {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                    </span>
                                </span>
                            </a>

                            <a class="add_to_cart_button" href="{{ route('add_to_cart', $item->id) }}" rel="nofollow">Thêm vào giỏ</a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div class="cart_totals">
                    <h2>Tổng đơn hàng</h2>
                    <table cellspacing="0">
                        <tbody>
                            <tr class="cart-subtotal">
                                <th>Tạm tính</th>
                                <td><span class="amount">{{ number_format($total, 0, ',', '.') }} VNĐ</span></td>
                            </tr>

                            <tr class="shipping">
                                <th>Phí vận chuyển</th>
                                <td>Miễn phí</td>
                            </tr>

                            <tr class="order-total">
                                <th>Tổng cộng</th>
                                <td><strong><span class="amount">{{ number_format($total, 0, ',', '.') }} VNĐ</span></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
@endsection
{{-- Dán đoạn này vào cuối file cart.blade.php --}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // --- 1. XỬ LÝ NÚT TĂNG/GIẢM SỐ LƯỢNG ---
        
        // Khi nhấn nút Cộng (+)
        $(document).on('click', '.plus', function() {
            // Tìm ô input gần nhất
            var $input = $(this).parent().find('input.qty');
            var val = parseInt($input.val());
            // Tăng lên 1 và kích hoạt sự kiện change
            $input.val(val + 1).trigger('change');
        });

        // Khi nhấn nút Trừ (-)
        $(document).on('click', '.minus', function() {
            var $input = $(this).parent().find('input.qty');
            var val = parseInt($input.val());
            // Giảm đi 1 nhưng không được nhỏ hơn 1
            if (val > 1) {
                $input.val(val - 1).trigger('change');
            }
        });

        // --- 2. XỬ LÝ TỰ ĐỘNG TÍNH TIỀN KHI SỐ LƯỢNG THAY ĐỔI ---

        $(document).on('change', 'input.qty', function() {
            var $row = $(this).closest('tr'); // Lấy dòng hiện tại
            var qty = parseInt($(this).val()); // Lấy số lượng mới

            // Lấy đơn giá (cần xử lý xóa chữ 'VNĐ' và dấu phẩy để tính toán)
            var priceText = $row.find('.product-price .amount').text();
            var price = parseInt(priceText.replace(/[^0-9]/g, '')); // Chỉ giữ lại số

            // Tính thành tiền mới
            var newSubtotal = qty * price;

            // Định dạng lại thành tiền tệ Việt Nam (Ví dụ: 10.000.000 VNĐ)
            var formattedSubtotal = new Intl.NumberFormat('vi-VN').format(newSubtotal) + ' VNĐ';

            // Cập nhật hiển thị cột Total của dòng đó
            $row.find('.product-subtotal .amount').text(formattedSubtotal);

            // Gọi hàm cập nhật tổng giỏ hàng phía dưới
            updateCartTotal();
        });

        // Hàm tính tổng cả giỏ hàng (Cart Totals)
        function updateCartTotal() {
            var grandTotal = 0;
            
            // Duyệt qua tất cả các dòng sản phẩm để cộng dồn tiền
            $('.product-subtotal .amount').each(function() {
                var val = parseInt($(this).text().replace(/[^0-9]/g, ''));
                grandTotal += val;
            });

            // Định dạng và hiển thị ra 2 ô Tổng cộng phía dưới
            var formattedGrandTotal = new Intl.NumberFormat('vi-VN').format(grandTotal) + ' VNĐ';
            
            // Cập nhật ô "Tạm tính" và "Tổng cộng"
            $('.cart-subtotal .amount').text(formattedGrandTotal);
            $('.order-total .amount').text(formattedGrandTotal);
        }
    });
</script>





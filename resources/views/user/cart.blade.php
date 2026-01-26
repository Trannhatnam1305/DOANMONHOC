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
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="single-sidebar">
                    <h2 class="sidebar-title">Products</h2>
                    @foreach($products_sidebar as $item)
                        <div class="thubmnail-recent" style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div class="recent-thumb-wrapper" style="flex-shrink: 0; margin-right: 15px;">
                                <a href="{{ route('add_to_cart', $item->id) }}">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="recent-thumb" alt="{{ $item->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                </a>
                            </div>
                            <div class="recent-content">
                                <h2 style="font-size: 15px; margin: 0 0 5px 0; line-height: 1.4;">
                                    <a href="{{ route('add_to_cart', $item->id) }}" style="text-decoration: none; color: #333;">{{ $item->name }}</a>
                                </h2>   
                                <div class="product-sidebar-price">
                                    <ins style="color: #fe5d5d; font-weight: bold; text-decoration: none;">{{ number_format($item->price, 0, ',', '.') }} VNĐ</ins>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="single-sidebar">
                    <h2 class="sidebar-title">Sản phẩm gần đây</h2>
                    <ul>
                        @foreach($recent_posts as $post)
                        <li><a href="{{ route('product_detail', $post->id) }}">{{ $post->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div> <div class="col-md-8">
                <div class="product-content-right">
                    <div class="woocommerce">
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
                                    {{-- Thay đổi cách duyệt vòng lặp --}}
                                    @php $total = 0; @endphp
                                    @if(count($cart) > 0)
                                        @foreach($cart as $item)
                                            @php 
                                                // Tính toán dựa trên thuộc tính của object
                                                $subtotal = $item->price * $item->quantity; 
                                                $total += $subtotal;
                                            @endphp
                                            <tr class="cart_item">
                                                <td class="product-remove">
                                                    {{-- Lưu ý: Dùng $item->id là ID của dòng trong bảng carts để xóa cho chuẩn --}}
                                                    <a title="Xóa" class="remove" href="{{ route('delete_cart', $item->id) }}" onclick="return confirm('Xóa sản phẩm này?')">×</a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    <a href="{{ route('product_detail', $item->product_id) }}">
                                                        <img width="145" height="145" src="{{ asset('storage/' . $item->image) }}" class="shop_thumbnail">
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <a href="{{ route('product_detail', $item->product_id) }}">{{ $item->name }}</a> 
                                                </td>
                                                <td class="product-price">
                                                    <span class="amount">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span> 
                                                </td>
                                                <td class="product-quantity">
                                                    <div class="quantity buttons_added">
                                                        <input type="button" class="minus" value="-">
                                                        {{-- Input số lượng --}}
                                                        <input type="number" size="4" class="input-text qty text" value="{{ $item->quantity }}" min="1" readonly>
                                                        <input type="button" class="plus" value="+">
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="amount">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span> 
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr><td colspan="6" class="text-center">Giỏ hàng của bạn đang trống!</td></tr>
                                    @endif
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
                                            <img width="325" height="325" src="{{ asset('storage/' . $item->image) }}">
                                            <h3 style="min-height: 45px;">{{ $item->name }}</h3>
                                            <span class="price"><span class="amount">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span></span>
                                        </a>
                                        <a class="add_to_cart_button" href="{{ route('add_to_cart', $item->id) }}">Thêm vào giỏ</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="cart_totals">
                                <h2>Tổng đơn hàng</h2>
                                <table cellspacing="0">
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
                                        <td><strong><span class="amount">{{ number_format($total, 0, ',', '.') }} VNĐ</span></strong></td>
                                    </tr>
                                </table>
                                
                                {{-- NÚT THANH TOÁN CỦA BẠN ĐÂY --}}
                                <div class="wc-proceed-to-checkout" style="margin-top: 20px; text-align: right;">
                                    <a href="{{ route('checkout') }}" class="checkout-button button alt wc-forward" 
                                    style="background: #5a88ca; color: #fff; padding: 10px 20px; text-transform: uppercase; font-weight: bold; text-decoration: none; display: inline-block;">
                                        Tiến hành thanh toán
                                    </a>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div> </div> </div> </div> @endsection

{{-- PHẦN SCRIPT GIỮ NGUYÊN NHƯ CỦA BẠN --}}

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





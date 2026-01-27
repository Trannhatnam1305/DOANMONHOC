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
            {{-- SIDEBAR --}}
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
            </div> 

            {{-- MAIN CONTENT --}}
            <div class="col-md-8">
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
                                    @if(count($cart) > 0)
                                        @foreach($cart as $item)
                                            @php 
                                                $subtotal = $item->current_product_price * $item->quantity; 
                                            @endphp
                                            <tr class="cart_item">
                                                <td class="product-remove">
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
                                                    <span class="amount">{{ number_format($item->current_product_price, 0, ',', '.') }} VNĐ</span> 
                                                </td>
                                                <td class="product-quantity">
                                                    <div class="quantity buttons_added" style="display: flex; align-items: center; gap: 5px;">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary update-qty" 
                                                                data-id="{{ $item->id }}" 
                                                                data-type="minus" 
                                                                style="padding: 2px 8px; font-weight: bold;">-</button>
                                                        
                                                        <input type="text" 
                                                            class="form-control text-center qty-input-{{ $item->id }}" 
                                                            value="{{ $item->quantity }}" 
                                                            style="width: 45px; height: 30px; padding: 0; font-weight: bold; background: #fff;" 
                                                            readonly>
                                                        
                                                        <button type="button" class="btn btn-sm btn-outline-secondary update-qty" 
                                                                data-id="{{ $item->id }}" 
                                                                data-type="plus" 
                                                                style="padding: 2px 8px; font-weight: bold;">+</button>
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="amount">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span> 
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- HIỂN THỊ PHÂN TRANG TẠI ĐÂY (Nằm ngoài vòng lặp foreach nhưng trong tbody) --}}
                                        <tr>
                                            <td colspan="6">
                                                <div class="pagination-wrapper" style="margin: 20px 0; display: flex; justify-content: center;">
                                                    {{ $cart->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr><td colspan="6" class="text-center">Giỏ hàng trống!</td></tr>
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
                                <table cellspacing="0" style="width: 100%; margin-bottom: 20px;">
                                    <tr class="cart-subtotal">
                                        <th>Tạm tính (Tất cả sản phẩm)</th>
                                        <td><span class="amount">{{ number_format($totalAll, 0, ',', '.') }} VNĐ</span></td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>Phí vận chuyển</th>
                                        <td><span style="color: #1cc88a; font-weight: bold;">Miễn phí</span></td>
                                    </tr>
                                    <tr class="order-total" style="border-top: 2px solid #eee;">
                                        <th>Tổng cộng</th>
                                        <td><strong><span class="amount" style="color: #d9534f; font-size: 1.2em;">{{ number_format($totalAll, 0, ',', '.') }} VNĐ</span></strong></td>
                                    </tr>
                                </table>

                                <div class="wc-proceed-to-checkout" style="text-align: right;">
                                    <div class="cart-actions-wrapper" style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <a href="{{ url('/') }}" class="btn-continue-shopping" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
                                            <i class="fa fa-reply"></i> Tiếp tục mua hàng
                                        </a>
                                        <a href="{{ route('checkout') }}" class="btn-proceed-checkout" style="background: #5a88ca; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                                            Tiến hành thanh toán <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
</div> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('click', '.update-qty', function(e) {
        e.preventDefault();
        
        let btn = $(this);
        let id = btn.data('id');
        let type = btn.data('type');
        let url = "/update-cart-quantity/" + id + "/" + type;

        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if(response.status === 'success') {
                    // 1. Tìm ô input số lượng của chính dòng này và cập nhật số mới
                    // Lưu ý: Tôi giả định bạn đặt class cho input là qty-input-{{$item->id}}
                    let inputField = $('.qty-' + id);
                    inputField.val(response.quantity);

                    // 2. Nếu bạn muốn tiền cũng nhảy theo:
                    window.location.reload(); 
                    
                    // HOẶC dùng lệnh này để ép trình duyệt load lại chuẩn nhất:
                    window.location.href = window.location.href;
                }
            },
            error: function(xhr) {
                alert('Lỗi kết nối server!');
            }
        });
    });
});
</script>
@endsection
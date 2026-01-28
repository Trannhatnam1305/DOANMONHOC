@extends('layout.user_layout')

{{-- Thêm meta tags để hỗ trợ responsive tốt hơn --}}
@section('main')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">

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
        {{-- Hiển thị thông báo lỗi nếu có --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            {{-- SIDEBAR --}}
            <div class="col-md-4">
                <div class="single-sidebar">
                    <h2 class="sidebar-title">Sản phẩm nổi bật</h2>
                    @foreach($products_sidebar as $item)
                        <div class="thubmnail-recent" style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div class="recent-thumb-wrapper" style="flex-shrink: 0; margin-right: 15px;">
                                <a href="{{ route('product_detail', $item->id) }}">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;">
                                </a>
                            </div>
                            <div class="recent-content">
                                <h2 style="font-size: 14px; margin: 0 0 5px 0; line-height: 1.4; font-weight: 600;">
                                    <a href="{{ route('product_detail', $item->id) }}" style="text-decoration: none; color: #333;">{{ $item->name }}</a>
                                </h2>   
                                <div class="product-sidebar-price">
                                    <ins style="color: #fe5d5d; font-weight: bold; text-decoration: none;">{{ number_format($item->price, 0, ',', '.') }} VNĐ</ins>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="single-sidebar">
                    <h2 class="sidebar-title">Xem gần đây</h2>
                    <ul class="list-unstyled">
                        @foreach($recent_posts as $post)
                            <li style="padding: 8px 0; border-bottom: 1px dashed #eee;">
                                <i class="fa fa-angle-right" style="margin-right: 10px; color: #5a88ca;"></i>
                                <a href="{{ route('product_detail', $post->id) }}" style="color: #666;">{{ $post->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div> 

            {{-- MAIN CONTENT --}}
            <div class="col-md-8">
                <div class="product-content-right">
                    <div class="woocommerce">
                        <table cellspacing="0" class="shop_table cart table-responsive-sm">
                            <thead>
                                <tr style="background-color: #f9f9f9;">
                                    <th class="product-remove">Xóa</th>
                                    <th class="product-thumbnail">Ảnh</th>
                                    <th class="product-name">Sản phẩm</th>
                                    <th class="product-price">Giá</th>
                                    <th class="product-quantity">Số lượng</th>
                                    <th class="product-subtotal">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cart as $item)
                                    @php 
                                        $subtotal = $item->current_product_price * $item->quantity; 
                                    @endphp
                                    <tr class="cart_item">
                                        <td class="product-remove">
                                            <a title="Xóa" class="remove" href="{{ route('delete_cart', $item->id) }}" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" 
                                               style="color: red !important; font-size: 20px; text-decoration: none;">×</a>
                                        </td>
                                        <td class="product-thumbnail">
                                            <a href="{{ route('product_detail', $item->product_id) }}">
                                                <img width="80" height="80" src="{{ asset('storage/' . $item->image) }}" class="shop_thumbnail" style="object-fit: cover;">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <a href="{{ route('product_detail', $item->product_id) }}" style="font-weight: 500;">{{ $item->name }}</a>
                                        </td>
                                        <td class="product-price">
                                            <span class="amount">{{ number_format($item->current_product_price, 0, ',', '.') }} VNĐ</span> 
                                        </td>
                                        <td class="product-quantity">
                                            <div class="quantity" style="display: flex; align-items: center; gap: 4px;">
                                                {{-- Nút giảm --}}
                                                <a href="{{ route('update_cart_qty', ['id' => $item->id, 'type' => 'minus']) }}" 
                                                   class="btn btn-sm btn-light" 
                                                   style="border: 1px solid #ddd; padding: 2px 10px;">-</a>

                                                <input type="text" 
                                                       class="form-control text-center" 
                                                       value="{{ $item->quantity }}" 
                                                       style="width: 40px; height: 31px; padding: 0; font-weight: bold; background: #fff;" 
                                                       readonly>

                                                {{-- Nút tăng --}}
                                                <a href="{{ route('update_cart_qty', ['id' => $item->id, 'type' => 'plus']) }}" 
                                                   class="btn btn-sm btn-light" 
                                                   style="border: 1px solid #ddd; padding: 2px 10px;">+</a>
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="amount" style="font-weight: bold; color: #333;">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span> 
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center" style="padding: 40px;">Giỏ hàng trống! <a href="{{ url('/') }}">Tiếp tục mua sắm</a></td></tr>
                                @endforelse

                                @if($cart->count() > 0)
                                    <tr>
                                        <td colspan="6">
                                            <div class="pagination-wrapper" style="margin: 20px 0; display: flex; justify-content: center;">
                                                {{ $cart->links('pagination::bootstrap-4') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="cart-collaterals" style="margin-top: 30px;">
                            <div class="cross-sells">
                                <h2 style="font-size: 20px; border-bottom: 2px solid #5a88ca; display: inline-block; padding-bottom: 5px; margin-bottom: 20px;">Gợi ý cho bạn</h2>
                                <ul class="products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; list-style: none; padding: 0;">
                                    @foreach($products_interested as $item)
                                    <li class="product" style="text-align: center; border: 1px solid #eee; padding: 15px; border-radius: 8px;">
                                        <a href="{{ route('product_detail', $item->id) }}" style="text-decoration: none;">
                                            <img src="{{ asset('storage/' . $item->image) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                                            <h3 style="font-size: 14px; margin: 10px 0; color: #333; height: 40px; overflow: hidden;">{{ $item->name }}</h3>
                                            <span class="price" style="display: block; margin-bottom: 10px;">
                                                <span class="amount" style="color: #fe5d5d; font-weight: bold;">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                                            </span>
                                        </a>
                                        <a class="add_to_cart_button" href="{{ route('add_to_cart', $item->id) }}" style="background: #5a88ca; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; text-decoration: none;">Thêm vào giỏ</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="cart_totals" style="background: #fcfcfc; padding: 25px; border: 1px solid #eee; border-radius: 8px;">
                                <h2 style="font-size: 20px; margin-bottom: 20px;">Tóm tắt đơn hàng</h2>
                                <table cellspacing="0" style="width: 100%;">
                                    <tr class="cart-subtotal">
                                        <th style="padding: 10px 0; font-weight: normal;">Tạm tính</th>
                                        <td style="text-align: right;"><span class="amount">{{ number_format($totalAll, 0, ',', '.') }} VNĐ</span></td>
                                    </tr>
                                    <tr class="shipping">
                                        <th style="padding: 10px 0; font-weight: normal;">Phí giao hàng</th>
                                        <td style="text-align: right;"><span style="color: #1cc88a; font-weight: bold;">Miễn phí</span></td>
                                    </tr>
                                    <tr class="order-total" style="border-top: 2px solid #ddd;">
                                        <th style="padding: 20px 0; font-size: 18px;">Tổng cộng</th>
                                        <td style="text-align: right;"><strong><span class="amount" style="color: #d9534f; font-size: 22px;">{{ number_format($totalAll, 0, ',', '.') }} VNĐ</span></strong></td>
                                    </tr>
                                </table>

                                <div class="wc-proceed-to-checkout" style="margin-top: 25px;">
                                    <div class="cart-actions-wrapper" style="display: flex; flex-direction: column; gap: 10px;">
                                        <a href="{{ route('checkout') }}" class="btn-proceed-checkout" style="background: #d9534f; color: white; padding: 15px; text-decoration: none; border-radius: 6px; font-weight: bold; text-align: center; font-size: 16px;">
                                            TIẾN HÀNH THANH TOÁN <i class="fa fa-arrow-right" style="margin-left: 10px;"></i>
                                        </a>
                                        <a href="{{ url('/') }}" style="text-align: center; color: #666; font-size: 14px; text-decoration: none; padding: 10px;">
                                            <i class="fa fa-reply"></i> Tiếp tục mua sắm
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
@endsection
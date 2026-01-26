@extends('layout.user_layout')

@section('main')
<style>
    /* CSS Tối ưu cho trang chi tiết */
    .product-main-img img {
        width: 100%;
        height: auto;
        border: 1px solid #f1f1f1;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
    }
    .product-inner-price ins {
        color: #fe4536;
        font-size: 28px;
        text-decoration: none;
        font-weight: 700;
        margin-right: 15px;
    }
    .product-inner-price del {
        color: #888;
        font-size: 18px;
    }
    .product-breadcroumb {
        margin-bottom: 25px;
        font-size: 14px;
        color: #777;
    }
    .product-breadcroumb a { color: #5a88ca; margin: 0 5px; }
    
    .quantity { display: inline-block; margin-right: 15px; }
    .quantity input {
        width: 60px;
        height: 42px;
        text-align: center;
        border: 1px solid #ccc;
    }
    .add_to_cart_button {
        background: #5a88ca;
        color: #fff;
        border: none;
        padding: 10px 30px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
    }
    .add_to_cart_button:hover { background: #333; }

    .product-tab { margin-top: 40px; border-bottom: 1px solid #ddd; }
    .product-tab li a { padding: 10px 25px; display: inline-block; color: #333; font-weight: 600; }
    .product-tab li.active a { background: #5a88ca; color: #fff; border-radius: 5px 5px 0 0; }
    .tab-content { padding: 20px; border: 1px solid #ddd; border-top: none; background: #fff; }

    .related-products-wrapper { margin-top: 50px; }
    .related-products-title { font-size: 24px; margin-bottom: 30px; text-transform: uppercase; text-align: center; }
    .single-related-product { 
        border: 1px solid #eee; 
        padding: 15px; 
        text-align: center; 
        transition: 0.3s; 
    }
    .single-related-product:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .single-related-product img { width: 100%; height: 200px; object-fit: contain; }
</style>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 style="color:#fff; padding: 40px 0;">{{ $product->name }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="single-sidebar">
                    <h2 class="sidebar-title">Tìm kiếm</h2>
                    <form action="#">
                        <input type="text" placeholder="Tìm sản phẩm..." style="width:70%; padding:8px;">
                        <input type="submit" value="Tìm" style="padding:8px 15px; background:#5a88ca; color:#fff; border:none;">
                    </form>
                </div>
                
                <div class="single-sidebar" style="margin-top:30px;">
                    <h2 class="sidebar-title">Sản phẩm mới</h2>
                    <div class="thubmnail-recent" style="display: flex; gap: 10px; margin-bottom:15px;">
                        <img src="{{ asset('storage/' . $product->image) }}" style="width:60px; height:60px; object-fit:cover;">
                        <div>
                            <h4 style="font-size:14px; margin:0;"><a href="#">{{ $product->name }}</a></h4>
                            <p style="color:#5a88ca;">{{ number_format($product->price) }}đ</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="product-content-right">
                    <div class="product-breadcroumb">
                        <a href="/">Trang chủ</a> /
                        <a href="#">{{ $product->category->name ?? 'Điện thoại' }}</a> /
                        <span>{{ $product->name }}</span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="product-main-img">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="product-inner">
                                <h2 style="font-size:28px; margin-top:0;">{{ $product->name }}</h2>
                                <div class="product-inner-price">
                                    <ins>{{ number_format($product->discount_price ?? $product->price) }}đ</ins>
                                    @if($product->discount_price)
                                        <del>{{ number_format($product->price) }}đ</del>
                                    @endif
                                </div>

                                <div style="margin: 20px 0;">
                                    <p><strong>Trạng thái:</strong> <span class="text-success">{{ $product->status == 1 ? 'Còn hàng' : 'Hết hàng' }}</span></p>
                                    <p><strong>Lượt xem:</strong> {{ $product->views ?? 0 }} (Mục 17)</p>
                                    <p><strong>Số lượng hiện có:</strong> {{ $product->stock_quantity ?? 'Liên hệ' }}</p>
                                </div>

                                    <div class="add-to-cart-form" style="margin: 20px 0;">
                                        <form action="{{ route('add_to_cart', $product->id) }}" method="POST" class="cart">
                                            @csrf
                                            <div class="quantity">
                                                <input type="number" size="4" class="input-text qty text" value="1" name="quantity" min="1">
                                            </div>
                                            <button class="add_to_cart_button" type="submit">Thêm vào giỏ</button>
                                        </form>
                                    </div>

                                <div class="product-inner-category" style="margin-top:25px; border-top:1px solid #eee; padding-top:15px;">
                                    <p>Danh mục: <a href="#">{{ $product->category->name ?? 'Chưa rõ' }}</a>. Tags: <a href="#">{{ $product->tags ?? 'apple' }}</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div role="tabpanel">
                        <ul class="product-tab" role="tablist" style="display:flex; list-style:none; padding:0;">
                            <li class="active"><a href="#home" data-toggle="tab">Mô tả sản phẩm</a></li>
                            <li><a href="#profile" data-toggle="tab">Đánh giá</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <h2>Chi tiết</h2>  
                                {!! $product->description !!}
                                <hr>
                                <p><strong>Loại sản phẩm:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
                            </div>
                            <div class="tab-pane" id="profile">
                                <h2>Đánh giá</h2>
                                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            </div>
                        </div>
                    </div>

                    <div class="related-products-wrapper">
                        <h2 class="related-products-title">Sản phẩm liên quan</h2>
                        <div class="row">
                            @forelse($relatedProducts as $related)
                                <div class="col-md-4">
                                    <div class="single-related-product">
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="">
                                        <h3><a href="{{ route('product_detail', $related->id) }}">{{ $related->name }}</a></h3>
                                        <div class="product-wid-price">
                                            <ins>{{ number_format($related->price) }}đ</ins>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">Không có sản phẩm liên quan nào.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layout.user_layout')
@section('main')
<div class="slider-area">
    <div id="slide-list" class="carousel carousel-fade slide" data-ride="carousel" data-interval="5000">
        
        <ol class="carousel-indicators">
            @foreach($sliders as $key => $slide)
                <li data-target="#slide-list" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
            @endforeach
        </ol>

        <div class="carousel-inner" role="listbox">
            @foreach($sliders as $key => $slide)
                <div class="item {{ $key == 0 ? 'active' : '' }}">
                    <div class="single-slide">
                        <div class="slide-bg" style="background-image: url('{{ asset('storage/' . $slide->image) }}');"></div>
                        
                        <div class="slide-text-wrapper">
                            <div class="slide-text">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-6">
                                            <div class="slide-content">
                                                <h2>{{ $slide->name }}</h2>
                                                <p>{{ \Illuminate\Support\Str::limit($slide->description, 150) }}</p>
                                                <a href="{{ url('product/'.$slide->id) }}" class="readmore">Mua ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a class="left carousel-control" href="#slide-list" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="right carousel-control" href="#slide-list" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>

<div class="promo-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="single-promo">
                    <i class="fa fa-refresh"></i>
                    <p>30 Days return</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="single-promo">
                    <i class="fa fa-truck"></i>
                    <p>Free shipping</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="single-promo">
                    <i class="fa fa-lock"></i>
                    <p>Secure payments</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="single-promo">
                    <i class="fa fa-gift"></i>
                    <p>New products</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-widget-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">Top Sellers</h2>
                    <a href="/shop" class="wid-view-more">View All</a>

                    @foreach($products_seller as $product_seller)
                    <div class="single-wid-product">
                        <a href="single-product.html"><img src="{{ asset('storage/' . $product_seller->image) }}" alt="" class="product-thumb"></a>
                        <h2><a href="single-product.html">{{$product_seller->name}}</a></h2>
                        <div class="product-wid-rating">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </div>
                        <div class="product-wid-price">
                            <ins>${{$product_seller->discount_price}}</ins> <del>${{$product_seller->price}}</del>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">Recently Viewed</h2>
                    <a href="/shop" class="wid-view-more">View All</a>

                    @foreach ($products_recently_view as $product_recent)
                        <div class="single-wid-product">
                        <a href="single-product.html"><img src="{{ asset('storage/' . $product_recent->image) }}" alt="" class="product-thumb"></a>
                        <h2><a href="single-product.html">{{$product_recent->name}}</a></h2>
                        <div class="product-wid-price">
                            <ins>${{$product_recent->discount_price}}</ins> <del>${{$product_recent->price}}</del>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-product-widget">
                    <h2 class="product-wid-title">Top New</h2>
                    <a href="/blog" class="wid-view-more">View All</a>

                    @foreach ($products_top_new as $product_top_new)
                        <div class="single-wid-product">
                        <a href="single-product.html"><img src="{{ asset('storage/' . $product_top_new->image) }}" alt="" class="product-thumb"></a>
                        <h2><a href="single-product.html">{{$product_top_new->name}}</a></h2>
                        <div class="product-wid-price">
                            <ins>${{ $product_top_new->discount_price}}</ins> <del>${{ $product_top_new->price}}</del>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
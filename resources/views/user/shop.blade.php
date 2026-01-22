@extends('layout.client_layout') {{-- Đảm bảo layout này tồn tại --}}

@section('main')
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Cửa Hàng</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">

                {{-- BẮT ĐẦU VÒNG LẶP SẢN PHẨM --}}
                @foreach ($products as $product)
                    <div class="col-md-3 col-sm-6">
                        {{-- Khoảng cách margin-bottom để các hàng không bị dính vào nhau --}}
                        <div class="single-shop-product" style="margin-bottom: 50px; text-align: center;">
                            
                            <div class="product-upper">
                                {{-- 
                                    1. Lấy ảnh từ folder storage (nếu bạn upload qua admin) 
                                    2. Fix chiều cao 220px và object-fit: cover để ảnh không bị méo/vỡ khung
                                --}}
                                <a href="{{ url('product/'.$product->id) }}">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         style="height: 220px; width: 100%; object-fit: cover; border-radius: 5px;">
                                </a>
                            </div>

                            {{-- 
                                Fix chiều cao tên sản phẩm (min-height) để các nút bấm bên dưới luôn thẳng hàng ngang nhau 
                                Sử dụng -webkit-line-clamp để tự động cắt chữ nếu quá 2 dòng
                            --}}
                            <h2 style="min-height: 48px; font-size: 18px; line-height: 1.2em; margin: 15px 0; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <a href="{{ url('product/'.$product->id) }}" style="color: #333; text-decoration: none;">
                                    {{ $product->name }}
                                </a>
                            </h2>

                            <div class="product-carousel-price" style="margin-bottom: 15px;">
                                {{-- Định dạng tiền tệ VNĐ --}}
                                <ins style="color: #5a88ca; font-weight: bold; text-decoration: none; font-size: 16px;">
                                    {{ number_format($product->discount_price, 0, ',', '.') }} VNĐ
                                </ins> <br>
                                <del style="color: #999; font-size: 14px;">
                                    {{ number_format($product->price, 0, ',', '.') }} VNĐ
                                </del>
                            </div>

                            <div class="product-option-shop">
                                {{-- Link add to cart truyền kèm ID sản phẩm --}}
                                <a class="add_to_cart_button" rel="nofollow" href="{{ route('add_to_cart', $product->id) }}" 
                                   style="background: #5a88ca; color: #fff; padding: 10px 20px; text-transform: uppercase; font-weight: bold; border-radius: 3px; display: inline-block;">
                                    <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                </a>
                            </div>                       
                        </div>
                    </div>
                @endforeach
                {{-- KẾT THÚC VÒNG LẶP --}}

            </div>

            {{-- PHÂN TRANG --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center">
                        <nav>
                            {{-- 
                                Nếu bạn dùng Controller có ->paginate(12), hãy dùng dòng dưới để tự sinh link phân trang:
                                {{ $products->links() }}
                            --}}
                            <ul class="pagination">
                                <li>
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li>
                                    <a href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
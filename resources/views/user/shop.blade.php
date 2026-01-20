@extends('layout.user_layout')

@section('main')
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shop</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">

                {{-- VÒNG LẶP SẢN PHẨM --}}
                @foreach ($products as $product)
                    <div class="col-md-3 col-sm-6">
                        {{-- Thêm style margin-bottom để các hàng không dính nhau --}}
                        <div class="single-shop-product" style="margin-bottom: 50px;">

                            <div class="product-upper">
                                {{--
                                1. Cập nhật đường dẫn ảnh (uploads/)
                                2. Thêm style fix chiều cao 220px và object-fit để ảnh không méo
                                --}}
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}"
                                    style="height: 220px; width: 100%; object-fit: cover;">
                            </div>

                            {{--
                            Thay thế đoạn PHP substr bằng CSS giúp tên luôn chiếm 2 dòng
                            để nút Add to cart luôn thẳng hàng
                            --}}
                            <h2
                                style="min-height: 45px; font-size: 18px; line-height: 1.3em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-top: 15px;">
                                <a href="#">{{ $product->name }}</a>
                            </h2>

                            <div class="product-carousel-price">
                                {{-- Thêm number_format để hiển thị dấu phẩy tiền tệ --}}
                                <ins>{{ number_format($product->discount_price) }} VNĐ</ins>
                                <del>{{ number_format($product->price) }} VNĐ</del>
                            </div>

                            <div class="product-option-shop">
                                {{-- Cập nhật Route thêm vào giỏ hàng --}}
                                {{-- Quan trọng: Phải gọi đúng tên route 'add_to_cart' và truyền ID sản phẩm vào --}}
                                <a class="btn btn-default add-to-cart save-scroll" rel="nofollow" href="{{ route('add_to_cart', $product->id) }}">
                                    Add to cart
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
                            Nếu controller bạn dùng ->paginate(10) thì dùng dòng dưới:
                            {{ $products->links() }}
                            Còn hiện tại mình giữ nguyên HTML tĩnh của bạn
                            --}}
                            <ul class="pagination">
                                <li>
                                    <a href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
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
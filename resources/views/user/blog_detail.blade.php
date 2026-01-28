@extends('layout.user_layout')
@section('main')
<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Kiến thức công nghệ</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="container">
        <div class="row">
            
            {{-- CỘT TRÁI: HIỂN THỊ NỘI DUNG BÀI VIẾT --}}
            <div class="col-md-8">
                <div class="product-content-right">
                    
                    {{-- 1. Tiêu đề bài viết --}}
                    <h1 style="font-size: 28px; color: #333; margin-bottom: 20px;">{{ $current_post['title'] }}</h1>
                    

                    <hr>

                    {{-- 3. Ảnh bài viết --}}
                    <img src="{{ asset('img/product-thumb-1.jpg') }}" alt="" style="width: 100%; margin-bottom: 30px;">

                    {{-- 4. Nội dung bài viết (Giả lập text dài) --}}
                    <div class="entry-content" style="font-size: 16px; line-height: 1.8; text-align: justify;">
                        <p><strong>{{ $current_post['title'] }}</strong> là chủ đề đang được rất nhiều người quan tâm hiện nay...</p>
                        
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        
                        <h3>Những điểm nổi bật cần lưu ý</h3>
                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        
                        <ul>
                            <li>Lựa chọn cấu hình phù hợp với nhu cầu.</li>
                            <li>Kiểm tra kỹ chế độ bảo hành.</li>
                            <li>So sánh giá tại các cửa hàng uy tín.</li>
                        </ul>

                        <p>Hy vọng qua bài viết này, Electronic đã giúp bạn có thêm thông tin hữu ích để đưa ra quyết định mua sắm thông minh nhất.</p>
                    </div>

                    {{-- Nút quay lại --}}
                    <div style="margin-top: 40px;">
                        <a href="{{ route('blog') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Quay lại trang tin tức</a>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: VẪN GIỮ DANH SÁCH BÀI KHÁC ĐỂ TIỆN CLICK --}}
            <div class="col-md-4">
                <div class="single-sidebar">
                    <h2 class="sidebar-title">Bài viết khác</h2>
                    @foreach($list_posts as $post)
                        {{-- Không hiển thị lại bài đang xem --}}
                        @if($post['id'] != $current_post['id'])
                        <div class="thubmnail-recent" style="display: flex; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <img src="{{ asset('img/product-thumb-1.jpg') }}" class="recent-thumb" alt="" style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">
                            <div>
                                <h4 style="font-size: 14px; margin: 0;">
                                    <a href="{{ route('blog.detail', ['id' => $post['id']]) }}">{{ $post['title'] }}</a>
                                </h4>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
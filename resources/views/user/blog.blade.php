@extends('layout.user_layout')
{{-- Lưu ý: Nếu trang này bị vỡ giao diện, hãy thử đổi thành @extends('layout.user_layout') --}}

@section('main') {{-- Hoặc @section('content') tùy theo layout của bạn --}}

    {{-- 1. Phần tiêu đề trang --}}
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Tin tức & Giới thiệu</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Phần nội dung chính chia 2 cột --}}
    <div class="single-product-area">
        <div class="container">
            <div class="row">

                {{-- CỘT BÊN TRÁI (Chiếm 8 phần): Nội dung giới thiệu công ty --}}
                <div class="col-md-8">
                    <div class="product-content-right">

                        {{-- Tiêu đề chính --}}
                        <h2 class="sidebar-title">Về công ty Electronic</h2>

                        <div class="entry-content" style="margin-top: 20px;">

                            {{-- Ảnh đại diện công ty --}}
                            <div class="about-img" style="margin-bottom: 25px;">
                                <img src="{{ asset('img/product-thumb-2.jpg') }}" alt="Về công ty Electronic"
                                    style="width: 100%; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                <p style="text-align: center; margin-top: 10px; font-style: italic; color: #888;">Không gian
                                    mua sắm hiện đại tại Electronic</p>
                            </div>

                            {{-- 1. Giới thiệu chung --}}
                            <h3 style="color: #5a88ca; margin-top: 30px;">1. Câu chuyện thương hiệu</h3>
                            <p style="font-size: 16px; line-height: 1.8; text-align: justify;">
                                Chào mừng quý khách đến với <strong>Electronic</strong> – Hệ thống bán lẻ thiết bị công nghệ
                                hàng đầu.
                                Với khát vọng mang công nghệ hiện đại đến gần hơn với mọi người, chúng tôi không ngừng nỗ
                                lực để trở thành điểm đến tin cậy nhất cho các tín đồ yêu thích đồ điện tử.
                                Tại Electronic, chúng tôi không chỉ bán sản phẩm, chúng tôi bán sự an tâm và trải nghiệm
                                tuyệt vời.
                            </p>

                            {{-- 2. Thông tin doanh nghiệp --}}
                            <h3 style="color: #5a88ca; margin-top: 30px;">2. Thông tin doanh nghiệp</h3>
                            <ul style="list-style: none; padding-left: 0; line-height: 2;">
                                <li><strong>🏢 Tên công ty:</strong> Công Ty TNHH Công Nghệ Electronic Việt Nam</li>
                                <li><strong>📍 Địa chỉ trụ sở:</strong> 91 Phạm Văn Hai, Phường 3, Quận Tân Bình, TP.HCM
                                </li>
                                <li><strong>📞 Hotline:</strong> 0772.749.227 (Hỗ trợ 24/7)</li>
                                <li><strong>📧 Email:</strong> contact@electronic.com</li>
                            </ul>

                            {{-- 3. Lĩnh vực kinh doanh --}}
                            <h3 style="color: #5a88ca; margin-top: 30px;">3. Lĩnh vực kinh doanh</h3>
                            <p>Electronic chuyên phân phối chính hãng các dòng sản phẩm công nghệ mới nhất thị trường, bao
                                gồm:</p>
                            <ul style="list-style-type: disc; margin-left: 20px; line-height: 1.8;">
                                <li><strong>Điện thoại thông minh (Smartphone):</strong> Apple iPhone, Samsung Galaxy,
                                    Xiaomi, Oppo...</li>
                                <li><strong>Máy tính xách tay (Laptop):</strong> Dell, HP, Asus, Macbook, Lenovo... phục vụ
                                    từ văn phòng đến Gaming.</li>
                                <li><strong>Máy tính để bàn (PC):</strong> PC Gaming, PC đồ họa, linh kiện lắp ráp.</li>
                                <li><strong>Thiết bị đeo thông minh:</strong> Đồng hồ thông minh (Smartwatch), vòng đeo tay
                                    sức khỏe.</li>
                                <li><strong>Phụ kiện chính hãng:</strong> Tai nghe, sạc dự phòng, chuột, bàn phím...</li>
                            </ul>

                            {{-- 4. Chính sách & Cam kết --}}
                            <div
                                style="background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 30px; border-left: 5px solid #5a88ca;">
                                <h3 style="color: #5a88ca; margin-top: 0;">4. Chính sách & Cam kết vàng</h3>
                                <p>Chúng tôi hiểu rằng sự hài lòng của khách hàng là thước đo thành công. Vì vậy, Electronic
                                    cam kết:</p>
                                <ul style="list-style-type: check; margin-left: 0; padding-left: 0;">
                                    <li style="margin-bottom: 10px;">✅ <strong>Sản phẩm chính hãng 100%:</strong> Đền bù gấp
                                        đôi nếu phát hiện hàng giả, hàng nhái.</li>
                                    <li style="margin-bottom: 10px;">✅ <strong>Bảo hành uy tín:</strong> Bảo hành chính hãng
                                        12 - 24 tháng tùy sản phẩm. Hỗ trợ kỹ thuật trọn đời.</li>
                                    <li style="margin-bottom: 10px;">✅ <strong>Đổi trả dễ dàng:</strong> 1 đổi 1 trong vòng
                                        30 ngày nếu có lỗi từ nhà sản xuất.</li>
                                    <li style="margin-bottom: 10px;">✅ <strong>Giao hàng toàn quốc:</strong> Miễn phí vận
                                        chuyển cho đơn hàng từ 500k. Nhận hàng kiểm tra rồi mới thanh toán.</li>
                                    <li>✅ <strong>Hỗ trợ trả góp:</strong> Trả góp 0% lãi suất qua thẻ tín dụng, thủ tục xét
                                        duyệt nhanh chóng trong 15 phút.</li>
                                </ul>
                            </div>

                            <p style="margin-top: 30px; font-weight: bold; text-align: center;">
                                Electronic - Trao uy tín, Nhận niềm tin! <br>
                                Rất hân hạnh được phục vụ quý khách.
                            </p>

                        </div>
                    </div>
                </div>

                {{-- CỘT BÊN PHẢI (Chiếm 4 phần): Danh sách bài viết --}}
                <div class="col-md-4">
                    <div class="single-sidebar">
                        <h2 class="sidebar-title">Bài viết mới nhất</h2>

                        {{-- Vòng lặp hiển thị danh sách bài viết từ Controller --}}
                        @if(isset($list_posts) && count($list_posts) > 0)
                            @foreach($list_posts as $post)
                                <div class="thubmnail-recent"
                                    style="display: flex; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                    <img src="{{ asset('img/product-thumb-1.jpg') }}" class="recent-thumb" alt=""
                                        style="width: 60px; height: 60px; object-fit: cover; margin-right: 15px;">

                                    <div>
                                        
                                        <h4 style="font-size: 14px; margin: 0;">
                                            <a href="{{ route('blog.detail', ['id' => $post['id']]) }}">
                                                {{ $post['title'] }}
                                            </a>
                                        </h4>

                                        <a href="{{ route('blog.detail', ['id' => $post['id']]) }}"
                                            style="font-size: 12px; color: #1abc9c;">
                                            Xem chi tiết &raquo;
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Chưa có bài viết nào.</p>
                        @endif

                    </div>

                    {{-- Banner quảng cáo nhỏ bên phải --}}
                    <div class="single-sidebar" style="margin-top: 30px;">
                        <h2 class="sidebar-title">Sản phẩm nổi bật</h2>
                        <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                            <img src="{{ asset('img/product-2.jpg') }}" alt="" style="width: 80%;">
                            <p><strong>iPhone 15 Pro Max</strong></p>
                            <a href="#" class="add_to_cart_button">Mua ngay</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
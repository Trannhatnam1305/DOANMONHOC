<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eElectronics - HTML eCommerce Template</title>

    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @yield('css')
</head>
<body>
    <div class="header-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                        <ul>
                            <li><a href="#"><i class="fa fa-heart"></i> Danh sách yêu thích</a></li>
                            <li><a href="/cart"><i class="fa fa-shopping-cart"></i> Giỏ hàng</a></li>
                            <li><a href="/checkout"><i class="fa fa-money"></i> Thanh toán</a></li>
                            @if(Auth::check())
                                <li>
                                    <a href="{{ route('user.profile.edit') }}">
                                        <i class="fa fa-user"></i> Xin chào, {{ Auth::user()->username ?? Auth::user()->name }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                                </li>
                            @else
                                <li><a href="{{ route('login') }}"><i class="fa fa-unlock-alt"></i> Đăng nhập</a></li>
                                <li><a href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Đăng ký</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> <div class="site-branding-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="logo">
                        <h1><a href="/">e<span>Electronics</span></a></h1>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="shopping-item">
                        <a href="/cart">Giỏ hàng - <span class="cart-amunt">$800</span> <i class="fa fa-shopping-cart"></i> <span class="product-count">5</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="/shop">Danh sách sản phẩm</a></li>
                        <li><a href="/cart">Giỏ hàng</a></li>

                        <li><a href="#">Danh mục</a></li>

                        <li><a href="/contact">liên hệ</a></li>

                        <li><a href="/blog">Blog</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> @yield('main')

    <div class="footer-top-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="footer-about-us">
                        <h2>e<span>Electronics</span></h2>
                        <p>Chuyên cung cấp các thiết bị điện tử chính hãng với giá cả cạnh tranh nhất thị trường.</p>
                        <div class="footer-social">
                            {{-- Lấy link từ bảng settings thông qua biến $socials --}}
                            <a href="{{ $socials['facebook'] ?? '#' }}" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="{{ $socials['twitter'] ?? '#' }}" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="{{ $socials['youtube'] ?? '#' }}" target="_blank"><i class="fa fa-youtube"></i></a>
                            <a href="{{ $socials['linkedin'] ?? '#' }}" target="_blank"><i class="fa fa-linkedin"></i></a>
                            <a href="{{ $socials['pinterest'] ?? '#' }}" target="_blank"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Người dùng</h2>
                        <ul>
                            <li><a href="#">Tài khoản của tôi</a></li>
                            <li><a href="#">Lịch sử đơn hàng</a></li>
                            <li><a href="#">Danh sách yêu thích</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Danh mục</h2>
                        <ul>
                            <li><a href="#">Điện thoại</a></li>
                            <li><a href="#">Máy tính bảng</a></li>
                            <li><a href="#">Laptop</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="footer-newsletter">
                        <h2 class="footer-wid-title">Bản tin</h2>
                        <p>Đăng ký để nhận những thông báo khuyến mãi mới nhất từ chúng tôi!</p>
                        <div class="newsletter-form">
                            <form action="#">
                                <input type="email" placeholder="Nhập email của bạn">
                                <input type="submit" value="Đăng ký">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="copyright">
                        <p>&copy; 2026 eElectronics. All Rights Reserved.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-card-icon">
                        <i class="fa fa-cc-mastercard"></i>
                        <i class="fa fa-cc-paypal"></i>
                        <i class="fa fa-cc-visa"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End footer bottom area -->

    <script src="https://code.jquery.com/jquery.min.js"></script>

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    {{-- SỬA LẠI CÁC DÒNG NÀY --}}
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.sticky.js') }}"></script>

    <script src="{{ asset('js/jquery.easing.1.3.min.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    
    @yield('js')
</body>
</html>
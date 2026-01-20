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
    </div> <!-- End Page title area -->


    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!--<div class="single-sidebar">
                        <h2 class="sidebar-title">Search Products</h2>
                        <form action="#">
                            <input type="text" placeholder="Search products...">
                            <input type="submit" value="Search">
                        </form>
                    </div> !-->

                    <div class="single-sidebar">
                        <h2 class="sidebar-title">Products</h2>
                        
                        {{-- Bắt đầu vòng lặp lấy dữ liệu từ Controller --}}
                        @foreach($products_sidebar as $item)
                        <div class="thubmnail-recent">
                            {{-- Ảnh sản phẩm --}}
                            <img src="{{ asset('uploads/' . $item->image) }}" class="recent-thumb" alt="{{ $item->name }}">
                            
                            {{-- Tên sản phẩm --}}
                            <h2>
                                <a href="{{ route('add_to_cart', $item->id) }}">
                                    {{ $item->name }}
                                </a>
                            </h2>   
                            
                            {{-- Giá sản phẩm --}}
                            <div class="product-sidebar-price">
                                <ins>{{ number_format($item->price) }} VNĐ</ins>
                            </div>
                        </div>
                        @endforeach
                        {{-- Kết thúc vòng lặp --}}
                    </div>

                    <div class="single-sidebar">
                        <h2 class="sidebar-title">Recent Posts</h2>
                        <ul>
                            @foreach($recent_posts as $post)
                                <li>
                                    {{-- Hiển thị tên sản phẩm, click vào sẽ load lại trang hoặc sang chi tiết --}}
                                    <a href="#">{{ $post->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="product-content-right">
                        <div class="woocommerce">
                            <form method="post" action="#">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                   <tbody>
                                        {{-- Khởi tạo biến tổng tiền = 0 --}}
                                        @php $total = 0; @endphp

                                        {{-- KIỂM TRA: Nếu giỏ hàng có dữ liệu --}}
                                        @if(session('cart'))
                                            {{-- VÒNG LẶP: Duyệt qua từng sản phẩm trong giỏ --}}
                                            @foreach(session('cart') as $id => $details)
                                                @php 
                                                    // Tính thành tiền từng món (Giá x Số lượng)
                                                    $subtotal = $details['price'] * $details['quantity']; 
                                                    // Cộng dồn vào tổng tiền đơn hàng
                                                    $total += $subtotal;
                                                @endphp

                                                <tr class="cart_item">
                                                   {{-- Tìm dòng này --}}
                                                    <td class="product-remove">
                                                        <a title="Xóa sản phẩm này" class="remove" href="{{ route('delete_cart', $id) }}">×</a>
                                                    </td>

                                                    <td class="product-thumbnail">
                                                        <a href="#">
                                                            {{-- Hiển thị ảnh từ thư mục uploads --}}
                                                            <img width="145" height="145" alt="{{ $details['name'] }}" class="shop_thumbnail" src="{{ asset('uploads/' . $details['image']) }}">
                                                        </a>
                                                    </td>

                                                    <td class="product-name">
                                                        <a href="#">{{ $details['name'] }}</a> 
                                                    </td>

                                                    <td class="product-price">
                                                        <span class="amount">{{ number_format($details['price']) }} VNĐ</span> 
                                                    </td>

                                                    <td class="product-quantity">
                                                        <div class="quantity buttons_added">
                                                            <input type="button" class="minus" value="-">
                                                            {{-- Hiển thị số lượng hiện tại --}}
                                                            <input type="number" size="4" class="input-text qty text" title="Qty" value="{{ $details['quantity'] }}" min="1" step="1">
                                                            <input type="button" class="plus" value="+">
                                                        </div>
                                                    </td>

                                                    <td class="product-subtotal">
                                                        <span class="amount">{{ number_format($subtotal) }} VNĐ</span> 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            {{-- Nếu giỏ hàng trống --}}
                                            <tr>
                                                <td colspan="6" class="text-center" style="padding: 20px; font-weight: bold;">
                                                    Giỏ hàng của bạn đang trống! <a href="/shop">Mua sắm ngay</a>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Phần nút bấm cập nhật / thanh toán (Giữ nguyên) --}}
                                        <tr>
                                            <td class="actions" colspan="6">
                                                <div class="coupon">
                                                    <label for="coupon_code">Coupon:</label>
                                                    <input type="text" placeholder="Coupon code" value="" id="coupon_code" class="input-text" name="coupon_code">
                                                    <input type="submit" value="Apply Coupon" name="apply_coupon" class="button">
                                                </div>
                                                <input type="submit" value="Update Cart" name="update_cart" class="button">
                                                {{-- Sửa link Checkout --}}
                                                <a href="/checkout" class="checkout-button button alt wc-forward">Thanh toán</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                            <div class="cart-collaterals">


                            <div class="cross-sells">
                                <h2>You may be interested in...</h2>
                                <ul class="products">
                                    @foreach($products_interested as $item)
                                    <li class="product">
                                        <a href="#">
                                            <img width="325" height="325" alt="{{ $item->name }}" class="attachment-shop_catalog wp-post-image" src="{{ asset('uploads/' . $item->image) }}">
                                            
                                            {{-- [CHỈNH SỬA Ở ĐÂY] Thêm style min-height để tên sản phẩm luôn cao bằng nhau (khoảng 2 dòng) --}}
                                            <h3 style="min-height: 40px; line-height: 1.4em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                {{ $item->name }}
                                            </h3>

                                            <span class="price"><span class="amount">{{ number_format($item->price) }} VNĐ</span></span>
                                        </a>

                                        {{-- Nút này bây giờ sẽ thẳng hàng --}}
                                        <a class="add_to_cart_button" href="{{ route('add_to_cart', $item->id) }}" rel="nofollow">Thêm vào giỏ</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
 

                            <div class="cart_totals ">
                                <h2>Cart Totals</h2>

                                <table cellspacing="0">
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Tạm tính</th>
                                            <td><span class="amount">{{ number_format($total) }} VNĐ</span></td>
                                        </tr>

                                        <tr class="shipping">
                                            <th>Phí vận chuyển</th>
                                            <td>Miễn phí</td>
                                        </tr>

                                        <tr class="order-total">
                                            <th>Tổng cộng</th>
                                            <td><strong><span class="amount">{{ number_format($total) }} VNĐ</span></strong> </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <form method="post" action="#" class="shipping_calculator">
                                <h2><a class="shipping-calculator-button" data-toggle="collapse" href="#calcalute-shipping-wrap" aria-expanded="false" aria-controls="calcalute-shipping-wrap">Calculate Shipping</a></h2>

                                <section id="calcalute-shipping-wrap" class="shipping-calculator-form collapse">

                                <p class="form-row form-row-wide">
                                <select rel="calc_shipping_state" class="country_to_state" id="calc_shipping_country" name="calc_shipping_country">
                                    <option value="">Select a country…</option>
                                    <option value="AX">Åland Islands</option>
                                    <option value="AF">Afghanistan</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="PW">Belau</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia</option>
                                    <option value="BQ">Bonaire, Saint Eustatius and Saba</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="VG">British Virgin Islands</option>
                                    <option value="BN">Brunei</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo (Brazzaville)</option>
                                    <option value="CD">Congo (Kinshasa)</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">CuraÇao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="CI">Ivory Coast</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Laos</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao S.A.R., China</option>
                                    <option value="MK">Macedonia</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia</option>
                                    <option value="MD">Moldova</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="KP">North Korea</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PS">Palestinian Territory</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="QA">Qatar</option>
                                    <option value="IE">Republic of Ireland</option>
                                    <option value="RE">Reunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russia</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="ST">São Tomé and Príncipe</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="SX">Saint Martin (Dutch part)</option>
                                    <option value="MF">Saint Martin (French part)</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="SM">San Marino</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia/Sandwich Islands</option>
                                    <option value="KR">South Korea</option>
                                    <option value="SS">South Sudan</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syria</option>
                                    <option value="TW">Taiwan</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option selected="selected" value="GB">United Kingdom (UK)</option>
                                    <option value="US">United States (US)</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VA">Vatican</option>
                                    <option value="VE">Venezuela</option>
                                    <option value="VN">Vietnam</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="WS">Western Samoa</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
                                </p>

                                <p class="form-row form-row-wide"><input type="text" id="calc_shipping_state" name="calc_shipping_state" placeholder="State / county" value="" class="input-text"> </p>

                                <p class="form-row form-row-wide"><input type="text" id="calc_shipping_postcode" name="calc_shipping_postcode" placeholder="Postcode / Zip" value="" class="input-text"></p>


                                <p><button class="button" value="1" name="calc_shipping" type="submit">Update Totals</button></p>

                                </section>
                            </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Dán đoạn này vào cuối file cart.blade.php --}}

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





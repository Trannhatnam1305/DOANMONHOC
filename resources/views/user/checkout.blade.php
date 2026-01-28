@extends('layout.user_layout')

@section('main')
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    @if(isset($cartItems) && count($cartItems) > 0)
        {{-- URL được trỏ trực tiếp đến route xử lý đặt hàng --}}
        <form action="{{ url('/place-order') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <h3 style="margin-bottom: 25px; font-weight: bold;">Thông tin giao hàng</h3>
                    
                    <div class="form-group mb-4">
                        <label style="font-weight: 600;">Họ và tên</label>
                        <input type="text" name="full_name" 
                            class="form-control form-control-lg" 
                            style="padding: 18px; font-size: 1.2rem; border-radius: 8px;"
                            value="{{ $user->name }}" required> 
                            </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: 600;">Số điện thoại</label>
                        <input type="text" name="phone" 
                            class="form-control form-control-lg" 
                            style="padding: 18px; font-size: 1.2rem; border-radius: 8px;"
                            value="{{ $user->phone }}" required> 
                            </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: 600;">Địa chỉ nhận hàng</label>
                        <textarea name="address" 
                                class="form-control" 
                                style="padding: 15px; font-size: 1.1rem; border-radius: 8px;" 
                                rows="3" required>{{ $user->address }}</textarea>
                                </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: 600;">Ghi chú (không bắt buộc)</label>
                        <textarea name="note" 
                                class="form-control" 
                                style="padding: 15px; font-size: 1rem; border-radius: 8px;" 
                                rows="2" placeholder="Lưu ý cho người giao hàng..."></textarea>
                    </div>
                </div>

                <div class="col-md-5" style="background: #f9f9f9; padding: 25px; border-radius: 10px; border: 1px solid #eee; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <h3 style="margin-bottom: 20px; font-weight: bold;">Đơn hàng của bạn</h3>
                    <table class="table" style="background: transparent;">
                        @foreach($cartItems as $item)
                        <tr>
                            <td style="font-size: 0.95em; border-top: 1px solid #eee; padding: 12px 0;">
                                <strong style="color: #333;">{{ $item->name }}</strong> <br>
                                <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                            </td>
                            <td class="text-right" style="vertical-align: middle; border-top: 1px solid #eee; font-weight: 500;">
                                {{ number_format($item->quantity * $item->current_price, 0, ',', '.') }} VNĐ
                            </td>
                        </tr>
                        @endforeach
                        
                        <tr style="font-weight: bold; font-size: 1.3em; color: #d9534f; border-top: 2px solid #ddd;">
                            <td style="padding-top: 20px;">Tổng cộng</td>
                            <td class="text-right" style="padding-top: 20px;">
                                {{ number_format($totalAll, 0, ',', '.') }} VNĐ
                                </td>
                        </tr>
                    </table>
                    
                    <button type="submit" class="btn btn-primary btn-block" 
                            style="background-color: #1abc9c; border: none; padding: 15px; font-weight: bold; font-size: 1.1rem; border-radius: 8px; margin-top: 20px; transition: 0.3s;">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>
                </div>
            </div>
        </form>
    @else
        <div class="alert alert-warning text-center" style="padding: 40px; border-radius: 10px;">
            <h4 style="font-weight: bold;">Giỏ hàng của bạn đang trống!</h4>
            <p style="margin-bottom: 25px;">Vui lòng chọn sản phẩm trước khi thanh toán.</p>
            <a href="{{ url('/') }}" class="btn btn-primary" style="padding: 10px 30px; font-weight: 600;">
                Tiếp tục mua sắm
            </a>
        </div>
    @endif
</div>
@endsection
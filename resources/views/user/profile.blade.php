@extends('layout.user_layout')

@section('main')
<style>
    .profile-wrapper {
        background: #f4f7f6;
        padding: 50px 0;
        min-height: 80vh;
        display: flex;
        justify-content: center;
    }
    
    .profile-box {
        background: #ffffff;
        width: 100%;
        max-width: 850px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #eee;
    }

    .profile-header {
        background: #0056b3;
        color: white;
        padding: 25px 35px;
        border-radius: 15px 15px 0 0;
    }

    .profile-body {
        padding: 40px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .full-width { grid-column: span 2; }

    .form-group label {
        display: block;
        font-weight: 700;
        margin-bottom: 10px;
        color: #444;
        font-size: 14px;
        text-transform: uppercase;
    }

    .custom-input, .custom-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        transition: 0.3s;
        box-sizing: border-box;
        background-color: #fafafa;
    }

    .custom-input:focus, .custom-select:focus {
        border-color: #007bff;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(0,123,255,0.1);
    }

    .btn-save {
        background: #1abc9c;
        color: white;
        border: none;
        padding: 16px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: 0.3s;
        text-transform: uppercase;
    }

    .btn-save:hover {
        background: #16a085;
        transform: translateY(-2px);
    }

    @media (max-width: 650px) {
        .form-grid { grid-template-columns: 1fr; }
        .full-width { grid-column: span 1; }
    }
</style>

<div class="profile-wrapper">
    <div class="profile-box">
        <div class="profile-header">
            <h5><i class="fas fa-user-edit"></i> CHỈNH SỬA THÔNG TIN CÁ NHÂN</h5>
        </div>
        
        <div class="profile-body">
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #c3e6cb;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="name" class="custom-input" value="{{ $user->name }}" placeholder="Ví dụ: Nguyễn Văn A">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="custom-input" value="{{ $user->email }}" placeholder="name@example.com">
                    </div>

                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" name="birthday" class="custom-input" value="{{ $user->birthday }}">
                    </div>

                    <div class="form-group">
                        <label>Giới tính</label>
                        <select name="gender" class="custom-select">
                            <option value="">Chọn giới tính</option>
                            <option value="0" {{ $user->gender == '0' ? 'selected' : '' }}>Nam</option>
                            <option value="1" {{ $user->gender == '1' ? 'selected' : '' }}>Nữ</option>
                            <option value="2" {{ $user->gender == '2' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="custom-input" value="{{ $user->phone }}" placeholder="09xx xxx xxx">
                    </div>

                    <div class="form-group full-width">
                        <label>Địa chỉ thường trú</label>
                        <input type="text" name="address" class="custom-input" value="{{ $user->address }}" placeholder="Số nhà, tên đường, phường/xã...">
                    </div>

                    <div class="form-group full-width">
                        <label>Mật khẩu mới (Nếu cần đổi)</label>
                        <input type="password" name="password" class="custom-input" placeholder="Nhập mật khẩu mới">
                        <p style="font-size: 12px; color: #888; margin-top: 5px;">Bỏ trống nếu bạn muốn giữ mật khẩu cũ.</p>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    CẬP NHẬT THÔNG TIN
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
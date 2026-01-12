@extends('layout.blank')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/ProductForm.css') }}">
    <style>
        .form-container { max-width: 600px; margin: 30px auto; padding: 25px; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .error-msg { color: #e74a3b; font-size: 13px; font-weight: bold; margin-top: 5px; display: block; }
        .form-group { margin-bottom: 20px; }
        label { font-weight: bold; color: #4e73df; }
    </style>
@endsection

@section('admin')
<div class="form-container">
    <h2 style="text-align: center; color: #333; margin-bottom: 25px;">
        <i class="fas fa-user-shield"></i> Tạo Quản Trị Viên Mới
    </h2>

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Tên đăng nhập (Username):</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="VD: nguyenvan_admin">
            @error('username') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@example.com">
            @error('email') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Nhập số điện thoại riêng biệt">
            @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Mật khẩu:</label>
            <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự">
            @error('password') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px; font-weight: bold;">
                <i class="fas fa-save"></i> Tạo tài khoản
            </button>
            <a href="{{ route('admin.user.create') }}" class="btn btn-secondary" style="flex: 1; padding: 12px; text-align: center; text-decoration: none;">
                Hủy bỏ
            </a>
        </div>
    </form>
</div>
@endsection
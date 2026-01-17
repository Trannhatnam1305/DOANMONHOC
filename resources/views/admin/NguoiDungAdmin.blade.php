@extends('layout.blank')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/UserManage.css') }}">
    <style>
        /* CSS cho nút bấm */
        .btn-sm { padding: 4px 8px; font-size: 12px; margin: 2px; border-radius: 3px; border: none; cursor: pointer; color: white; transition: 0.2s;}
        .btn-warning { background-color: #f6c23e; } /* Màu vàng nút Khóa */
        .btn-success { background-color: #1cc88a; } /* Màu xanh nút Mở */
        .btn-warning:hover { background-color: #dfae2e; }
        .btn-success:hover { background-color: #169b6b; }
        
        .text-muted { font-style: italic; color: #858796; font-size: 12px;}
        
        /* --- CSS MỚI: Badge hiển thị trạng thái --- */
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; color: white; display: inline-block;}
        .bg-active { background-color: #1cc88a; } /* Nền xanh */
        .bg-locked { background-color: #e74a3b; } /* Nền đỏ */
    </style>
@endsection

@section('admin')
    <div class="container">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #333;"><i class="fas fa-users-cog"></i> Quản lý người dùng</h2>
            
            {{-- Chỉ Admin Cấp Cao (Role 2) mới thấy nút tạo --}}
            @if(Auth::check() && Auth::user()->role == 2)
                <a href="{{ route('admin.user.create') }}" class="create-button">
                    <i class="fas fa-user-plus"></i> Tạo mới quản trị viên
                </a>
            @endif
        </div>

        {{-- HIỂN THỊ THÔNG BÁO (SUCCESS / ERROR) --}}
        @if(session('status'))
            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                {{ session('error') }}
            </div>
        @endif

        <table class="user-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fc; border-bottom: 2px solid #e3e6f0;">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Quyền</th>
                    <th class="text-center">Trạng Thái</th> {{-- Cột mới thêm --}}
                    <th class="text-center">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dsNguoiDung as $ds)
                    <tr style="border-bottom: 1px solid #e3e6f0;">
                        <td>{{ $ds->id }}</td>
                        <td>{{ $ds->username }}</td>
                        <td>{{ $ds->email }}</td>
                        <td>
                            @if($ds->role == 2) <b style="color: red;">Admin Cấp Cao</b>
                            @elseif($ds->role == 1) <b style="color: blue;">Quản trị viên</b>
                            @else User
                            @endif
                        </td>
                        
                        {{-- 1. CỘT TRẠNG THÁI (MỚI) --}}
                        <td class="text-center">
                            {{-- Mặc định coi như 1 (Active) nếu null --}}
                            @if(($ds->status ?? 1) == 1)
                                <span class="badge bg-active">Hoạt động</span>
                            @else
                                <span class="badge bg-locked">Đang khóa</span>
                            @endif
                        </td>

                        {{-- 2. CỘT HÀNH ĐỘNG --}}
                        <td class="text-center">
                            @if(Auth::check())
                                @php 
                                    $me = Auth::user(); 
                                    $canAction = false;

                                    // Logic kiểm tra quyền:
                                    // Không được thao tác với chính mình
                                    if($ds->id != $me->id) {
                                        // Admin cấp cao (2) thao tác được tất cả
                                        if($me->role == 2) {
                                            $canAction = true;
                                        } 
                                        // Admin thường (1) chỉ thao tác với User (0)
                                        elseif($me->role == 1 && $ds->role == 0) {
                                            $canAction = true;
                                        }
                                    }
                                @endphp

                                @if($ds->id == $me->id)
                                    <span class="text-muted">(Tài khoản của bạn)</span>
                                @elseif($canAction)
                                    {{-- FORM POST ĐỂ GỬI YÊU CẦU KHÓA/MỞ --}}
                                    {{-- Route này phải khớp với route bạn định nghĩa trong web.php --}}
                                    <form action="{{ route('admin.toggleUser', $ds->id) }}" method="POST">
                                        @csrf 
                                        @if(($ds->status ?? 1) == 1)
                                            {{-- Nếu đang mở -> Hiện nút Khóa --}}
                                            <button type="submit" class="btn-sm btn-warning" onclick="return confirm('Bạn có chắc muốn KHÓA tài khoản này?')">
                                                <i class="fas fa-lock"></i> Khóa
                                            </button>
                                        @else
                                            {{-- Nếu đang khóa -> Hiện nút Mở --}}
                                            <button type="submit" class="btn-sm btn-success" onclick="return confirm('Bạn muốn MỞ LẠI tài khoản này?')">
                                                <i class="fas fa-lock-open"></i> Mở
                                            </button>
                                        @endif
                                    </form>
                                @else
                                    <span style="color: #ccc;">-</span>
                                @endif
                            @else
                                <span style="color: red; font-size: 11px;">Chưa đăng nhập</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@extends('layout.blank')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/UserManage.css') }}">
    <style>
        .btn-sm { padding: 4px 8px; font-size: 12px; margin: 2px; text-decoration: none; display: inline-block; border-radius: 3px; }
        .btn-warning { background-color: #f6c23e; color: white; }
        .btn-success { background-color: #1cc88a; color: white; }
        .text-muted { font-style: italic; color: #858796; font-weight: bold; }
    </style>
@endsection

@section('admin')
    <div class="container">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #333;"><i class="fas fa-users-cog"></i> Quản lý người dùng</h2>
            
            {{-- SỬA LỖI: Thêm Auth::check() để tránh lỗi "role on null" --}}
            
            @if(Auth::check() && Auth::user()->role == 2)
                <a href="{{ route('admin.user.create') }}" class="create-button">
                    <i class="fas fa-user-plus"></i> Tạo mới quản trị viên
                </a>
            @endif
        </div>

        <table class="user-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8f9fc; border-bottom: 2px solid #e3e6f0;">
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Quyền</th>
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
                        <td class="text-center">
                            {{-- KIỂM TRA ĐĂNG NHẬP --}}
                            @if(Auth::check())
                                @php $me = Auth::user(); @endphp

                                {{-- 1. Nếu là chính mình --}}
                                @if($ds->id == $me->id)
                                    <span class="text-muted" style="font-size: 12px;">(Tài khoản của bạn)</span>

                                {{-- 2. Nếu là Admin Cấp Cao (Role 2) --}}
                                @elseif($me->role == 2)
                                    <a href="{{ route('admin.user.status', ['id' => $ds->id, 'status' => ($ds->status ?? 1) == 1 ? 0 : 1]) }}" 
                                    class="btn-sm {{ ($ds->status ?? 1) == 1 ? 'btn-warning' : 'btn-success' }}">
                                        {{ ($ds->status ?? 1) == 1 ? 'Khóa' : 'Mở' }}
                                    </a>

                                {{-- 3. Nếu là Quản trị viên (Role 1) và đối tượng là User (Role 0) --}}
                                @elseif($me->role == 1 && $ds->role == 0)
                                    <a href="{{ route('admin.user.status', ['id' => $ds->id, 'status' => ($ds->status ?? 1) == 1 ? 0 : 1]) }}" 
                                    class="btn-sm {{ ($ds->status ?? 1) == 1 ? 'btn-warning' : 'btn-success' }}">
                                        {{ ($ds->status ?? 1) == 1 ? 'Khóa' : 'Mở' }}
                                    </a>
                                
                                {{-- 4. Không đủ quyền --}}
                                @else
                                    <span style="color: #ccc;">-</span>
                                @endif
                            
                            @else
                                {{-- NẾU CHƯA ĐĂNG NHẬP SẼ HIỆN DÒNG NÀY --}}
                                <span style="color: red; font-weight: bold; font-size: 11px;">Bạn chưa đăng nhập</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
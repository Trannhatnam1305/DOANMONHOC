@extends('layout.blank')
@section('css')
    <!-- CSS SẢN PHẨM-->
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
@endsection

@section('admin')
    <div class="container">
        <div class="header">
            <a href="{{ route('admin.addNhaCungCap') }}" style="text-decoration: none;">
                <button class="create-button">
                    <span class="icon">+</span> Tạo mới
                </button>
            </a>
        </div>
    </div>
        <table class="product-table">
            <thead class="the">
                <tr>
                    <th>ID</th>
                    <th>Tên Công Ty</th>
                    <th>Người Liên Hệ</th>
                   <th>Số Điện Thoại</th>
                   <th>Email</th>
                   <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dsNhacungcap as $ds)
                    <tr>
                        <td>{{ $ds->id }}</td>
                        <td>{{ $ds->name }}</td>
                        <td>{{ $ds->contact_person }}</td>
                        <td>{{ $ds->phone }}</td>
                       <td>{{ $ds->email }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-button edit-btn">✏️</button>
                                <button class="action-button delete-btn">🗑️</button>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection

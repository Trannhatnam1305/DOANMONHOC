@extends('layout.blank')
@section('css')
    <!-- CSS SẢN PHẨM-->
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
@endsection
@section('admin')
            <div class="container">
    <div class="header" style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
        <a href="{{ route('admin.them-loaisanpham') }}" style="text-decoration: none;">
            <button class="create-button" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <span class="icon" style="font-weight: bold; font-size: 1.2rem;">+</span> 
                Tạo loại sản phẩm mới
            </button>
        </a>
        </div>
    </div>

        <table class="product-table">
            <thead class="the">
                <tr>
                    <th>ID</th>
                    <th>Tên Loại Sản Phẩm</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dsLoaisanpham as $ds)
                    <tr>
                        <td>{{ $ds->id }}</td>
                        <td>{{ $ds->name }}</td>
                        <td>{{ $ds->status }}</td>
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

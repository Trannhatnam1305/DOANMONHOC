@extends('layout.blank')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ProductManage.css') }}">
@endsection

@section('admin')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('admin.addProduct') }}" class="btn-create" style="background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                <i class="fas fa-plus"></i> Tạo mới sản phẩm
            </a>

            <form action="{{ route('admin.sanpham') }}" method="GET" class="search-box">
                <input type="text" name="keyword" placeholder="Tìm tên sản phẩm..." value="{{ request()->keyword }}" style="padding: 8px 15px; width: 300px; border: 1px solid #ddd; border-radius: 4px 0 0 4px;">
                <button type="submit" style="padding: 8px 15px; background: #333; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div style="overflow-x: auto; background: white; border-radius: 8px;">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá Gốc</th>
                        <th>Giá Giảm</th>
                        <th>Mô tả</th>
                        <th>Hình ảnh</th>
                        <th>Loại SP</th>
                        <th>Phân loại</th>
                        <th>Tag</th>
                        <th>Trạng thái</th>
                        <th>Thương hiệu</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sanpham as $sp)
                        <tr>
                            <td>{{ $sp->id }}</td>
                            <td class="name-cell">{{ $sp->name }}</td>
                            <td class="price-text">{{ number_format($sp->price, 0, ',', '.') }}đ</td>
                            <td class="discount-text">{{ number_format($sp->discount_price, 0, ',', '.') }}đ</td>
                            <td>
                                <div class="description-cell" title="{{ $sp->description }}">
                                    {{ $sp->description }}
                                </div>
                            </td>
                            <td>
                                @if($sp->image)
                                    <img src="{{ asset('uploads/'.$sp->image) }}" class="img-admin">
                                @else
                                    <small style="color: #ccc;">No image</small>
                                @endif
                            </td>
                            <td><span class="badge" style="background: #333; padding: 3px 8px; border-radius: 10px;">ID: {{ $sp->category_id }}</span></td>
                            <td>{{ $sp->loai == 1 ? '🔥 Bán chạy' : '✨ Mới' }}</td>
                            <td><small class="text-muted">{{ $sp->tags }}</small></td>
                            <td>
                                @if($sp->status == 1)
                                    <span style="color: #1cc88a; font-weight: bold;">● Hiển thị</span>
                                @else
                                    <span style="color: #e74a3b; font-weight: bold;">● Ẩn</span>
                                @endif
                            </td>
                            <td>{{ $sp->brand_id }}</td>
                            <td>
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('admin.edit-product', $sp->id) }}" class="btn-edit" style="color: #4e73df;" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.sanpham.xoa', $sp->id) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #e74a3b; cursor: pointer;" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper" style="display: flex; justify-content: center; padding: 20px;">
                {{ $sanpham->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
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

        <div style="overflow-x: auto; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <table class="product-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fc;">
                        <th>ID</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá Gốc</th>
                        <th>Kho (Mục 11)</th> <th>Xem (Mục 17)</th> <th>Hình ảnh</th>
                        <th>Loại SP</th>
                        <th>Phân loại</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sanpham as $sp)
                        <tr>
                            <td>{{ ($sanpham->currentPage() - 1) * $sanpham->perPage() + $loop->iteration }}</td>
                            <td class="name-cell"><strong>{{ $sp->name }}</strong></td>
                            <td class="price-text">{{ number_format($sp->price, 0, ',', '.') }}đ</td>
                            
                            <td>
                                @if($sp->stock_quantity <= 5)
                                    <span class="badge" style="background: #e74a3b; color: white; padding: 5px 10px;">{{ $sp->stock_quantity }} (Sắp hết)</span>
                                @else
                                    <span class="badge" style="background: #1cc88a; color: white; padding: 5px 10px;">{{ $sp->stock_quantity }}</span>
                                @endif
                            </td>

                            <td>
                                <span style="color: #4e73df;"><i class="fas fa-eye"></i> {{ number_format($sp->views) }}</span>
                            </td>

                            <td>
                                @if($sp->image)
                                    <img src="{{ asset('storage/'.$sp->image) }}" class="img-admin" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <small style="color: #ccc;">No image</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: #333; color: white; padding: 4px 10px; border-radius: 10px;">
                                    {{ $sp->category_name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td>{{ $sp->loai == 1 ? '🔥 Bán chạy' : '✨ Mới' }}</td>
                            <td>
                                @if($sp->status == 1)
                                    <span style="color: #1cc88a; font-weight: bold;">● Hiện</span>
                                @else
                                    <span style="color: #e74a3b; font-weight: bold;">● Ẩn</span>
                                @endif
                            </td>
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
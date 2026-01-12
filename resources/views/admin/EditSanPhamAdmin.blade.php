<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin/Editproduct.css') }}">
    <title>Sửa Sản Phẩm</title>
    <style>
        .text-danger { color: #e74a3b; font-size: 0.85rem; margin-top: 5px; display: block; }
        .form-control.is-invalid { border-color: #e74a3b; }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="edit-card">
            <h3>Sửa sản phẩm</h3>
            {{-- Đừng quên enctype="multipart/form-data" nếu bạn có upload ảnh --}}
            <form action="{{ route('admin.update-product', $sanpham->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $sanpham->id }}">

                <div class="form-section">
                    {{-- Tên sản phẩm --}}
                    <div>
                        <label>Tên sản phẩm:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $sanpham->name) }}">
                        @error('name')
                            <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label>Giá gốc:</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $sanpham->price) }}">
                        @error('price')
                            <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Giá giảm (Cho phép trống trong Controller, không cần báo lỗi bắt buộc) --}}
                    <div>
                        <label>Giá giảm:</label>
                        <input type="number" name="discount_price" class="form-control"
                               value="{{ old('discount_price', $sanpham->discount_price) }}">
                        @error('discount_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Loại sản phẩm --}}
                    <div>
                        <label>ID Loại sản phẩm:</label>
                        <input type="text" name="category_id" class="form-control" value="{{ old('category_id', $sanpham->category_id) }}">
                        @error('category_id')
                            <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Thương hiệu --}}
                    <div>
                        <label>ID Thương hiệu:</label>
                        <input type="text" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror" 
                               value="{{ old('brand_id', $sanpham->brand_id) }}">
                        @error('brand_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Cột Loại (Phân loại hiển thị) --}}
                    <div>
                        <label>Loại (0: Mới, 1: Bán chạy):</label>
                        <input type="number" name="loai" class="form-control @error('loai') is-invalid @enderror" 
                               value="{{ old('loai', $sanpham->loai) }}">
                        @error('loai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tag --}}
                    <div>
                        <label>Tag:</label>
                        <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" 
                               value="{{ old('tags', $sanpham->tags) }}">
                        @error('tags')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div>
                        <label>Trạng thái:</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $sanpham->status) == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('status', $sanpham->status) == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="full-width">
                        <label>Mô tả:</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4">{{ old('description', $sanpham->description) }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <div class="full-width">
                        <label>Thay đổi hình ảnh (để trống nếu giữ nguyên):</label>
                        <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('admin.sanpham') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
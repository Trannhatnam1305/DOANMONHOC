<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/admin/Editproduct.css') }}">
    <title>Sửa Sản Phẩm</title>
</head>

<body>
    <div class="container-fluid mt-4">

        <div class="edit-card">
            <h3> Sửa sản phẩm</h3>
            <form action="{{ route('admin.update-product', $sanpham->id) }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $sanpham->id }}">

                <div class="form-section">
                    <div>
                        <label>Tên sản phẩm:</label>
                        <input type="text" name="name" class="form-control" value="{{ $sanpham->name }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label>Giá gốc:</label>
                        <input type="number" name="price" class="form-control" value="{{ $sanpham->price }}">
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label>Giá giảm:</label>
                        <input type="number" name="discount_price" class="form-control"
                            value="{{ $sanpham->discount_price }}">
                        @error('discount_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label>Loại sản phẩm:</label>
                        <select name="category_id" class="form-select">
                            @foreach ($dsLoai as $loai)
                                <option value="{{ $loai->id }}" {{ $loai->id == $sanpham->category_id ? 'selected' : '' }}>
                                    {{ $loai->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Thương hiệu:</label>
                        <select name="brand_id" class="form-select">
                            @foreach ($dsThuongHieu as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $sanpham->brand_id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Nhà cung cấp:</label>
                        <select name="supplier_id" class="form-select">
                            @foreach ($dsNhaCungCap as $sup)
                                <option value="{{ $sup->id }}" {{ $sup->id == $sanpham->supplier_id ? 'selected' : '' }}>
                                    {{ $sup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Loại:</label>
                        <input type="number" name="loai" class="form-control" value="{{ $sanpham->loai }}">
                        @error('loai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label>Tag:</label>
                        <input type="text" name="tags" class="form-control" value="{{ $sanpham->tags }}">
                        @error('tags')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label>Trạng thái:</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $sanpham->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $sanpham->status == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="full-width">
                        <label>Mô tả:</label>
                        <textarea name="description" class="form-control"
                            rows="4">{{ $sanpham->description }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <div class="full-width">
                        <label>Hình ảnh (tên file):</label>
                        <input type="file" id="image" name="image" class="form-control">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="/admin/sanpham" class="btn btn-secondary"> Quay lại</a>
                    <button type="submit" class="btn btn-primary"> Cập nhật</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>
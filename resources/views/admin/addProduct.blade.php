<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/admin/addProduct.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
   
  
<body>
    <form action="/admin/addsanpham" method="post" enctype="multipart/form-data">
        @csrf
        <h2><i class="fas fa-box-open"></i> Thêm sản phẩm mới</h2>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Dell XPS 13">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="form-group">
                <label for="price">Giá gốc (Tối đa 999.999.999):</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" max="999999999">
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div> 

            <div class="form-group">
                <label for="discount_price">Giá Giảm:</label>
                <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', 0) }}" max="999999999">
            </div>


            <div class="form-group">
                <label for="category_id">Loại Sản Phẩm:</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">-- Chọn loại sản phẩm --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger" style="color:red">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="brand_id">Thương Hiệu:</label>
                <select id="brand_id" name="brand_id" class="form-control">
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id') <small class="text-danger" style="color:red">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="loai">Phân loại (0: Mới, 1: Bán chạy):</label>
                <input type="number" id="loai" name="loai" value="{{ old('loai') }}" placeholder="0 hoặc 1">
                @error('loai') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags') }}" placeholder="laptop, gaming,...">
            </div>

            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select name="status">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị trên web</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn sản phẩm</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="supplier_id">Nhà Cung Cấp:</label>
                <select name="supplier_id" id="supplier_id">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }}
                        </option>
                    @endforeach
                        </select>
                        @error('supplier_id') 
                            <small class="text-danger" style="color: red;">{{ $message }}</small> 
                        @enderror
            </div>
            <div class="form-group full-width">
                <label for="description">Mô tả sản phẩm:</label>
                <textarea id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group full-width">
                <label for="image">Hình ảnh sản phẩm:</label>
                <input type="file" id="image" name="image">
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-add"><i class="fas fa-save"></i> Lưu dữ liệu</button>
            <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.sanpham') }}'">Hủy bỏ</button>
        </div>
    </form>
</body>
</html>
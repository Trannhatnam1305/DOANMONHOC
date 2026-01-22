<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Loại Sản Phẩm</title>
    <link rel="stylesheet" href="{{ asset('css/admin/addNhaCungCap.css') }}"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="supplier-container">
        <form action="{{ route('admin.luu-loaisanpham') }}" method="POST">
            @csrf
            <h2><i class="fas fa-list"></i> Thêm Loại Sản Phẩm Mới</h2>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name">Tên Loại Sản Phẩm:</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Laptop, Điện thoại...">
                    @error('name') <small class="text-danger" style="color:red">{{ $message }}</small> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="status">Trạng Thái:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn (Khóa)</option>
                    </select>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.loaisanpham') }}'">Hủy bỏ</button>
                <button type="submit" class="btn btn-add">Lưu loại sản phẩm</button>
            </div>
        </form>
    </div>
</body>
</html>
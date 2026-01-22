<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhà Cung Cấp</title>
    <link rel="stylesheet" href="{{ asset('css/admin/addNhaCungCap.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' https://cdnjs.cloudflare.com; object-src 'none';">
</head>
   
<body>
    <div class="supplier-container">
        <form action="{{ route('admin.luu-nhacungcap') }}" method="post">
            @csrf
            
            <div class="form-header">
                <h2><i class="fas fa-truck-moving"></i> Thêm Nhà Cung Cấp Mới</h2>
                <p>Vui lòng điền đầy đủ thông tin bên dưới để đăng ký nhà cung cấp mới vào hệ thống.</p>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="name"><i class="fas fa-building"></i> Tên Công Ty:</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Công ty TNHH Công Nghệ Toàn Cầu">
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                    <label for="contact_person"><i class="fas fa-user-tie"></i> Người Liên Hệ:</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="VD: Nguyễn Văn A">
                    @error('contact_person') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone-alt"></i> Số Điện Thoại:</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="VD: 0987654321">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="email"><i class="fas fa-envelope"></i> Địa chỉ Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="VD: contact@congty.com">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group full-width">
                    <label for="address"><i class="fas fa-map-marker-alt"></i> Địa chỉ chi tiết:</label>
                    <textarea id="address" name="address" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện...">{{ old('address') }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='{{ route('admin.nhacungcap') }}'">
                    <i class="fas fa-times"></i> Hủy bỏ
                </button>
                <button type="submit" class="btn btn-add">
                    <i class="fas fa-save"></i> Lưu nhà cung cấp
                </button>
            </div>
        </form>
    </div>
</body>
</html>
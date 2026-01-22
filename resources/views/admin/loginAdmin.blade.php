<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="{{ asset('css/admin/loginAdmin.css') }}">
</head>
<body>
   <div class="login-container">
    <h2>Đăng nhập tài khoản</h2>

    {{-- Thông báo thành công --}}
    @if(session('status'))
        <div style="color: green; margin-bottom: 10px;">{{ session('status') }}</div>
    @endif

    {{-- Thông báo lỗi từ redirect()->with('error', ...) --}}
    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <form action="/admin/login" method="POST">
        @csrf
        <input type="text" placeholder="Nhập tài khoản *" required name="username" value="{{ old('username') }}">
        <input type="password" placeholder="Nhập mật khẩu *" required name="password">
        <button type="submit" class="btn">Đăng nhập</button>

        {{-- Hiển thị các lỗi từ Validation ($errors) --}}
        @if ($errors->any())
            <ul style="list-style-position: inside; margin-top: 10px;">
                @foreach ($errors->all() as $error)
                    <li style="color:red;">{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </form>
</div>
</body>
</html>

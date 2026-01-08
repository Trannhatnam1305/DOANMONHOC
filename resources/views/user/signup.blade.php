<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-container {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input, select {
      width: 95%;
      padding: 7.5px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      width: 100%;
      padding: 10px;
      background: #1abc9c;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
        transition: 0.5s;
    }
    .btn:hover {
       opacity: 0.75;
    }
    .social-login {
      margin: 15px 0;
      text-align: center;
    }
    .social-btn {
      display: block;
      width: 100%;
      padding: 10px;
      margin: 5px 0;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 15px;
    }
    .note {
      font-size: 13px;
      margin-top: 10px;
      color: #555;
    }
    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .login-link a {
      color: #e53935;
      text-decoration: none;
    }
    .login-link .dn:hover, .tc:hover{
        border-bottom: 1px solid black;
    }
  </style>
</head>
<body>
      <div class="register-container">
        <h2>Đăng ký tài khoản</h2>
          <form action="{{ route('postSignup') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

    <button type="submit" class="btn btn-success">Đăng ký ngay</button>
    <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
</form>
    <div class="login-link">
      Bạn đã có tài khoản? <a href="/login" class="dn">Đăng nhập ngay</a> - <a href="/" class="tc">Quay lại</a>
    </div>
  </div>
</body>
</html>

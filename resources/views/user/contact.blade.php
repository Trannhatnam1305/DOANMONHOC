@extends('layout.user_layout')

@section('main')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
        <div class="mw-930">
            <h2 class="page-header">Liên hệ với chúng tôi</h2>
        </div>
    </section>

    <hr class="mt-2 text-secondary" />
    <div class="mb-4 pb-4"></div>

    <section class="contact-us container">
        <div class="mw-930">
            <div class="contact-us__form">
                
                {{-- Hiển thị thông báo khi gửi thành công (nếu không dùng redirect về trang chủ) --}}
                @if(session('success'))
                    <div class="alert alert-success" style="color: green; background: #e9f7ef; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fa fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                {{-- Hiển thị lỗi Validation nếu có --}}
                @if ($errors->any())
                    <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <ul style="margin-bottom: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- SỬA ĐỔI QUAN TRỌNG TẠI ĐÂY: action gọi theo route name --}}
                <form id="contactForm" method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <h3 class="mb-5">Gửi tin nhắn cho chúng tôi</h3>
                    
                    <div class="form-floating my-4">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Họ tên *" value="{{ old('name') }}" required>
                        <label for="name">Họ và Tên: *</label>
                    </div>

                    <div class="form-floating my-4">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Số điện thoại *" value="{{ old('phone') }}" required>
                        <label for="phone">Số điện thoại: *</label>
                    </div>

                    <div class="form-floating my-4">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email *" value="{{ old('email') }}" required>
                        <label for="email">Email: *</label>
                    </div>

                    <div class="form-floating my-4">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Tiêu đề *" value="{{ old('title') }}" required>
                        <label for="title">Tiêu đề: *</label>
                    </div>

                    <div class="my-4">
                        <label class="mb-2" style="font-weight: bold;">Nội dung liên hệ: *</label>
                        <textarea class="form-control" name="content" id="editor" rows="8">{{ old('content') }}</textarea>
                    </div>

                    <div class="my-4">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 40px; background: #1abc9c; border: none; color: white; font-weight: bold; text-transform: uppercase;">
                            GỬI TIN NHẮN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@section('js')
{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    let editorInstance;
    
    ClassicEditor
        .create(document.querySelector('#editor'), {
            placeholder: 'Nhập nội dung chi tiết tại đây...'
        })
        .then(editor => { 
            editorInstance = editor; 
        })
        .catch(error => { 
            console.error('Lỗi CKEditor:', error); 
        });

    // Xử lý trước khi submit form
    document.getElementById('contactForm').onsubmit = function(e) {
        if(editorInstance) {
            const data = editorInstance.getData();
            // Nếu CKEditor trống, báo lỗi ngay tại client (tùy chọn)
            if(data.trim() === '') {
                alert('Vui lòng nhập nội dung liên hệ!');
                e.preventDefault();
                return false;
            }
            // Gán dữ liệu vào textarea thực tế
            document.querySelector('#editor').value = data;
        }
    };
</script>

@endsection
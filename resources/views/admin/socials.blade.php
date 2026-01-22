@extends('layout.blank')

@section('admin')
<form action="{{ route('admin.socials.update') }}" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label>Facebook URL</label>
        <input type="text" name="facebook" value="{{ $socials['facebook'] ?? '' }}" class="form-control" placeholder="Nhập link Facebook...">
    </div>

    <div class="form-group mb-3">
        <label>Twitter URL</label>
        <input type="text" name="twitter" value="{{ $socials['twitter'] ?? '' }}" class="form-control" placeholder="Nhập link Twitter...">
    </div>

    <div class="form-group mb-3">
        <label>Youtube URL</label>
        <input type="text" name="youtube" value="{{ $socials['youtube'] ?? '' }}" class="form-control" placeholder="Nhập link Youtube...">
    </div>

    <div class="form-group mb-3">
        <label>Linkedin URL</label>
        <input type="text" name="linkedin" value="{{ $socials['linkedin'] ?? '' }}" class="form-control" placeholder="Nhập link Linkedin...">
    </div>

    <div class="form-group mb-3">
        <label>Pinterest URL</label>
        <input type="text" name="pinterest" value="{{ $socials['pinterest'] ?? '' }}" class="form-control" placeholder="Nhập link Pinterest...">
    </div>

    <button type="submit" class="btn btn-success">Cập nhật ngay</button>
</form>
@endsection
@extends('layout.blank')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/Manage.css') }}">
@endsection

@section('admin')
    <div class="container">
        <div class="header-actions-trash">
            <a href="{{ route('admin.sanpham') }}" class="btn-back">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success"
                style="padding: 15px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="product-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background: #858796; color: white;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Tên Sản Phẩm</th>
                        <th style="padding: 12px; text-align: left;">Ngày Xóa</th>
                        <th style="padding: 12px; text-align: center;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dsDaXoa as $item)
                        <tr style="border-bottom: 1px solid #e3e6f0;">
                            <td style="padding: 12px;">{{ $item->id }}</td>
                            <td style="padding: 12px;">{{ $item->name }}</td>
                            <td style="padding: 12px;">{{ date('d/m/Y H:i', strtotime($item->deleted_at)) }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="{{ route('admin.sanpham.phuc-hoi', $item->id) }}" class="btn btn-success"
                                    title="Phục hồi"
                                    style="background: #1cc88a; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none;">
                                    <i class="fa fa-undo"></i> Phục hồi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #858796;">Không có dữ liệu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
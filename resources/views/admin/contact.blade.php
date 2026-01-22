@extends('layout.blank')

@section('admin')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Quản lý Liên hệ</h1>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Người gửi</th>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Ngày gửi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $item)
                <tr style="{{ $item->status == 0 ? 'font-weight:bold; background-color: #f9f9f9;' : '' }}">
                    <td>
                        {{ $item->name }} <br>
                        <small>{{ $item->phone }}</small>
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{!! Str::limit($item->content, 50) !!}</td>
                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($item->status == 0)
                            <a href="{{ route('contact.read', $item->id) }}" class="btn btn-xs btn-success">
                                <i class="fa fa-check"></i> Đánh dấu đã xem
                            </a>
                        @else
                            <span class="label label-default">Đã xem</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $contacts->links() }}
    </div>
</div>
@endsection
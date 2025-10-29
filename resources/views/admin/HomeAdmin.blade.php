@extends('layout.blank')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection
@section('admin')
<div class="container mt-5">
    <h2 class="dashboard-title"><i class="fas fa-chart-line"></i> Bảng Điều Khiển Quản Trị</h2>
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon bg-blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ $stats['users'] }}</div>
                <div class="stat-label">Người dùng</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon bg-green">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $stats['orders'] }}</div>
                <div class="stat-label">Đơn hàng</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon bg-yellow">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-value">{{ $stats['products'] }}</div>
                <div class="stat-label">Sản phẩm</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon bg-red">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">{{ number_format($stats['revenue'], 0, ',', '.') }} đ</div>
                <div class="stat-label">Doanh thu</div>
            </div>
        </div>
    </div>
</div>
@endsection

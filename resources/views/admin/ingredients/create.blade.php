@extends('layouts.admin.app')

@section('title', 'Thêm nguyên liệu')
@section('page-title', 'Thêm nguyên liệu')
@section('page-subtitle', 'Tạo nguyên liệu mới để người dùng có thể chọn khi tìm gợi ý món ăn')

@section('content')
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="admin-card-title">Tạo nguyên liệu mới</h5>
                </div>
            </div>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.ingredients.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên nguyên liệu</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Loại</label>
                    <input name="type" class="form-control" value="{{ old('type') }}" placeholder="ví dụ: rau, thịt, gia vị" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Đơn vị</label>
                    <input name="unit" class="form-control" value="{{ old('unit') }}" placeholder="ví dụ: g, kg, cái, muỗng" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả (không bắt buộc)</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-admin btn-admin-primary">Lưu</button>
                    <a href="{{ route('admin.ingredients.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

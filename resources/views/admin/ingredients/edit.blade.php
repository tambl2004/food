@extends('layouts.admin.app')

@section('title', 'Sửa nguyên liệu')
@section('page-title', 'Sửa nguyên liệu')
@section('page-subtitle', 'Chỉnh sửa nguyên liệu hiện có')

@section('content')
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="admin-card-title">Sửa nguyên liệu</h5>
                </div>
            </div>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.ingredients.update', [$ingredient->id]) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên nguyên liệu</label>
                    <input name="name" class="form-control" value="{{ old('name', $ingredient->name) }}" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Loại</label>
                    <input name="type" class="form-control" value="{{ old('type', $ingredient->type) }}" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Đơn vị</label>
                    <input name="unit" class="form-control" value="{{ old('unit', $ingredient->unit) }}" placeholder="ví dụ: g, kg, cái, muỗng" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả (không bắt buộc)</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $ingredient->description) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-admin btn-admin-primary">Lưu</button>
                    <a href="{{ route('admin.ingredients.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection

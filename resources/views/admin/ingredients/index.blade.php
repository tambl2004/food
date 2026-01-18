@extends('layouts.admin.app')

@section('title', 'Quản lý nguyên liệu')
@section('page-title', 'Quản lý nguyên liệu')
@section('page-subtitle', 'Danh sách nguyên liệu dùng cho gợi ý món ăn')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Quản lý nguyên liệu</h2>
            <p class="text-muted mb-0">Danh sách nguyên liệu dùng cho gợi ý món ăn</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.ingredients.index') }}" method="GET" class="d-flex">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm nguyên liệu..." class="form-control form-control-sm me-2" style="min-width:320px; border-radius:8px;" />
                <button class="btn btn-light btn-sm me-2" type="submit"><i class="fas fa-search"></i></button>
            </form>
            <a href="{{ route('admin.ingredients.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Thêm nguyên liệu
            </a>
        </div>
    </div>

    <div class="admin-card">
        @section('styles')
        <style>
            /* Page card + header */
            .admin-card { border-radius:12px; background:#fff; box-shadow:0 6px 22px rgba(20,30,40,0.04); }
            .admin-card-header { padding:18px 24px; border-bottom:1px solid #f1f3f5; }
            .admin-card-title { font-weight:700; color:#1f2937; }

            /* Search & buttons */
            .form-control-sm { padding:10px 14px; border-radius:8px; border:1px solid #eef2f6; }
            .btn-success { background:#22c55e; border-color:#22c55e; color:#fff; border-radius:8px; }
            .btn-success:hover { background:#16a34a; border-color:#16a34a; }

            /* Table look */
            .admin-table .table { margin-bottom:0; }
            .admin-table thead th { background:transparent; color:#6b7280; font-weight:600; border-bottom:1px solid #f1f3f5; }
            .admin-table tbody tr { background:#fff; }
            .admin-table tbody tr:nth-child(odd) { background:#fbfbfb; }
            .admin-table td, .admin-table th { vertical-align:middle; padding:18px; }

            /* Type badges palette */
            .badge-type { display:inline-block; padding:6px 10px; border-radius:999px; font-weight:600; font-size:13px; }
            .type-rau { background:#ecfdf5; color:#059669; }
            .type-thit { background:#fff1f2; color:#ef4444; }
            .type-haisan { background:#eff6ff; color:#2563eb; }
            .type-gia-vi { background:#fffbeb; color:#d97706; }
            .type-ngu-coc { background:#fff7ed; color:#f97316; }
            .type-default { background:#f3f4f6; color:#374151; }

            /* Action buttons */
            .btn-admin-outline { background:#fff; border:1px solid #e6e9ee; color:#374151; border-radius:8px; padding:8px 10px; }

            /* Pagination colors */
            .pagination .page-link { color:#16a34a; border-color:transparent; }
            .pagination .page-item.active .page-link { background:#16a34a; border-color:#16a34a; color:#fff; }
        </style>
        @endsection
        <div class="admin-card-header">
            <h5 class="admin-card-title"><i class="fas fa-leaf me-2"></i>Nguyên liệu</h5>
        </div>
        <div class="admin-card-body p-0">
            <div class="admin-table">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="border-0">No.</th>
                            <th class="border-0">Tên nguyên liệu</th>
                            <th class="border-0">Loại</th>
                            <th class="border-0">Đơn vị</th>
                            <th class="border-0">Mô tả</th>
                            <th class="border-0 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($ingredients) && $ingredients->count() > 0)
                            @foreach($ingredients as $i => $ing)
                                <tr>
                                    <td><span class="badge bg-light text-dark">#{{ $ingredients->firstItem() + $i }}</span></td>
                                    <td>{{ $ing->name }}</td>
                                    <td>
                                        @php
                                            $type = strtolower(trim($ing->type ?? ''));
                                            $typeClass = 'type-default';
                                            if (str_contains($type, 'rau')) $typeClass = 'type-rau';
                                            elseif (str_contains($type, 'thịt') || str_contains($type, 'thit')) $typeClass = 'type-thit';
                                            elseif (str_contains($type, 'hải') || str_contains($type, 'hai')) $typeClass = 'type-haisan';
                                            elseif (str_contains($type, 'gia') || str_contains($type, 'gia vị') || str_contains($type, 'gia_vi')) $typeClass = 'type-gia-vi';
                                            elseif (str_contains($type, 'ngũ') || str_contains($type, 'ngu')) $typeClass = 'type-ngu-coc';
                                        @endphp
                                        @if($ing->type)
                                            <span class="badge-type {{ $typeClass }}">{{ $ing->type }}</span>
                                        @else
                                            <span class="badge-type type-default">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $ing->unit ?? '-' }}</td>
                                    <td>{{ Str::limit($ing->description ?? '-', 80) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.ingredients.edit', $ing) }}" class="btn-admin btn-admin-outline btn-admin-sm"><i class="fas fa-edit"></i></a>
                                        <form method="POST" action="{{ route('admin.ingredients.destroy', $ing) }}" style="display:inline" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-admin btn-admin-outline btn-admin-sm ms-1" onclick="return confirm('Xác nhận xóa?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có nguyên liệu nào</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if(isset($ingredients) && method_exists($ingredients, 'links'))
            <div class="admin-card-body border-top">
                <div class="d-flex justify-content-end p-3">
                    {!! $ingredients->links() !!}
                </div>
            </div>
        @endif
    </div>
@endsection

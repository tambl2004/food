@extends('layouts.customer')

@section('title', 'Nguyên liệu của tôi')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-2">
            <i class="fas fa-utensils me-2 text-primary"></i>
            Nguyên liệu của tôi
        </h1>
        <p class="text-muted">Quản lý những gì bạn đang có trong bếp</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="customer-card mb-4">
        <div class="customer-card-body">
            <form method="GET" action="{{ route('user.ingredients.index') }}" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label small text-muted">Tìm kiếm nguyên liệu</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nhập tên nguyên liệu...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label small text-muted">Lọc theo loại</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tất cả</option>
                            @foreach($ingredientTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Button -->
    <div class="mb-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ingredientModal" onclick="openAddModal()">
            <i class="fas fa-plus me-2"></i>Thêm nguyên liệu
        </button>
    </div>

    <!-- Ingredients List -->
    @if($userIngredients->count() > 0)
        <div class="row" id="ingredientsList">
            @foreach($userIngredients as $userIngredient)
                @if($userIngredient->ingredient)
                    <div class="col-md-6 col-lg-4 mb-4 ingredient-item" 
                         data-type="{{ $userIngredient->ingredient->type ?? '' }}"
                         data-name="{{ strtolower($userIngredient->ingredient->name ?? '') }}">
                        <div class="customer-card h-100">
                            <div class="customer-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-bold">{{ $userIngredient->ingredient->name }}</h5>
                                        <span class="badge bg-secondary">{{ $userIngredient->ingredient->type ?? 'Khác' }}</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="openEditModal({{ $userIngredient->id }}, {{ $userIngredient->ingredient_id }}, {{ json_encode($userIngredient->ingredient->name) }}, {{ json_encode($userIngredient->quantity ?? '') }}, {{ json_encode($userIngredient->unit ?? '') }})">
                                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $userIngredient->id }})">
                                                    <i class="fas fa-trash me-2"></i>Xoá
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @if($userIngredient->quantity || $userIngredient->unit)
                                    <div class="mt-3">
                                        <small class="text-muted">Số lượng:</small>
                                        <p class="mb-0">
                                            @if($userIngredient->quantity)
                                                <strong>{{ $userIngredient->quantity }}</strong>
                                            @endif
                                            @if($userIngredient->unit)
                                                <span class="text-muted">{{ $userIngredient->unit }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Cập nhật: {{ $userIngredient->updated_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="customer-card text-center py-5" id="emptyState">
            <div class="mb-4">
                <i class="fas fa-shopping-basket fa-4x text-muted"></i>
            </div>
            <h4 class="mb-3">Bạn chưa thêm nguyên liệu nào</h4>
            <p class="text-muted mb-4">Hãy thêm nguyên liệu bạn có trong bếp để AI có thể gợi ý món ăn phù hợp!</p>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ingredientModal" onclick="openAddModal()">
                <i class="fas fa-plus me-2"></i>Thêm nguyên liệu ngay
            </button>
        </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ingredientModalLabel">
                    <i class="fas fa-plus me-2"></i>Thêm nguyên liệu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ingredientForm" method="POST">
                @csrf
                <div id="formMethod"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ingredient_id" class="form-label">Chọn nguyên liệu <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="ingredient_search" 
                               placeholder="Tìm kiếm nguyên liệu..."
                               autocomplete="off">
                        <input type="hidden" id="ingredient_id" name="ingredient_id">
                        <div id="ingredientDropdown" class="list-group mt-2" style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: calc(100% - 2rem);"></div>
                        <small class="text-muted">Nhập tên nguyên liệu để tìm kiếm</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="quantity" 
                                   name="quantity" 
                                   placeholder="VD: 500">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="unit" class="form-label">Đơn vị</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="unit" 
                                   name="unit" 
                                   placeholder="VD: g, kg, quả, bó">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Xác nhận xoá
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xoá nguyên liệu này khỏi bếp của bạn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Xoá
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout;
let selectedIngredientId = null;
let selectedIngredientName = '';

// Open Add Modal
function openAddModal() {
    document.getElementById('ingredientModalLabel').innerHTML = '<i class="fas fa-plus me-2"></i>Thêm nguyên liệu';
    document.getElementById('ingredientForm').action = '{{ route("user.ingredients.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('ingredient_id').value = '';
    document.getElementById('ingredient_search').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    selectedIngredientId = null;
    selectedIngredientName = '';
    document.getElementById('ingredientDropdown').style.display = 'none';
}

// Open Edit Modal
function openEditModal(userIngredientId, ingredientId, ingredientName, quantity, unit) {
    document.getElementById('ingredientModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Chỉnh sửa nguyên liệu';
    document.getElementById('ingredientForm').action = `/user/ingredients/${userIngredientId}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('ingredient_id').value = ingredientId;
    document.getElementById('ingredient_search').value = typeof ingredientName === 'string' ? ingredientName : '';
    document.getElementById('quantity').value = typeof quantity === 'string' ? quantity : '';
    document.getElementById('unit').value = typeof unit === 'string' ? unit : '';
    selectedIngredientId = ingredientId;
    selectedIngredientName = typeof ingredientName === 'string' ? ingredientName : '';
    document.getElementById('ingredientDropdown').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('ingredientModal'));
    modal.show();
}

// Confirm Delete
function confirmDelete(userIngredientId) {
    document.getElementById('deleteForm').action = `/user/ingredients/${userIngredientId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Ingredient Search
document.getElementById('ingredient_search').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    const dropdown = document.getElementById('ingredientDropdown');
    
    if (searchTerm.length < 2) {
        dropdown.style.display = 'none';
        return;
    }
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('api.user.ingredients.search') }}?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    dropdown.innerHTML = '';
                    data.data.forEach(ingredient => {
                        const item = document.createElement('a');
                        item.className = 'list-group-item list-group-item-action';
                        item.href = '#';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${ingredient.name}</strong>
                                    <br>
                                    <small class="text-muted">${ingredient.type || 'Khác'}</small>
                                </div>
                            </div>
                        `;
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            selectedIngredientId = ingredient.id;
                            selectedIngredientName = ingredient.name;
                            document.getElementById('ingredient_id').value = ingredient.id;
                            document.getElementById('ingredient_search').value = ingredient.name;
                            dropdown.style.display = 'none';
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.style.display = 'block';
                } else {
                    dropdown.innerHTML = '<div class="list-group-item text-muted">Không tìm thấy nguyên liệu</div>';
                    dropdown.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }, 300);
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('ingredientDropdown');
    const searchInput = document.getElementById('ingredient_search');
    if (!dropdown.contains(e.target) && !searchInput.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// Form Validation
document.getElementById('ingredientForm').addEventListener('submit', function(e) {
    if (!document.getElementById('ingredient_id').value) {
        e.preventDefault();
        alert('Vui lòng chọn nguyên liệu!');
        return false;
    }
});
</script>
@endpush
@endsection


@extends('layouts.customer')

@section('title', 'Nguyên liệu cần mua thêm')

@section('content')
<div class="container py-5">
    <!-- Khu A: Title Section -->
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2">
            <i class="fas fa-shopping-cart me-2 text-primary"></i>
            Nguyên liệu cần mua thêm
        </h1>
        <p class="text-muted">
            Được AI đề xuất dựa trên các món bạn muốn nấu
        </p>
    </div>

    <!-- Khu B: Chọn món nguồn - TargetDishSelector -->
    <div class="customer-card mb-4">
        <div class="customer-card-body">
            <label class="form-label fw-bold mb-3">
                <i class="fas fa-list-check me-2"></i>
                Chọn món để xem nguyên liệu cần mua:
            </label>
            <div class="row g-3" id="dishSelector">
                @foreach($recommendedDishes as $dish)
                    @php
                        $isSelected = in_array($dish->id, $selectedDishIds);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check p-3 border rounded {{ $isSelected ? 'border-primary bg-light' : '' }}" 
                             style="cursor: pointer; transition: all 0.2s;"
                             onmouseover="this.style.backgroundColor='#f8f9fa'" 
                             onmouseout="this.style.backgroundColor='{{ $isSelected ? '#f8f9fa' : 'white' }}'"
                             onclick="toggleDish({{ $dish->id }})">
                            <input class="form-check-input dish-checkbox" 
                                   type="checkbox" 
                                   value="{{ $dish->id }}" 
                                   id="dish_{{ $dish->id }}"
                                   {{ $isSelected ? 'checked' : '' }}
                                   onchange="if (!isInitialLoad) updateShoppingList()">
                            <label class="form-check-label w-100" for="dish_{{ $dish->id }}" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    @if($dish->image)
                                        <img src="{{ asset('storage/' . $dish->image) }}" 
                                             alt="{{ $dish->name }}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-utensils text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $dish->name }}</div>
                                        @if($dish->category)
                                            <small class="text-muted">{{ $dish->category->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllDishes()">
                        <i class="fas fa-check-double me-1"></i>Chọn tất cả
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="deselectAllDishes()">
                        <i class="fas fa-times me-1"></i>Bỏ chọn tất cả
                    </button>
                </div>
                <div>
                    <span class="text-muted">
                        Đã chọn: <strong id="selectedCount">{{ count($selectedDishIds) }}</strong> món
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Khu C: Danh sách nguyên liệu cần mua - MissingIngredientList -->
    <div id="missingIngredientsSection">
        @if(count($missingIngredients) > 0)
            <div class="customer-card">
                <div class="customer-card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-list-ul me-2 text-primary"></i>
                        Danh sách nguyên liệu cần mua (<span id="ingredientCount">{{ count($missingIngredients) }}</span> món)
                    </h5>
                    
                    <div id="missingIngredientsList">
                        @foreach($missingIngredients as $item)
                            <div class="missing-ingredient-item border rounded p-3 mb-3" 
                                 data-ingredient-id="{{ $item['ingredient_id'] }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="fw-bold mb-0 me-2">{{ $item['ingredient_name'] }}</h6>
                                            @if($item['is_required'])
                                                <span class="badge bg-danger">Cần thiết</span>
                                            @endif
                                        </div>
                                        
                                        @if($item['ingredient_type'])
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Loại: {{ $item['ingredient_type'] }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <div class="mb-2">
                                            <span class="badge bg-info">
                                                <i class="fas fa-utensils me-1"></i>
                                                Cần cho {{ $item['required_count'] }} món
                                            </span>
                                        </div>
                                        
                                        @if($item['suggested_quantity'])
                                            <div class="mb-2">
                                                <small class="text-success">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Gợi ý số lượng: {{ $item['suggested_quantity'] }}
                                                    @if($item['ingredient_unit'])
                                                        {{ $item['ingredient_unit'] }}
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <!-- Danh sách món cần nguyên liệu này -->
                                        @if(isset($dishesMap) && !empty($item['required_for_dishes']))
                                            <div class="mt-2">
                                                <small class="text-muted">Cần cho các món:</small>
                                                <div class="mt-1">
                                                    @foreach($item['required_for_dishes'] as $dishId)
                                                        @php
                                                            $dish = $dishesMap->get($dishId);
                                                        @endphp
                                                        @if($dish)
                                                            <span class="badge bg-secondary me-1 mb-1">{{ $dish->name }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Khu D: Hành động nhanh - ShoppingActions -->
                                    <div class="ms-3">
                                        <button type="button" 
                                                class="btn btn-success btn-sm mark-purchased-btn"
                                                onclick="markAsPurchased({{ $item['ingredient_id'] }}, '{{ $item['ingredient_name'] }}', '{{ $item['suggested_quantity'] ?? '' }}', '{{ $item['ingredient_unit'] ?? '' }}')">
                                            <i class="fas fa-check me-1"></i>
                                            Đã mua
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Khu E: Empty state - EmptyShoppingList -->
            <div class="customer-card text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                </div>
                <h4 class="mb-3">Bạn đã đủ nguyên liệu!</h4>
                <p class="text-muted mb-4">
                    Bạn đã có đủ nguyên liệu cho các món được chọn.<br>
                    Có thể bắt đầu nấu ngay!
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('recommendations.index') }}" class="btn btn-primary">
                        <i class="fas fa-utensils me-2"></i>Xem gợi ý món ăn
                    </a>
                    <a href="{{ route('user.ingredients.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-snowflake me-2"></i>Quản lý nguyên liệu
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedDishIds = @json($selectedDishIds);
let isInitialLoad = true; // Flag để tránh gọi API khi trang load lần đầu

// Sau khi trang load xong, set flag về false
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        isInitialLoad = false;
    }, 500);
});

function toggleDish(dishId) {
    const checkbox = document.getElementById('dish_' + dishId);
    const wasChecked = checkbox.checked;
    checkbox.checked = !wasChecked;
    // Chỉ update nếu không phải lần load đầu và checkbox thực sự thay đổi
    if (!isInitialLoad && checkbox.checked !== wasChecked) {
        updateShoppingList();
    }
}

function selectAllDishes() {
    document.querySelectorAll('.dish-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateShoppingList();
}

function deselectAllDishes() {
    document.querySelectorAll('.dish-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateShoppingList();
}

function updateShoppingList() {
    // Lấy danh sách dish IDs đã chọn
    selectedDishIds = Array.from(document.querySelectorAll('.dish-checkbox:checked'))
        .map(cb => parseInt(cb.value));
    
    // Cập nhật số lượng đã chọn
    document.getElementById('selectedCount').textContent = selectedDishIds.length;
    
    if (selectedDishIds.length === 0) {
        // Nếu không chọn món nào, hiển thị empty state
        document.getElementById('missingIngredientsSection').innerHTML = `
            <div class="customer-card text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-list-check fa-4x text-muted mb-3"></i>
                </div>
                <h4 class="mb-3">Chưa chọn món nào</h4>
                <p class="text-muted mb-4">
                    Vui lòng chọn ít nhất một món để xem danh sách nguyên liệu cần mua.
                </p>
            </div>
        `;
        return;
    }
    
    // Hiển thị loading
    document.getElementById('loadingOverlay').classList.remove('d-none');
    
    // Gọi API để lấy nguyên liệu thiếu
    fetch(`{{ route('api.missing-ingredients') }}?dish_ids=${selectedDishIds.join(',')}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
    })
    .then(async response => {
        // Kiểm tra response status
        if (!response.ok) {
            const errorText = await response.text();
            console.error('API Error Response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Kiểm tra content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Non-JSON Response:', text);
            throw new Error('Response is not JSON');
        }
        
        return response.json();
    })
    .then(data => {
        document.getElementById('loadingOverlay').classList.add('d-none');
        
        if (data.success) {
            renderMissingIngredients(data.data);
        } else {
            console.error('API returned error:', data);
            const errorMsg = data.message || 'Có lỗi xảy ra khi tải danh sách nguyên liệu.';
            alert(errorMsg);
        }
    })
    .catch(error => {
        document.getElementById('loadingOverlay').classList.add('d-none');
        console.error('Fetch Error:', error);
        // Chỉ hiển thị alert nếu không phải lỗi do user cancel
        if (error.name !== 'AbortError') {
            alert('Có lỗi xảy ra khi tải danh sách nguyên liệu. Vui lòng thử lại.');
        }
    });
}

function renderMissingIngredients(ingredients) {
    const section = document.getElementById('missingIngredientsSection');
    
    if (ingredients.length === 0) {
        section.innerHTML = `
            <div class="customer-card text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                </div>
                <h4 class="mb-3">Bạn đã đủ nguyên liệu!</h4>
                <p class="text-muted mb-4">
                    Bạn đã có đủ nguyên liệu cho các món được chọn.<br>
                    Có thể bắt đầu nấu ngay!
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('recommendations.index') }}" class="btn btn-primary">
                        <i class="fas fa-utensils me-2"></i>Xem gợi ý món ăn
                    </a>
                    <a href="{{ route('user.ingredients.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-snowflake me-2"></i>Quản lý nguyên liệu
                    </a>
                </div>
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="customer-card">
            <div class="customer-card-body">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-list-ul me-2 text-primary"></i>
                    Danh sách nguyên liệu cần mua (<span id="ingredientCount">${ingredients.length}</span> món)
                </h5>
                <div id="missingIngredientsList">
    `;
    
    ingredients.forEach(item => {
        const requiredBadge = item.is_required ? '<span class="badge bg-danger">Cần thiết</span>' : '';
        const typeInfo = item.ingredient_type ? `
            <div class="mb-2">
                <small class="text-muted">
                    <i class="fas fa-tag me-1"></i>
                    Loại: ${item.ingredient_type}
                </small>
            </div>
        ` : '';
        const quantityInfo = item.suggested_quantity ? `
            <div class="mb-2">
                <small class="text-success">
                    <i class="fas fa-info-circle me-1"></i>
                    Gợi ý số lượng: ${item.suggested_quantity} ${item.ingredient_unit || ''}
                </small>
            </div>
        ` : '';
        
        html += `
            <div class="missing-ingredient-item border rounded p-3 mb-3" data-ingredient-id="${item.ingredient_id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <h6 class="fw-bold mb-0 me-2">${item.ingredient_name}</h6>
                            ${requiredBadge}
                        </div>
                        ${typeInfo}
                        <div class="mb-2">
                            <span class="badge bg-info">
                                <i class="fas fa-utensils me-1"></i>
                                Cần cho ${item.required_count} món
                            </span>
                        </div>
                        ${quantityInfo}
                    </div>
                    <div class="ms-3">
                        <button type="button" 
                                class="btn btn-success btn-sm mark-purchased-btn"
                                onclick="markAsPurchased(${item.ingredient_id}, '${item.ingredient_name}', '${item.suggested_quantity || ''}', '${item.ingredient_unit || ''}')">
                            <i class="fas fa-check me-1"></i>
                            Đã mua
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += `
                </div>
            </div>
        </div>
    `;
    
    section.innerHTML = html;
    // ingredientCount đã được set trong HTML string ở trên, không cần set lại
}

function markAsPurchased(ingredientId, ingredientName, quantity, unit) {
    if (!confirm(`Bạn đã mua "${ingredientName}"?\nNguyên liệu này sẽ được thêm vào tủ lạnh của bạn.`)) {
        return;
    }
    
    const btn = event.target.closest('.mark-purchased-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xử lý...';
    
    fetch('{{ route("api.shopping-list.mark-purchased") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            ingredient_id: ingredientId,
            quantity: quantity || null,
            unit: unit || null,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Xóa item khỏi danh sách
            const item = document.querySelector(`[data-ingredient-id="${ingredientId}"]`);
            if (item) {
                item.style.transition = 'opacity 0.3s';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    // Cập nhật lại danh sách
                    updateShoppingList();
                }, 300);
            }
            
            // Hiển thị thông báo thành công
            showNotification('success', data.message || 'Đã thêm nguyên liệu vào tủ lạnh!');
        } else {
            alert(data.message || 'Có lỗi xảy ra.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã mua';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã mua';
    });
}

function showNotification(type, message) {
    // Tạo thông báo tạm thời
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '10000';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush


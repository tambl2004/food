@extends('layouts.customer')

@section('title', 'Món ăn gợi ý cho bạn')

@section('content')
<div class="container py-5">
    <!-- Khu A: Title Section -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h1 class="h2 fw-bold mb-2">
                    <i class="fas fa-robot me-2 text-primary"></i>
                    Món ăn gợi ý cho bạn hôm nay
                </h1>
                <p class="text-muted">
                    Được AI đề xuất dựa trên sở thích và nguyên liệu của bạn
                </p>
            </div>
            <div>
                <a href="{{ route('shopping-list.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Nguyên liệu cần mua
                </a>
            </div>
        </div>
    </div>

    <!-- Khu B: Quick Filter Bar -->
    <div class="customer-card mb-4">
        <div class="customer-card-body">
            <div class="row g-3">
                <div class="col-md-12 mb-2">
                    <label class="form-label text-muted small">Bộ lọc nhanh:</label>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_quick_cook" 
                               {{ request('quick_cook') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_quick_cook">
                            <i class="fas fa-clock text-warning me-1"></i>
                            Nấu nhanh (≤30 phút)
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_sufficient" 
                               {{ request('sufficient_ingredients') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_sufficient">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Đủ nguyên liệu
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_healthy" 
                               {{ request('healthy') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_healthy">
                            <i class="fas fa-heart text-danger me-1"></i>
                            Healthy
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_low_calorie" 
                               {{ request('low_calorie') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_low_calorie">
                            <i class="fas fa-weight text-info me-1"></i>
                            Ít calo
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_vegetarian" 
                               {{ request('vegetarian') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_vegetarian">
                            <i class="fas fa-leaf text-success me-1"></i>
                            Món chay
                        </label>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="form-check">
                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="filter_spicy" 
                               {{ request('spicy') ? 'checked' : '' }} onchange="applyFilters()">
                        <label class="form-check-label" for="filter_spicy">
                            <i class="fas fa-pepper-hot text-danger me-1"></i>
                            Món cay
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khu C: Recommended Dish List -->
    @if(count($recommendations) > 0)
        <div class="row" id="recommendationsList">
            @foreach($recommendations as $rec)
                @php
                    $dish = $rec['dish'];
                    $ingredientMatchRatio = $rec['ingredient_match_ratio'];
                    $missingIngredients = $rec['missing_ingredients'];
                    $recommendationReason = $rec['recommendation_reason'];
                    $isFullMatch = $ingredientMatchRatio >= 1.0;
                    $totalTime = ($dish->prep_time ?? 0) + ($dish->cook_time ?? 0);
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="customer-card h-100 {{ $isFullMatch ? 'border-success border-2' : '' }}">
                        @if($isFullMatch)
                            <div class="alert alert-success mb-0 rounded-0 rounded-top py-2">
                                <i class="fas fa-check-circle me-1"></i>
                                <strong>Đủ nguyên liệu!</strong> Có thể nấu ngay
                            </div>
                        @endif
                        <div class="customer-card-body">
                            <!-- Image -->
                            @if($dish->image)
                                <img src="{{ asset('storage/' . $dish->image) }}" 
                                     alt="{{ $dish->name }}" 
                                     class="img-fluid rounded mb-3" 
                                     style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif

                            <!-- Title & Category -->
                            <h5 class="fw-bold mb-2">{{ $dish->name }}</h5>
                            @if($dish->category)
                                <span class="badge bg-secondary mb-2">{{ $dish->category->name }}</span>
                            @endif

                            <!-- Recommendation Reason -->
                            @if($recommendationReason)
                                <div class="mb-2">
                                    <small class="text-primary">
                                        <i class="fas fa-star me-1"></i>
                                        {{ $recommendationReason }}
                                    </small>
                                </div>
                            @endif

                            <!-- Ingredient Match Info -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small">Nguyên liệu:</span>
                                    <span class="fw-bold {{ $isFullMatch ? 'text-success' : 'text-warning' }}">
                                        {{ round($ingredientMatchRatio * 100) }}%
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $isFullMatch ? 'bg-success' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $ingredientMatchRatio * 100 }}%"
                                         aria-valuenow="{{ $ingredientMatchRatio * 100 }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                @if(!$isFullMatch && $missingIngredients->count() > 0)
                                    <small class="text-muted">
                                        Thiếu {{ $missingIngredients->count() }} nguyên liệu
                                    </small>
                                @endif
                            </div>

                            <!-- Missing Ingredients Badges -->
                            @if($missingIngredients->count() > 0 && $missingIngredients->count() <= 3)
                                <div class="mb-3">
                                    @foreach($missingIngredients as $missing)
                                        <span class="badge bg-danger me-1 mb-1">{{ $missing->name }}</span>
                                    @endforeach
                                </div>
                            @elseif($missingIngredients->count() > 3)
                                <div class="mb-3">
                                    @foreach($missingIngredients->take(2) as $missing)
                                        <span class="badge bg-danger me-1 mb-1">{{ $missing->name }}</span>
                                    @endforeach
                                    <span class="badge bg-secondary">+{{ $missingIngredients->count() - 2 }} khác</span>
                                </div>
                            @endif

                            <!-- Dish Info -->
                            <div class="row g-2 mb-3">
                                @if($dish->difficulty)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-signal me-1"></i>
                                            {{ ucfirst($dish->difficulty) }}
                                        </small>
                                    </div>
                                @endif
                                @if($totalTime > 0)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $totalTime }} phút
                                        </small>
                                    </div>
                                @endif
                                @if($dish->servings)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $dish->servings }} khẩu phần
                                        </small>
                                    </div>
                                @endif
                                @if($dish->calories)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-fire me-1"></i>
                                            {{ $dish->calories }} cal
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('dishes.show', $dish->id) }}" 
                                   class="btn btn-primary btn-sm flex-grow-1"
                                   onclick="logView({{ $dish->id }})">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @if($isFullMatch)
                                    <a href="{{ route('dishes.show', $dish->id) }}" 
                                       class="btn btn-success btn-sm"
                                       onclick="logCook({{ $dish->id }})">
                                        <i class="fas fa-utensils me-1"></i>Nấu món này
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Khu D: Empty State -->
        <div class="customer-card text-center py-5">
            <div class="mb-4">
                <i class="fas fa-robot fa-4x text-muted mb-3"></i>
            </div>
            <h4 class="mb-3">Chưa tìm được món phù hợp</h4>
            <p class="text-muted mb-4">
                AI chưa thể tìm thấy món ăn phù hợp với sở thích và nguyên liệu của bạn.<br>
                Hãy thử thêm nguyên liệu hoặc cập nhật sở thích của bạn!
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('user.ingredients.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm nguyên liệu
                </a>
                <a href="{{ route('preferences.show') }}" class="btn btn-outline-primary">
                    <i class="fas fa-heart me-2"></i>Cập nhật sở thích
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const params = new URLSearchParams();
    
    // Get checked filters
    if (document.getElementById('filter_quick_cook').checked) {
        params.append('quick_cook', '1');
    }
    if (document.getElementById('filter_sufficient').checked) {
        params.append('sufficient_ingredients', '1');
    }
    if (document.getElementById('filter_healthy').checked) {
        params.append('healthy', '1');
    }
    if (document.getElementById('filter_low_calorie').checked) {
        params.append('low_calorie', '1');
    }
    if (document.getElementById('filter_vegetarian').checked) {
        params.append('vegetarian', '1');
    }
    if (document.getElementById('filter_spicy').checked) {
        params.append('spicy', '1');
    }
    
    // Reload page with filters
    const url = new URL(window.location.href);
    url.search = params.toString();
    window.location.href = url.toString();
}

function logView(dishId) {
    // Log view action (non-blocking)
    fetch(`/recommendations/dishes/${dishId}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
        },
    }).catch(err => console.error('Failed to log view:', err));
}

function logCook(dishId) {
    // Log cook action (non-blocking)
    fetch(`/recommendations/dishes/${dishId}/cook`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
        },
    }).catch(err => console.error('Failed to log cook:', err));
}
</script>
@endpush


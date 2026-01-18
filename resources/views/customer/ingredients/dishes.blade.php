@extends('layouts.customer')

@section('title', 'Gợi ý món ăn')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bold mb-2">
                    <i class="fas fa-utensils me-2 text-primary"></i>
                    Gợi ý món ăn
                </h1>
                <p class="text-muted">Dựa trên nguyên liệu bạn có trong tủ lạnh</p>
            </div>
            <a href="{{ route('user.ingredients.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại tủ lạnh
            </a>
        </div>
    </div>

    @if($dishesWithMatchRate->count() > 0)
        <!-- Sort Options -->
        <div class="customer-card mb-4">
            <div class="customer-card-body">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Sắp xếp theo:</span>
                    <select class="form-select" style="width: auto;" id="sortSelect" onchange="sortDishes()">
                        <option value="match_rate_desc">Tỷ lệ đủ nguyên liệu (Cao → Thấp)</option>
                        <option value="match_rate_asc">Tỷ lệ đủ nguyên liệu (Thấp → Cao)</option>
                        <option value="name_asc">Tên món ăn (A → Z)</option>
                        <option value="name_desc">Tên món ăn (Z → A)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Dishes List -->
        <div class="row" id="dishesList">
            @foreach($dishesWithMatchRate as $item)
                @php
                    $dish = $item['dish'];
                    $matchedCount = $item['matched_count'];
                    $totalCount = $item['total_count'];
                    $matchRate = $item['match_rate'];
                    $missingIngredients = $item['missing_ingredients'];
                    $isFullMatch = $matchedCount === $totalCount;
                @endphp
                <div class="col-md-6 col-lg-4 mb-4 dish-item" data-match-rate="{{ $matchRate }}" data-name="{{ strtolower($dish->name) }}">
                    <div class="customer-card h-100 {{ $isFullMatch ? 'border-success' : '' }}">
                        @if($isFullMatch)
                            <div class="alert alert-success mb-0 rounded-0 rounded-top">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Đủ nguyên liệu!</strong> Bạn có thể nấu món này ngay
                            </div>
                        @endif
                        <div class="customer-card-body">
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
                            
                            <h5 class="fw-bold mb-2">{{ $dish->name }}</h5>
                            
                            @if($dish->category)
                                <span class="badge bg-secondary mb-2">{{ $dish->category->name }}</span>
                            @endif
                            
                            <!-- Match Rate -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Nguyên liệu:</span>
                                    <span class="fw-bold {{ $isFullMatch ? 'text-success' : 'text-warning' }}">
                                        {{ $matchedCount }}/{{ $totalCount }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar {{ $isFullMatch ? 'bg-success' : 'bg-warning' }}" 
                                         role="progressbar" 
                                         style="width: {{ $matchRate }}%"
                                         aria-valuenow="{{ $matchRate }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ $matchRate }}% đủ nguyên liệu</small>
                            </div>

                            <!-- Missing Ingredients -->
                            @if($missingIngredients->count() > 0)
                                <div class="mb-3">
                                    <small class="text-danger fw-bold">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Còn thiếu {{ $missingIngredients->count() }} nguyên liệu:
                                    </small>
                                    <div class="mt-2">
                                        @foreach($missingIngredients->take(3) as $missing)
                                            <span class="badge bg-danger me-1 mb-1">{{ $missing->name }}</span>
                                        @endforeach
                                        @if($missingIngredients->count() > 3)
                                            <span class="badge bg-secondary">+{{ $missingIngredients->count() - 3 }} khác</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Dish Info -->
                            <div class="row g-2 mb-3">
                                @if($dish->prep_time)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $dish->prep_time }} phút
                                        </small>
                                    </div>
                                @endif
                                @if($dish->difficulty)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-signal me-1"></i>
                                            {{ ucfirst($dish->difficulty) }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('dishes.show', $dish->id) }}" class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                @if($isFullMatch)
                                    <button class="btn btn-success btn-sm" onclick="alert('Bạn có đủ nguyên liệu để nấu món này!')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State (if filtered) -->
        <div id="noResults" class="customer-card text-center py-5" style="display: none;">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h5>Không tìm thấy món ăn phù hợp</h5>
            <p class="text-muted">Thử thay đổi bộ lọc hoặc thêm thêm nguyên liệu vào tủ lạnh</p>
        </div>
    @else
        <!-- Empty State -->
        <div class="customer-card text-center py-5">
            <i class="fas fa-utensils fa-4x text-muted mb-4"></i>
            <h4 class="mb-3">Không tìm thấy món ăn phù hợp</h4>
            <p class="text-muted mb-4">Chúng tôi không tìm thấy món ăn nào có thể nấu với nguyên liệu bạn đang có.</p>
            <a href="{{ route('user.ingredients.index') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm thêm nguyên liệu
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function sortDishes() {
    const sortValue = document.getElementById('sortSelect').value;
    const dishes = Array.from(document.querySelectorAll('.dish-item'));
    const container = document.getElementById('dishesList');
    
    dishes.sort((a, b) => {
        if (sortValue === 'match_rate_desc') {
            return parseFloat(b.getAttribute('data-match-rate')) - parseFloat(a.getAttribute('data-match-rate'));
        } else if (sortValue === 'match_rate_asc') {
            return parseFloat(a.getAttribute('data-match-rate')) - parseFloat(b.getAttribute('data-match-rate'));
        } else if (sortValue === 'name_asc') {
            return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
        } else if (sortValue === 'name_desc') {
            return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
        }
        return 0;
    });
    
    // Clear container
    container.innerHTML = '';
    
    // Append sorted dishes
    dishes.forEach(dish => container.appendChild(dish));
}
</script>
@endpush
@endsection


@extends('layouts.customer')

@section('title', 'Sở thích ăn uống')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-2">Sở thích ăn uống</h1>
        <p class="text-muted">Tuỳ chỉnh để AI hiểu bạn hơn</p>
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

    <form action="{{ route('preferences.update') }}" method="POST" id="preferencesForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Content Column -->
            <div class="col-lg-8">
                <!-- Favorite Categories Section -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-heart me-2 text-danger"></i>
                            Danh mục yêu thích
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn các loại món ăn bạn yêu thích (có thể chọn nhiều)</p>
                        <div class="row">
                            @php
                                $categories = [
                                    'mon_chay' => 'Món chay',
                                    'mon_man' => 'Món mặn',
                                    'mon_cay' => 'Món cay',
                                    'mon_ngot' => 'Món ngọt',
                                    'mon_trang_mieng' => 'Món tráng miệng',
                                    'mon_an_nhanh' => 'Món ăn nhanh',
                                    'mon_truyen_thong' => 'Món truyền thống',
                                    'mon_au' => 'Món Âu',
                                    'mon_my' => 'Món Mỹ',
                                    'mon_nhat' => 'Món Nhật',
                                    'mon_han' => 'Món Hàn',
                                    'mon_trung' => 'Món Trung',
                                ];
                                $selectedCategories = old('favorite_categories', $preferences->favorite_categories ?? []);
                            @endphp
                            @foreach($categories as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="favorite_categories[]" 
                                               value="{{ $key }}" 
                                               id="category_{{ $key }}"
                                               {{ in_array($key, $selectedCategories) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Diet Type Section -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-leaf me-2 text-success"></i>
                            Chế độ ăn
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn chế độ ăn của bạn</p>
                        <div class="row">
                            @php
                                $dietTypes = [
                                    'binh_thuong' => 'Bình thường',
                                    'chay' => 'Chay',
                                    'eat_clean' => 'Eat Clean',
                                    'keto' => 'Keto',
                                    'giam_can' => 'Giảm cân',
                                    'khong_gluten' => 'Không gluten',
                                    'low_carb' => 'Low Carb',
                                    'paleo' => 'Paleo',
                                ];
                                $selectedDietType = old('diet_type', $preferences->diet_type ?? null);
                            @endphp
                            @foreach($dietTypes as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="diet_type" 
                                               value="{{ $key }}" 
                                               id="diet_{{ $key }}"
                                               {{ $selectedDietType === $key ? 'checked' : '' }}>
                                        <label class="form-check-label" for="diet_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Spicy Level Section -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-pepper-hot me-2 text-warning"></i>
                            Mức độ cay
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn mức độ cay bạn có thể chịu được</p>
                        @php
                            $spicyLevels = [
                                0 => 'Không cay',
                                1 => 'Cay nhẹ',
                                2 => 'Cay vừa',
                                3 => 'Cay nhiều',
                            ];
                            $selectedSpicyLevel = old('spicy_level', $preferences->spicy_level ?? 0);
                        @endphp
                        <div class="row">
                            @foreach($spicyLevels as $level => $label)
                                <div class="col-md-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="spicy_level" 
                                               value="{{ $level }}" 
                                               id="spicy_{{ $level }}"
                                               {{ (int)$selectedSpicyLevel === $level ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="spicy_{{ $level }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <input type="range" 
                                   class="form-range" 
                                   id="spicyLevelSlider" 
                                   min="0" 
                                   max="3" 
                                   value="{{ $selectedSpicyLevel }}"
                                   oninput="document.getElementById('spicy_' + this.value).checked = true;">
                        </div>
                    </div>
                </div>

                <!-- Disliked Ingredients Section -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-ban me-2 text-danger"></i>
                            Nguyên liệu không thích
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn các nguyên liệu bạn không thích (có thể chọn nhiều)</p>
                        <div class="row mb-3">
                            @php
                                $ingredients = [
                                    'hanh' => 'Hành',
                                    'toi' => 'Tỏi',
                                    'hai_san' => 'Hải sản',
                                    'dau_phong' => 'Đậu phộng',
                                    'noi_tang' => 'Nội tạng',
                                    'ca_chua' => 'Cà chua',
                                    'ot' => 'Ớt',
                                    'gia_vi_man' => 'Gia vị mặn',
                                    'sua' => 'Sữa',
                                    'trung' => 'Trứng',
                                    'thit_bo' => 'Thịt bò',
                                    'thit_heo' => 'Thịt heo',
                                ];
                                $selectedIngredients = old('disliked_ingredients', $preferences->disliked_ingredients ?? []);
                            @endphp
                            @foreach($ingredients as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="disliked_ingredients[]" 
                                               value="{{ $key }}" 
                                               id="ingredient_{{ $key }}"
                                               {{ in_array($key, $selectedIngredients) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ingredient_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Custom Ingredient Input -->
                        <div class="mb-3">
                            <label for="customIngredient" class="form-label fw-semibold">Nguyên liệu khác (nhập và nhấn Enter)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customIngredient" 
                                   placeholder="Ví dụ: Cà tím, Bắp cải...">
                            <small class="form-text text-muted">Nhập tên nguyên liệu và nhấn Enter để thêm</small>
                            <div id="customIngredientsList" class="mt-2 d-flex flex-wrap gap-2">
                                @if(old('custom_ingredients'))
                                    @foreach(old('custom_ingredients') as $ingredient)
                                        <span class="badge bg-secondary">
                                            {{ $ingredient }}
                                            <input type="hidden" name="disliked_ingredients[]" value="{{ $ingredient }}">
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="this.parentElement.remove()"></button>
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Goal Section -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-dumbbell me-2 text-info"></i>
                            Mục tiêu sức khỏe
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn mục tiêu sức khỏe của bạn</p>
                        <select class="form-select @error('health_goal') is-invalid @enderror" 
                                name="health_goal" 
                                id="health_goal">
                            <option value="">-- Chọn mục tiêu --</option>
                            @php
                                $healthGoals = [
                                    'giu_dang' => 'Giữ dáng',
                                    'giam_can' => 'Giảm cân',
                                    'tang_co' => 'Tăng cơ',
                                    'an_lanh_manh' => 'Ăn lành mạnh',
                                    'khong_muc_tieu' => 'Không có mục tiêu cụ thể',
                                ];
                                $selectedHealthGoal = old('health_goal', $preferences->health_goal ?? null);
                            @endphp
                            @foreach($healthGoals as $key => $label)
                                <option value="{{ $key }}" {{ $selectedHealthGoal === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('health_goal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Action Buttons Card -->
                <div class="customer-card mb-4 sticky-top" style="top: 20px;">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-tasks me-2 text-primary"></i>
                            Thao tác
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Lưu sở thích
                            </button>
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-lg" 
                                    onclick="resetPreferences()">
                                <i class="fas fa-undo me-2"></i>
                                Đặt lại mặc định
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="customer-card">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-info-circle me-2 text-info"></i>
                            Thông tin
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-2">
                            <i class="fas fa-lightbulb me-2 text-warning"></i>
                            Sở thích của bạn sẽ được sử dụng để AI gợi ý món ăn phù hợp hơn.
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            Bạn có thể cập nhật sở thích bất kỳ lúc nào.
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-clock me-2 text-success"></i>
                            Cập nhật lần cuối: 
                            @if($preferences->updated_at)
                                {{ $preferences->updated_at->format('d/m/Y H:i') }}
                            @else
                                Chưa cập nhật
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom ingredient input handler
    const customIngredientInput = document.getElementById('customIngredient');
    const customIngredientsList = document.getElementById('customIngredientsList');

    if (customIngredientInput) {
        customIngredientInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const ingredient = this.value.trim();
                
                if (ingredient && !isIngredientExists(ingredient)) {
                    // Create badge
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-secondary';
                    badge.innerHTML = `
                        ${ingredient}
                        <input type="hidden" name="disliked_ingredients[]" value="${ingredient}">
                        <button type="button" class="btn-close btn-close-white ms-1" onclick="this.parentElement.remove()"></button>
                    `;
                    customIngredientsList.appendChild(badge);
                    this.value = '';
                }
            }
        });
    }

    // Check if ingredient already exists
    function isIngredientExists(ingredient) {
        const existingInputs = customIngredientsList.querySelectorAll('input[type="hidden"]');
        for (let input of existingInputs) {
            if (input.value.toLowerCase() === ingredient.toLowerCase()) {
                return true;
            }
        }
        return false;
    }

    // Sync slider with radio buttons
    const spicyRadios = document.querySelectorAll('input[name="spicy_level"]');
    const spicySlider = document.getElementById('spicyLevelSlider');

    if (spicySlider) {
        spicyRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                spicySlider.value = this.value;
            });
        });

        spicySlider.addEventListener('input', function() {
            const radio = document.getElementById('spicy_' + this.value);
            if (radio) {
                radio.checked = true;
            }
        });
    }
});

// Reset preferences function
function resetPreferences() {
    if (confirm('Bạn có chắc chắn muốn đặt lại tất cả sở thích về mặc định?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("preferences.reset") }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection


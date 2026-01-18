@extends('layouts.customer')

@section('title', 'Thu thập sở thích')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4 text-center">
        <h1 class="h2 fw-bold mb-2">Cho chúng tôi biết sở thích của bạn</h1>
        <p class="text-muted">Điều này giúp AI gợi ý món ăn phù hợp hơn cho bạn</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('onboarding.preferences.store') }}" method="POST" id="onboardingForm">
        @csrf

        <div class="row">
            <!-- Main Content Column -->
            <div class="col-lg-10 mx-auto">
                <!-- Group 1: Loại món yêu thích -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-heart me-2 text-danger"></i>
                            Loại món yêu thích <span class="text-danger">*</span>
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn các loại món ăn bạn yêu thích (chọn ít nhất 1 món)</p>
                        <div class="row">
                            @php
                                $selectedCategories = old('favorite_categories', []);
                            @endphp
                            @foreach($favoriteCategories as $key => $label)
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

                <!-- Group 2: Nguồn gốc món ăn -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-globe me-2 text-primary"></i>
                            Nguồn gốc món ăn
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn các nguồn gốc món ăn bạn yêu thích (có thể chọn nhiều)</p>
                        <div class="row">
                            @php
                                $selectedOrigins = old('origins', []);
                            @endphp
                            @foreach($origins as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="origins[]" 
                                               value="{{ $key }}" 
                                               id="origin_{{ $key }}"
                                               {{ in_array($key, $selectedOrigins) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="origin_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Group 3: Chế độ ăn / Lối sống -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-leaf me-2 text-success"></i>
                            Chế độ ăn / Lối sống
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn chế độ ăn phù hợp với bạn (có thể chọn nhiều)</p>
                        <div class="row">
                            @php
                                $selectedDietTypes = old('diet_types', []);
                            @endphp
                            @foreach($dietTypes as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="diet_types[]" 
                                               value="{{ $key }}" 
                                               id="diet_{{ $key }}"
                                               {{ in_array($key, $selectedDietTypes) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="diet_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Group 4: Mức độ ăn cay -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-pepper-hot me-2 text-warning"></i>
                            Mức độ ăn cay <span class="text-danger">*</span>
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn mức độ cay bạn có thể chịu được</p>
                        @php
                            $selectedSpicyLevel = old('spicy_level', 0);
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
                    </div>
                </div>

                <!-- Group 5: Dị ứng / Không ăn được -->
                <div class="customer-card mb-4">
                    <div class="customer-card-header">
                        <h3 class="customer-card-title">
                            <i class="fas fa-ban me-2 text-danger"></i>
                            Dị ứng / Không ăn được
                        </h3>
                    </div>
                    <div class="customer-card-body">
                        <p class="text-muted small mb-3">Chọn các nguyên liệu bạn dị ứng hoặc không ăn được (có thể chọn nhiều)</p>
                        <div class="row mb-3">
                            @php
                                $selectedAllergies = old('allergies', []);
                            @endphp
                            @foreach($allergies as $key => $label)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="allergies[]" 
                                               value="{{ $key }}" 
                                               id="allergy_{{ $key }}"
                                               {{ in_array($key, $selectedAllergies) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allergy_{{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Custom Allergies Input -->
                        <div class="mb-3">
                            <label for="allergies_custom" class="form-label fw-semibold">Khác (nhập tên nguyên liệu, cách nhau bởi dấu phẩy)</label>
                            <input type="text" 
                                   class="form-control @error('allergies_custom') is-invalid @enderror" 
                                   id="allergies_custom" 
                                   name="allergies_custom"
                                   value="{{ old('allergies_custom') }}"
                                   placeholder="Ví dụ: Cà tím, Bắp cải, Ớt chuông...">
                            @error('allergies_custom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nhập tên các nguyên liệu bạn dị ứng hoặc không ăn được, cách nhau bởi dấu phẩy</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg py-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Hoàn tất & Xem gợi ý món ăn
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('onboardingForm');
    
    // Validate form before submit
    form.addEventListener('submit', function(e) {
        const favoriteCategories = document.querySelectorAll('input[name="favorite_categories[]"]:checked');
        
        if (favoriteCategories.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một loại món yêu thích!');
            return false;
        }
    });
});
</script>
@endpush
@endsection


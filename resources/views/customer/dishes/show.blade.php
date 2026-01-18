@extends('layouts.customer')

@section('title', $dish->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.ingredients.find-dishes') }}">Gợi ý món ăn</a></li>
            <li class="breadcrumb-item active">{{ $dish->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Left Column: Image & Info -->
        <div class="col-lg-5 mb-4">
            @if($dish->image)
                <img src="{{ asset('storage/' . $dish->image) }}" 
                     alt="{{ $dish->name }}" 
                     class="img-fluid rounded shadow-lg mb-4"
                     style="width: 100%; max-height: 500px; object-fit: cover;">
            @else
                <div class="bg-light rounded shadow-lg mb-4 d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-image fa-5x text-muted"></i>
                </div>
            @endif

            <!-- Video -->
            @if($dish->video_url)
                <div class="mb-4">
                    <h5 class="mb-3"><i class="fas fa-video me-2"></i>Video hướng dẫn</h5>
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $dish->youtube_embed_url }}" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Details -->
        <div class="col-lg-7">
            <h1 class="h2 fw-bold mb-3">{{ $dish->name }}</h1>
            
            @if($dish->category)
                <span class="badge bg-secondary mb-3">{{ $dish->category->name }}</span>
            @endif

            @if($dish->description)
                <div class="mb-4">
                    <h5>Mô tả</h5>
                    <p class="text-muted">{{ $dish->description }}</p>
                </div>
            @endif

            <!-- Dish Info Grid -->
            <div class="row g-3 mb-4">
                @if($dish->prep_time)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                                <h6 class="text-muted mb-1">Thời gian chuẩn bị</h6>
                                <h5 class="mb-0">{{ $dish->prep_time }} phút</h5>
                            </div>
                        </div>
                    </div>
                @endif
                @if($dish->cook_time)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-fire fa-2x text-danger mb-2"></i>
                                <h6 class="text-muted mb-1">Thời gian nấu</h6>
                                <h5 class="mb-0">{{ $dish->cook_time }} phút</h5>
                            </div>
                        </div>
                    </div>
                @endif
                @if($dish->servings)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h6 class="text-muted mb-1">Khẩu phần</h6>
                                <h5 class="mb-0">{{ $dish->servings }} người</h5>
                            </div>
                        </div>
                    </div>
                @endif
                @if($dish->difficulty)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-signal fa-2x text-warning mb-2"></i>
                                <h6 class="text-muted mb-1">Độ khó</h6>
                                <h5 class="mb-0">{{ ucfirst($dish->difficulty) }}</h5>
                            </div>
                        </div>
                    </div>
                @endif
                @if($dish->calories)
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-fire-alt fa-2x text-info mb-2"></i>
                                <h6 class="text-muted mb-1">Calories</h6>
                                <h5 class="mb-0">{{ $dish->calories }} kcal</h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Ingredients List -->
            @if($dish->ingredients->count() > 0)
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-list-ul me-2"></i>
                        Nguyên liệu cần thiết
                    </h5>
                    <div class="row g-2">
                        @foreach($dish->ingredients as $ingredient)
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $ingredient->name }}</h6>
                                                @if($ingredient->pivot->quantity || $ingredient->pivot->unit)
                                                    <small class="text-muted">
                                                        {{ $ingredient->pivot->quantity ?? '' }} 
                                                        {{ $ingredient->pivot->unit ?? '' }}
                                                    </small>
                                                @endif
                                            </div>
                                            @if($ingredient->pivot->is_required)
                                                <span class="badge bg-danger">Bắt buộc</span>
                                            @else
                                                <span class="badge bg-secondary">Tùy chọn</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="d-flex gap-2 mb-4">
                <a href="{{ route('user.ingredients.find-dishes') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <a href="{{ route('user.ingredients.index') }}" class="btn btn-primary">
                    <i class="fas fa-snowflake me-2"></i>Về tủ lạnh
                </a>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    @if($dish->visibleReviews->count() > 0)
        <div class="mt-5">
            <h4 class="mb-4">
                <i class="fas fa-star me-2"></i>
                Đánh giá ({{ $dish->visibleReviews->count() }})
            </h4>
            <div class="row">
                @foreach($dish->visibleReviews->take(5) as $review)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Related Dishes -->
    @if($relatedDishes->count() > 0)
        <div class="mt-5">
            <h4 class="mb-4">Món ăn liên quan</h4>
            <div class="row">
                @foreach($relatedDishes as $relatedDish)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if($relatedDish->image)
                                <img src="{{ asset('storage/' . $relatedDish->image) }}" 
                                     alt="{{ $relatedDish->name }}" 
                                     class="card-img-top"
                                     style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $relatedDish->name }}</h6>
                                <a href="{{ route('dishes.show', $relatedDish->id) }}" class="btn btn-sm btn-primary w-100">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection


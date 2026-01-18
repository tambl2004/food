@extends('layouts.customer')

@section('title', $dish->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            @if($fromRecommendations ?? false)
                <li class="breadcrumb-item"><a href="{{ route('recommendations.index') }}">Gợi ý món ăn</a></li>
            @else
                <li class="breadcrumb-item"><a href="{{ route('user.ingredients.find-dishes') }}">Tìm món ăn</a></li>
            @endif
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
            
            <div class="d-flex align-items-center gap-2 mb-3">
                @if($dish->category)
                    <span class="badge bg-secondary">{{ $dish->category->name }}</span>
                @endif
                @if($dish->origin)
                    <span class="badge bg-info">{{ $dish->origin }}</span>
                @endif
            </div>

            <!-- AI Explanation Section (Khu giải thích AI) -->
            @if(isset($aiReason) && $aiReason)
                <div class="alert alert-info mb-4" role="alert">
                    <h5 class="alert-heading">
                        <i class="fas fa-robot me-2"></i>
                        Món này được gợi ý vì:
                    </h5>
                    <p class="mb-0">{{ $aiReason }}</p>
                </div>
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

            <!-- Ingredients List (Danh sách nguyên liệu với trạng thái) -->
            @if($dish->ingredients->count() > 0)
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-list-ul me-2"></i>
                        Nguyên liệu cần thiết
                        @if(auth()->check() && isset($ingredientMatchRatio))
                            <span class="badge bg-success ms-2">
                                {{ round($ingredientMatchRatio * 100) }}% có sẵn
                            </span>
                        @endif
                    </h5>
                    <div class="row g-2 mb-3">
                        @foreach($dish->ingredients as $ingredient)
                            @php
                                $status = isset($ingredientStatus[$ingredient->id]) ? $ingredientStatus[$ingredient->id] : null;
                                $isAvailable = $status === 'AVAILABLE';
                            @endphp
                            <div class="col-md-6">
                                <div class="card {{ $isAvailable ? 'border-success' : ($status === 'MISSING' ? 'border-warning' : '') }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    @if($status)
                                                        @if($isAvailable)
                                                            <i class="fas fa-check-circle text-success"></i>
                                                        @else
                                                            <i class="fas fa-times-circle text-danger"></i>
                                                        @endif
                                                    @endif
                                                    <h6 class="mb-0">{{ $ingredient->name }}</h6>
                                                </div>
                                                @if($ingredient->pivot->quantity || $ingredient->pivot->unit)
                                                    <small class="text-muted">
                                                        {{ $ingredient->pivot->quantity ?? '' }} 
                                                        {{ $ingredient->pivot->unit ?? '' }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column align-items-end gap-1">
                                                @if($ingredient->pivot->is_required)
                                                    <span class="badge bg-danger">Bắt buộc</span>
                                                @else
                                                    <span class="badge bg-secondary">Tùy chọn</span>
                                                @endif
                                                @if($status)
                                                    @if($isAvailable)
                                                        <small class="text-success">Đã có</small>
                                                    @else
                                                        <small class="text-danger">Còn thiếu</small>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Button thêm nguyên liệu thiếu vào shopping list -->
                    @if(auth()->check() && !empty($missingIngredients))
                        <form action="{{ route('dishes.add-missing-ingredients', $dish->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Thêm nguyên liệu còn thiếu vào danh sách mua
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Actions Section (Hành động người dùng) -->
            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="fas fa-tasks me-2"></i>
                    Hành động
                </h5>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <!-- Nấu món này -->
                        <form action="{{ route('dishes.cook', $dish->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-utensils me-2"></i>
                                Nấu món này
                            </button>
                        </form>

                        <!-- Lưu món yêu thích -->
                        <form action="{{ route('dishes.favorite', $dish->id) }}" method="POST" class="d-inline" id="favoriteForm">
                            @csrf
                            <button type="submit" class="btn {{ isset($isFavorite) && $isFavorite ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="fas fa-heart me-2"></i>
                                {{ isset($isFavorite) && $isFavorite ? 'Đã yêu thích' : 'Lưu món yêu thích' }}
                            </button>
                        </form>

                        <!-- Đánh giá món -->
                        @if(isset($hasCooked) && $hasCooked)
                            @if(!isset($userReview) || !$userReview)
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="fas fa-star me-2"></i>
                                    Đánh giá món
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="fas fa-star me-2"></i>
                                    Sửa đánh giá
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-outline-warning" disabled title="Bạn cần nấu món này trước khi đánh giá">
                                <i class="fas fa-star me-2"></i>
                                Đánh giá món
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Đăng nhập để sử dụng các tính năng
                        </a>
                    @endauth

                    <!-- Quay lại -->
                    <a href="{{ $fromRecommendations ?? false ? route('recommendations.index') : route('user.ingredients.find-dishes') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cooking Instructions Section (Hướng dẫn nấu ăn) -->
    @if($dish->description || $dish->video_url)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-book-open me-2"></i>
                            Hướng dẫn nấu ăn
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($dish->description)
                            <div class="mb-3">
                                <h6>Mô tả chi tiết:</h6>
                                <p class="text-muted" style="white-space: pre-line;">{{ $dish->description }}</p>
                            </div>
                        @endif
                        
                        @if($dish->video_url)
                            <div>
                                <h6 class="mb-3">Video hướng dẫn:</h6>
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
                </div>
            </div>
        </div>
    @endif

    <!-- Review Modal -->
    @auth
        @if(isset($hasCooked) && $hasCooked)
            <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reviewModalLabel">
                                {{ isset($userReview) && $userReview ? 'Sửa đánh giá' : 'Đánh giá món ăn' }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ isset($userReview) && $userReview ? route('reviews.update', $userReview->id) : route('reviews.store-dish', $dish->id) }}" method="POST">
                            @csrf
                            @if(isset($userReview) && $userReview)
                                @method('PUT')
                            @endif
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Đánh giá (sao)</label>
                                    <select class="form-select" id="rating" name="rating" required>
                                        <option value="">Chọn số sao</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ (isset($userReview) && $userReview && $userReview->rating == $i) ? 'selected' : '' }}>
                                                {{ $i }} sao
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Bình luận</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="4" maxlength="1000">{{ isset($userReview) && $userReview ? $userReview->comment : '' }}</textarea>
                                    <small class="text-muted">Tối đa 1000 ký tự</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($userReview) && $userReview ? 'Cập nhật' : 'Gửi đánh giá' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

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


@extends('layouts.customer')

@section('title', 'Lịch sử đánh giá của tôi')

@section('content')
<div class="container py-5">
    <!-- Title Section -->
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2">
            <i class="fas fa-star me-2 text-warning"></i>
            Lịch sử đánh giá của tôi
        </h1>
        <p class="text-muted">
            Xem lại tất cả các đánh giá bạn đã gửi cho các món ăn
        </p>
    </div>

    <!-- Reviews List -->
    @if($reviews->count() > 0)
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    @if($review->dish && $review->dish->image)
                                        <img src="{{ asset('storage/' . $review->dish->image) }}" 
                                             alt="{{ $review->dish->name }}" 
                                             class="img-fluid rounded"
                                             style="max-height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="height: 120px;">
                                            <i class="fas fa-utensils fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">
                                                <a href="{{ route('dishes.show', $review->dish->id) }}" 
                                                   class="text-decoration-none">
                                                    {{ $review->dish->name ?? 'N/A' }}
                                                </a>
                                            </h5>
                                            @if($review->dish && $review->dish->category)
                                                <span class="badge bg-secondary mb-2">
                                                    {{ $review->dish->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            {{ $review->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}"></i>
                                            @endfor
                                            <span class="ms-2 text-dark">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                    
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                    
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('dishes.show', $review->dish->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Xem món ăn
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editReviewModal{{ $review->id }}">
                                            <i class="fas fa-edit me-1"></i>Sửa đánh giá
                                        </button>
                                        <form action="{{ route('reviews.destroy', $review->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i>Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Review Modal -->
                <div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1" aria-labelledby="editReviewModalLabel{{ $review->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editReviewModalLabel{{ $review->id }}">Sửa đánh giá</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="rating{{ $review->id }}" class="form-label">Đánh giá (sao)</label>
                                        <select class="form-select" id="rating{{ $review->id }}" name="rating" required>
                                            <option value="">Chọn số sao</option>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                                    {{ $i }} sao
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment{{ $review->id }}" class="form-label">Bình luận</label>
                                        <textarea class="form-control" id="comment{{ $review->id }}" name="comment" rows="4" maxlength="1000">{{ $review->comment }}</textarea>
                                        <small class="text-muted">Tối đa 1000 ký tự</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-star fa-5x text-muted"></i>
                </div>
                <h5 class="mb-3">Bạn chưa có đánh giá nào</h5>
                <p class="text-muted mb-4">
                    Hãy nấu các món ăn và đánh giá để giúp hệ thống gợi ý món ăn phù hợp hơn!
                </p>
                <a href="{{ route('recommendations.index') }}" class="btn btn-primary">
                    <i class="fas fa-compass me-2"></i>Khám phá món ăn
                </a>
            </div>
        </div>
    @endif
</div>
@endsection


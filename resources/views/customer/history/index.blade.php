@extends('layouts.customer')

@section('title', 'Lịch sử chọn món của tôi')

@section('content')
<div class="container py-5">
    <!-- Khu A: Title Section -->
    <div class="mb-5">
        <h1 class="h2 fw-bold mb-2">
            <i class="fas fa-history me-2 text-primary"></i>
            Lịch sử chọn món của tôi
        </h1>
        <p class="text-muted">
            Xem lại các món bạn đã xem, lưu và nấu
        </p>
    </div>

    <!-- Khu B: Tabs Section -->
    <div class="customer-card mb-4">
        <div class="customer-card-body">
            <ul class="nav nav-tabs border-0" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $actionType === 'ALL' ? 'active' : '' }}" 
                       href="{{ route('user.history.index', ['action' => 'ALL']) }}"
                       id="tab-all">
                        <i class="fas fa-list me-1"></i>
                        Tất cả
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $actionType === 'VIEWED' ? 'active' : '' }}" 
                       href="{{ route('user.history.index', ['action' => 'VIEWED']) }}"
                       id="tab-viewed">
                        <i class="fas fa-eye me-1"></i>
                        Đã xem
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $actionType === 'SAVED' ? 'active' : '' }}" 
                       href="{{ route('user.history.index', ['action' => 'SAVED']) }}"
                       id="tab-saved">
                        <i class="fas fa-heart me-1"></i>
                        Đã lưu
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $actionType === 'COOKED' ? 'active' : '' }}" 
                       href="{{ route('user.history.index', ['action' => 'COOKED']) }}"
                       id="tab-cooked">
                        <i class="fas fa-utensils me-1"></i>
                        Đã nấu
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Khu C: History List -->
    @if($histories->count() > 0)
        <div class="row g-4">
            @foreach($histories as $history)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        @if($history->dish && $history->dish->image)
                            <img src="{{ asset('storage/' . $history->dish->image) }}" 
                                 alt="{{ $history->dish->name }}" 
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-utensils fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">
                                {{ $history->dish->name ?? 'N/A' }}
                            </h5>
                            
                            @if($history->dish && $history->dish->category)
                                <span class="badge bg-secondary mb-2">
                                    {{ $history->dish->category->name }}
                                </span>
                            @endif
                            
                            <div class="mb-2">
                                @if($history->action === 'viewed')
                                    <span class="badge bg-info">
                                        <i class="fas fa-eye me-1"></i>Đã xem
                                    </span>
                                @elseif($history->action === 'saved')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-heart me-1"></i>Đã lưu
                                    </span>
                                @elseif($history->action === 'cooked')
                                    <span class="badge bg-success">
                                        <i class="fas fa-utensils me-1"></i>Đã nấu
                                    </span>
                                @endif
                            </div>
                            
                            <small class="text-muted mb-3">
                                <i class="fas fa-clock me-1"></i>
                                {{ $history->action_at->diffForHumans() }}
                            </small>
                            
                            <div class="mt-auto d-flex gap-2">
                                @if($history->dish)
                                    <a href="{{ route('dishes.show', $history->dish->id) }}" 
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    @if($history->action === 'cooked')
                                        <form action="{{ route('dishes.cook', $history->dish->id) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-utensils me-1"></i>Nấu lại
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $histories->links() }}
        </div>
    @else
        <!-- Khu D: Empty State -->
        <div class="customer-card">
            <div class="customer-card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-utensils fa-5x text-muted"></i>
                </div>
                <h5 class="mb-3">Bạn chưa có lịch sử chọn món nào</h5>
                <p class="text-muted mb-4">
                    Hãy khám phá các món ăn và bắt đầu xem, lưu hoặc nấu món bạn yêu thích!
                </p>
                <a href="{{ route('recommendations.index') }}" class="btn btn-primary">
                    <i class="fas fa-compass me-2"></i>Khám phá món ăn
                </a>
            </div>
        </div>
    @endif
</div>
@endsection


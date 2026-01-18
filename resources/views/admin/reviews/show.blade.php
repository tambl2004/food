@extends('layouts.admin.app')

@section('title', 'Chi tiết đánh giá')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết đánh giá</h2>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về đánh giá và phản hồi</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.reviews.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại</span>
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <!-- Review Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    Thông tin đánh giá
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID đánh giá</label>
                        <p class="text-gray-900 mt-1">#{{ $review->id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Điểm rating</label>
                        <div class="mt-1 flex items-center space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                                @else
                                    <i class="far fa-star text-gray-300 text-xl"></i>
                                @endif
                            @endfor
                            <span class="ml-2 text-lg font-semibold text-gray-900">({{ $review->rating }}/5)</span>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Nội dung đánh giá</label>
                        <div class="mt-1 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $review->comment ?: 'Người dùng không để lại bình luận.' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                        <div class="mt-1">
                            @if($review->status == 'visible')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-eye mr-1"></i>
                                    Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-eye-slash mr-1"></i>
                                    Ẩn
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Thời gian gửi</label>
                        <p class="text-gray-900 mt-1">{{ $review->created_at->format('d/m/Y H:i:s') }}</p>
                        <p class="text-xs text-gray-500 mt-1">({{ $review->created_at->diffForHumans() }})</p>
                    </div>
                </div>
            </div>

            <!-- User Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Người đánh giá
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tên người dùng</label>
                        <p class="text-gray-900 mt-1">{{ $review->user->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 mt-1">{{ $review->user->email ?? 'N/A' }}</p>
                    </div>

                    @if($review->user)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Vai trò</label>
                            <p class="text-gray-900 mt-1">
                                @if($review->user->role)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $review->user->role->value ?? $review->user->role }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Ngày tham gia</label>
                            <p class="text-gray-900 mt-1">{{ $review->user->created_at->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Dish Information Section -->
            @if($review->dish)
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-utensils mr-2 text-green-600"></i>
                        Món ăn được đánh giá
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tên món ăn</label>
                            <p class="text-gray-900 mt-1">{{ $review->dish->name }}</p>
                            @if($review->dish->category)
                                <p class="text-xs text-gray-500 mt-1">Danh mục: {{ $review->dish->category->name }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Độ khó</label>
                            <p class="text-gray-900 mt-1">
                                @if($review->dish->difficulty)
                                    @php
                                        $difficultyColors = [
                                            'easy' => 'bg-green-100 text-green-800',
                                            'medium' => 'bg-yellow-100 text-yellow-800',
                                            'hard' => 'bg-red-100 text-red-800',
                                        ];
                                        $difficultyLabels = [
                                            'easy' => 'Dễ',
                                            'medium' => 'Trung bình',
                                            'hard' => 'Khó',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $difficultyColors[$review->dish->difficulty] }}">
                                        {{ $difficultyLabels[$review->dish->difficulty] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <a href="{{ route('admin.dishes.show', $review->dish) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Xem chi tiết món ăn
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Context Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>
                    Ngữ cảnh đánh giá
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Người dùng đã nấu món này?</label>
                        <p class="text-gray-900 mt-1">
                            @if($hasCooked)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Có
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Chưa xác định
                                </span>
                            @endif
                        </p>
                    </div>

                    @if($hasCooked && $cookedAt)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Ngày nấu</label>
                            <p class="text-gray-900 mt-1">{{ $cookedAt->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                ({{ \Carbon\Carbon::parse($cookedAt)->diffInDays($review->created_at) }} ngày trước khi đánh giá)
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Actions Section -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cog mr-2 text-orange-600"></i>
                    Hành động quản trị
                </h3>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.reviews.updateStatus', $review) }}" class="inline-block">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="{{ $review->status == 'visible' ? 'hidden' : 'visible' }}">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 border {{ $review->status == 'visible' ? 'border-orange-300 text-orange-700 hover:bg-orange-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} shadow-sm text-sm font-medium rounded-lg bg-white transition"
                                onclick="return confirm('Bạn có chắc chắn muốn {{ $review->status == 'visible' ? 'ẩn' : 'hiển thị' }} đánh giá này?');">
                            <i class="fas fa-{{ $review->status == 'visible' ? 'eye-slash' : 'eye' }} mr-2"></i>
                            {{ $review->status == 'visible' ? 'Ẩn đánh giá' : 'Hiện đánh giá' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" 
                          class="inline-block"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này? Hành động này không thể hoàn tác.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 border border-red-300 text-red-700 hover:bg-red-50 shadow-sm text-sm font-medium rounded-lg bg-white transition">
                            <i class="fas fa-trash mr-2"></i>
                            Xóa đánh giá
                        </button>
                    </form>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Lưu ý: Admin không thể chỉnh sửa nội dung đánh giá, chỉ có thể ẩn/hiện hoặc xóa.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection


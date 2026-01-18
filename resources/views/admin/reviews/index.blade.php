@extends('layouts.admin.app')

@section('title', 'Quản lý đánh giá & phản hồi')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý đánh giá & phản hồi</h2>
            <p class="text-gray-600 mt-1">Danh sách tất cả đánh giá và phản hồi của người dùng</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-blue-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số đánh giá</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalReviews }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Đánh giá hiển thị</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $visibleReviews }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye-slash text-red-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Đánh giá đã ẩn</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $hiddenReviews }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nội dung, tên user..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Điểm rating</label>
                <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} sao
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Món ăn</label>
                <select name="dish_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    @if(isset($dishes) && $dishes->count() > 0)
                        @foreach($dishes as $dish)
                            <option value="{{ $dish->id }}" {{ request('dish_id') == $dish->id ? 'selected' : '' }}>
                                {{ $dish->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex items-end">
                <div class="flex gap-2 w-full">
                    <button type="submit" class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
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

    <!-- Reviews List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Món ăn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">#{{ $review->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $review->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($review->dish)
                                    <div class="text-sm font-medium text-gray-900">{{ $review->dish->name }}</div>
                                    @if($review->dish->category)
                                        <div class="text-xs text-gray-500">{{ $review->dish->category->name }}</div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($review->status == 'visible')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-eye mr-1"></i>
                                        Hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-eye-slash mr-1"></i>
                                        Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $review->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $review->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.reviews.show', $review) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-purple-300 shadow-sm text-sm font-medium rounded-lg text-purple-700 bg-white hover:bg-purple-50 transition"
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.reviews.updateStatus', $review) }}" 
                                          class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $review->status == 'visible' ? 'hidden' : 'visible' }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border {{ $review->status == 'visible' ? 'border-orange-300 text-orange-700 hover:bg-orange-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} shadow-sm text-sm font-medium rounded-lg bg-white transition"
                                                title="{{ $review->status == 'visible' ? 'Ẩn đánh giá' : 'Hiện đánh giá' }}"
                                                onclick="return confirm('Bạn có chắc chắn muốn {{ $review->status == 'visible' ? 'ẩn' : 'hiển thị' }} đánh giá này?');">
                                            <i class="fas fa-{{ $review->status == 'visible' ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Chưa có đánh giá nào</p>
                                    <p class="text-gray-400 text-sm mt-2">Không tìm thấy đánh giá phù hợp với bộ lọc</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }} trong tổng số {{ $reviews->total() }} đánh giá
                    </div>
                    <div>
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


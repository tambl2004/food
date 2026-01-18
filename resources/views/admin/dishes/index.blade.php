@extends('layouts.admin.app')

@section('title', 'Quản lý món ăn')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý món ăn</h2>
            <p class="text-gray-600 mt-1">Danh sách tất cả món ăn trong hệ thống</p>
        </div>
        <div>
            <a href="{{ route('admin.dishes.create') }}" 
               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Thêm món ăn</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-blue-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số món ăn</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalDishes }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Món đang hiển thị</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $activeDishes }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye-slash text-red-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Món đã ẩn</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $inactiveDishes }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.dishes.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Tên món..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó</label>
                <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <div class="flex gap-2 w-full">
                    <button type="submit" class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <a href="{{ route('admin.dishes.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
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

    <!-- Dishes List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên món</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Độ khó</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượt nấu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm đánh giá</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dishes as $dish)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">#{{ $dish->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($dish->image)
                                        <img src="{{ $dish->image }}" 
                                             alt="{{ $dish->name }}" 
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $dish->name }}</div>
                                        @if($dish->origin)
                                            <div class="text-xs text-gray-500">{{ $dish->origin }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dish->category)
                                    <span class="text-sm text-gray-900">{{ $dish->category->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">Chưa phân loại</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dish->difficulty)
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $difficultyColors[$dish->difficulty] }}">
                                        {{ $difficultyLabels[$dish->difficulty] }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dish->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
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
                                <div class="text-sm text-gray-900">{{ $dish->cook_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($dish->review_count > 0)
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($dish->average_rating, 1) }}</span>
                                        <span class="text-xs text-gray-500">({{ $dish->review_count }})</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Chưa có đánh giá</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.dishes.show', $dish) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition"
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.dishes.edit', $dish) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.dishes.updateStatus', $dish) }}" 
                                          class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $dish->status === 'active' ? 'inactive' : 'active' }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border {{ $dish->status === 'active' ? 'border-orange-300 text-orange-700 hover:bg-orange-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} shadow-sm text-sm font-medium rounded-lg bg-white transition"
                                                title="{{ $dish->status === 'active' ? 'Ẩn món' : 'Hiển thị món' }}"
                                                onclick="return confirm('Bạn có chắc chắn muốn {{ $dish->status === 'active' ? 'ẩn' : 'hiển thị' }} món ăn này?');">
                                            <i class="fas fa-{{ $dish->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-utensils text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Chưa có món ăn nào</p>
                                    <p class="text-gray-400 text-sm mt-2">Không tìm thấy món ăn phù hợp với bộ lọc</p>
                                    <a href="{{ route('admin.dishes.create') }}" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        Thêm món ăn đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dishes->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ $dishes->firstItem() }} đến {{ $dishes->lastItem() }} trong tổng số {{ $dishes->total() }} món ăn
                    </div>
                    <div>
                        {{ $dishes->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


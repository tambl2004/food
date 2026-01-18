@extends('layouts.admin.app')

@section('title', 'Chi tiết món ăn')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết món ăn</h2>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về món ăn</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.dishes.edit', $dish) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                <i class="fas fa-edit"></i>
                <span>Chỉnh sửa</span>
            </a>
            <a href="{{ route('admin.dishes.index') }}" 
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
            <!-- Dish Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-utensils mr-2 text-blue-600"></i>
                    Thông tin món ăn
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($dish->image)
                        <div class="md:col-span-2">
                            <img src="{{ $dish->image }}" 
                                 alt="{{ $dish->name }}" 
                                 class="w-full h-64 object-cover rounded-lg">
                        </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Tên món ăn</label>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ $dish->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Danh mục</label>
                        <p class="text-gray-900 mt-1">
                            @if($dish->category)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $dish->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400">Chưa phân loại</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Nguồn gốc</label>
                        <p class="text-gray-900 mt-1">{{ $dish->origin ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                        <p class="text-gray-900 mt-1">
                            @if($dish->status === 'active')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-eye-slash mr-1"></i>
                                    Ẩn
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Độ khó</label>
                        <p class="text-gray-900 mt-1">
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
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $difficultyColors[$dish->difficulty] }}">
                                    {{ $difficultyLabels[$dish->difficulty] }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Mô tả</label>
                        <p class="text-gray-900 mt-1 whitespace-pre-wrap">{{ $dish->description }}</p>
                    </div>

                    @if($dish->video_url)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Video hướng dẫn</label>
                            <p class="text-gray-900 mt-1">
                                <a href="{{ $dish->video_url }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $dish->video_url }}
                                    <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cooking Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-green-600"></i>
                    Thông tin nấu
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Thời gian chuẩn bị</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dish->prep_time ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">phút</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Thời gian nấu</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dish->cook_time ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">phút</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Khẩu phần</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dish->servings ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">người</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Calories</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dish->calories ?? '-' }}</p>
                        <p class="text-xs text-gray-500 mt-1">kcal</p>
                    </div>
                </div>
            </div>

            <!-- Ingredients Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-list mr-2 text-purple-600"></i>
                    Nguyên liệu ({{ $statistics['ingredient_count'] }})
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nguyên liệu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Số lượng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đơn vị</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dish->ingredients as $ingredient)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $ingredient->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $ingredient->pivot->quantity ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $ingredient->pivot->unit ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ingredient->pivot->is_required)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Bắt buộc
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Tùy chọn
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Chưa có nguyên liệu nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                    Thống kê
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-3">
                            <i class="fas fa-list text-purple-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Số nguyên liệu</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['ingredient_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg mb-3">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Điểm đánh giá</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['average_rating'], 1) }}</p>
                        <p class="text-xs text-gray-500 mt-1">({{ $statistics['review_count'] }} đánh giá)</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-3">
                            <i class="fas fa-utensils text-green-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Số lượt nấu</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['cook_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-3">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Ngày tạo</p>
                        <p class="text-lg font-bold text-gray-900">{{ $dish->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $dish->created_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Actions Section -->
            <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
                <form method="POST" action="{{ route('admin.dishes.updateStatus', $dish) }}" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $dish->status === 'active' ? 'inactive' : 'active' }}">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 border {{ $dish->status === 'active' ? 'border-orange-300 text-orange-700 hover:bg-orange-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} shadow-sm text-sm font-medium rounded-lg bg-white transition"
                            onclick="return confirm('Bạn có chắc chắn muốn {{ $dish->status === 'active' ? 'ẩn' : 'hiển thị' }} món ăn này?');">
                        <i class="fas fa-{{ $dish->status === 'active' ? 'eye-slash' : 'eye' }} mr-2"></i>
                        {{ $dish->status === 'active' ? 'Ẩn món ăn' : 'Hiển thị món ăn' }}
                    </button>
                </form>
                <a href="{{ route('admin.dishes.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


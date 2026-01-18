@extends('layouts.admin.app')

@section('title', 'Quản lý danh mục món ăn')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý danh mục món ăn</h2>
            <p class="text-gray-600 mt-1">Danh sách tất cả danh mục trong hệ thống</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}" 
               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Thêm danh mục</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số danh mục</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalCategories }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Danh mục đang hiển thị</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $visibleCategories }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye-slash text-red-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Danh mục đã ẩn</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $hiddenCategories }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Tên danh mục..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại bữa ăn</label>
                <select name="meal_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>Sáng</option>
                    <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>Trưa</option>
                    <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>Tối</option>
                    <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>Đồ ăn vặt</option>
                    <option value="dessert" {{ request('meal_type') == 'dessert' ? 'selected' : '' }}>Tráng miệng</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại ăn kiêng</label>
                <select name="diet_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="vegetarian" {{ request('diet_type') == 'vegetarian' ? 'selected' : '' }}>Chay</option>
                    <option value="vegan" {{ request('diet_type') == 'vegan' ? 'selected' : '' }}>Thuần chay</option>
                    <option value="keto" {{ request('diet_type') == 'keto' ? 'selected' : '' }}>Keto</option>
                    <option value="low-carb" {{ request('diet_type') == 'low-carb' ? 'selected' : '' }}>Ít carb</option>
                    <option value="gluten-free" {{ request('diet_type') == 'gluten-free' ? 'selected' : '' }}>Không gluten</option>
                    <option value="halal" {{ request('diet_type') == 'halal' ? 'selected' : '' }}>Halal</option>
                    <option value="regular" {{ request('diet_type') == 'regular' ? 'selected' : '' }}>Thông thường</option>
                </select>
            </div>
            <div class="flex items-end">
                <div class="flex gap-2 w-full">
                    <button type="submit" class="flex-1 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
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

    <!-- Categories List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại bữa ăn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại ăn kiêng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số món ăn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">#{{ $category->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-folder text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->meal_type)
                                    @php
                                        $mealTypeLabels = [
                                            'breakfast' => 'Sáng',
                                            'lunch' => 'Trưa',
                                            'dinner' => 'Tối',
                                            'snack' => 'Đồ ăn vặt',
                                            'dessert' => 'Tráng miệng',
                                        ];
                                        $mealTypeColors = [
                                            'breakfast' => 'bg-yellow-100 text-yellow-800',
                                            'lunch' => 'bg-blue-100 text-blue-800',
                                            'dinner' => 'bg-purple-100 text-purple-800',
                                            'snack' => 'bg-orange-100 text-orange-800',
                                            'dessert' => 'bg-pink-100 text-pink-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mealTypeColors[$category->meal_type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $mealTypeLabels[$category->meal_type] ?? $category->meal_type }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->diet_type)
                                    @php
                                        $dietTypeLabels = [
                                            'vegetarian' => 'Chay',
                                            'vegan' => 'Thuần chay',
                                            'keto' => 'Keto',
                                            'low-carb' => 'Ít carb',
                                            'gluten-free' => 'Không gluten',
                                            'halal' => 'Halal',
                                            'regular' => 'Thông thường',
                                        ];
                                        $dietTypeColors = [
                                            'vegetarian' => 'bg-green-100 text-green-800',
                                            'vegan' => 'bg-emerald-100 text-emerald-800',
                                            'keto' => 'bg-indigo-100 text-indigo-800',
                                            'low-carb' => 'bg-cyan-100 text-cyan-800',
                                            'gluten-free' => 'bg-amber-100 text-amber-800',
                                            'halal' => 'bg-teal-100 text-teal-800',
                                            'regular' => 'bg-gray-100 text-gray-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dietTypeColors[$category->diet_type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $dietTypeLabels[$category->diet_type] ?? $category->diet_type }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $category->dishes_count ?? 0 }} món</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($category->status == 1)
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
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-sm font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.updateStatus', $category) }}" 
                                          class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $category->status == 1 ? 0 : 1 }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border {{ $category->status == 1 ? 'border-orange-300 text-orange-700 hover:bg-orange-50' : 'border-green-300 text-green-700 hover:bg-green-50' }} shadow-sm text-sm font-medium rounded-lg bg-white transition"
                                                title="{{ $category->status == 1 ? 'Ẩn danh mục' : 'Hiển thị danh mục' }}"
                                                onclick="return confirm('Bạn có chắc chắn muốn {{ $category->status == 1 ? 'ẩn' : 'hiển thị' }} danh mục này?');">
                                            <i class="fas fa-{{ $category->status == 1 ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Hành động này không thể hoàn tác!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Chưa có danh mục nào</p>
                                    <p class="text-gray-400 text-sm mt-2">Không tìm thấy danh mục phù hợp với bộ lọc</p>
                                    <a href="{{ route('admin.categories.create') }}" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        Thêm danh mục đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ $categories->firstItem() }} đến {{ $categories->lastItem() }} trong tổng số {{ $categories->total() }} danh mục
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


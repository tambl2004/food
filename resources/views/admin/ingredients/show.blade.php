@extends('layouts.admin.app')

@section('title', 'Chi tiết nguyên liệu')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết nguyên liệu</h2>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về nguyên liệu</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ingredients.edit', $ingredient) }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                <i class="fas fa-edit"></i>
                <span>Chỉnh sửa</span>
            </a>
            <a href="{{ route('admin.ingredients.index') }}" 
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
            <!-- Ingredient Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-carrot mr-2 text-blue-600"></i>
                    Thông tin nguyên liệu
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Tên nguyên liệu</label>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ $ingredient->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Loại nguyên liệu</label>
                        <p class="text-gray-900 mt-1">
                            @if($ingredient->type)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $ingredient->type }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                        <p class="text-gray-900 mt-1">
                            @if($ingredient->status === 'active')
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

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Mô tả</label>
                        <p class="text-gray-900 mt-1 whitespace-pre-wrap">{{ $ingredient->description ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Nutrition Information Section -->
            @if($ingredient->nutrition)
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                    Thông tin dinh dưỡng (tính trên 100g)
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Calories</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $ingredient->nutrition->calories ?? '-' }}
                            @if($ingredient->nutrition->calories)
                                <span class="text-sm font-normal text-gray-500">Kcal</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Protein</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $ingredient->nutrition->protein ?? '-' }}
                            @if($ingredient->nutrition->protein)
                                <span class="text-sm font-normal text-gray-500">g</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Chất béo</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $ingredient->nutrition->fat ?? '-' }}
                            @if($ingredient->nutrition->fat)
                                <span class="text-sm font-normal text-gray-500">g</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Carb</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $ingredient->nutrition->carbs ?? '-' }}
                            @if($ingredient->nutrition->carbs)
                                <span class="text-sm font-normal text-gray-500">g</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Chất xơ</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $ingredient->nutrition->fiber ?? '-' }}
                            @if($ingredient->nutrition->fiber)
                                <span class="text-sm font-normal text-gray-500">g</span>
                            @endif
                        </p>
                    </div>
                </div>

                @if($ingredient->nutrition->vitamins)
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-500">Vitamin</label>
                    <p class="text-gray-900 mt-1">{{ $ingredient->nutrition->vitamins }}</p>
                </div>
                @endif
            </div>
            @else
            <div class="border-b border-gray-200 pb-6 mb-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <p class="text-sm text-yellow-800">Chưa có thông tin dinh dưỡng cho nguyên liệu này</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Statistics Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-purple-600"></i>
                    Thống kê
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Số món sử dụng</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $usedInDishesCount }}</p>
                        <p class="text-xs text-gray-400 mt-1">Món ăn đang sử dụng nguyên liệu này</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Lượt quét camera</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $cameraScanCount }}</p>
                        <p class="text-xs text-gray-400 mt-1">Số lần được quét bằng camera</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Người dùng đang có</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userHaveCount }}</p>
                        <p class="text-xs text-gray-400 mt-1">Số user đang có nguyên liệu này</p>
                    </div>
                </div>
            </div>

            <!-- Used in Dishes Section -->
            @if($ingredient->dishes->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-utensils mr-2 text-orange-600"></i>
                    Món ăn đang sử dụng nguyên liệu này ({{ $ingredient->dishes->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($ingredient->dishes as $dish)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1">
                                        <a href="{{ route('admin.dishes.show', $dish) }}" class="hover:text-blue-600 transition">
                                            {{ $dish->name }}
                                        </a>
                                    </h4>
                                    @if($dish->category)
                                        <p class="text-xs text-gray-500 mb-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $dish->category->name }}
                                            </span>
                                        </p>
                                    @endif
                                    @if($dish->pivot->quantity)
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-weight mr-1"></i>
                                            {{ $dish->pivot->quantity }} {{ $dish->pivot->unit ?? '' }}
                                            @if($dish->pivot->is_required)
                                                <span class="ml-2 text-xs text-red-600">(Bắt buộc)</span>
                                            @else
                                                <span class="ml-2 text-xs text-gray-500">(Tùy chọn)</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
            <div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Nguyên liệu này chưa được sử dụng trong món ăn nào</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


@extends('layouts.admin.app')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thêm danh mục mới</h2>
            <p class="text-gray-600 mt-1">Nhập thông tin danh mục món ăn</p>
        </div>
        <div>
            <a href="{{ route('admin.categories.index') }}" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại</span>
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
        @csrf

        <!-- Thông tin cơ bản -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Thông tin cơ bản
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên danh mục <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           placeholder="Ví dụ: Món canh, Món chiên..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="description" 
                              rows="4" 
                              placeholder="Mô tả về danh mục này..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Phân loại -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tags mr-2 text-green-600"></i>
                Phân loại
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại bữa ăn</label>
                    <select name="meal_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Chọn loại bữa ăn</option>
                        <option value="breakfast" {{ old('meal_type') == 'breakfast' ? 'selected' : '' }}>Sáng</option>
                        <option value="lunch" {{ old('meal_type') == 'lunch' ? 'selected' : '' }}>Trưa</option>
                        <option value="dinner" {{ old('meal_type') == 'dinner' ? 'selected' : '' }}>Tối</option>
                        <option value="snack" {{ old('meal_type') == 'snack' ? 'selected' : '' }}>Đồ ăn vặt</option>
                        <option value="dessert" {{ old('meal_type') == 'dessert' ? 'selected' : '' }}>Tráng miệng</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Phân loại theo thời gian trong ngày</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại ăn kiêng</label>
                    <select name="diet_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Chọn loại ăn kiêng</option>
                        <option value="vegetarian" {{ old('diet_type') == 'vegetarian' ? 'selected' : '' }}>Chay</option>
                        <option value="vegan" {{ old('diet_type') == 'vegan' ? 'selected' : '' }}>Thuần chay</option>
                        <option value="keto" {{ old('diet_type') == 'keto' ? 'selected' : '' }}>Keto</option>
                        <option value="low-carb" {{ old('diet_type') == 'low-carb' ? 'selected' : '' }}>Ít carb</option>
                        <option value="gluten-free" {{ old('diet_type') == 'gluten-free' ? 'selected' : '' }}>Không gluten</option>
                        <option value="halal" {{ old('diet_type') == 'halal' ? 'selected' : '' }}>Halal</option>
                        <option value="regular" {{ old('diet_type') == 'regular' ? 'selected' : '' }}>Thông thường</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Phân loại theo chế độ ăn uống</p>
                </div>
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-toggle-on mr-2 text-purple-600"></i>
                Trạng thái
            </h3>
            <div class="flex items-center space-x-4">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" 
                           name="status" 
                           value="1" 
                           {{ old('status', '1') == '1' ? 'checked' : '' }}
                           class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-eye text-green-600 mr-1"></i>
                        Hiển thị
                    </span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" 
                           name="status" 
                           value="0" 
                           {{ old('status') == '0' ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-eye-slash text-red-600 mr-1"></i>
                        Ẩn
                    </span>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Danh mục ẩn sẽ không hiển thị trên trang web</p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Hủy
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Lưu danh mục</span>
            </button>
        </div>
    </form>
</div>
@endsection


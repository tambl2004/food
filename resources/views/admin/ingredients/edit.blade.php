@extends('layouts.admin.app')

@section('title', 'Sửa nguyên liệu')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Sửa nguyên liệu</h2>
            <p class="text-gray-600 mt-1">Cập nhật thông tin nguyên liệu và dinh dưỡng</p>
        </div>
        <div>
            <a href="{{ route('admin.ingredients.index') }}" 
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

    <form method="POST" action="{{ route('admin.ingredients.update', $ingredient) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Thông tin cơ bản -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Thông tin cơ bản
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tên nguyên liệu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $ingredient->name) }}" 
                           required
                           placeholder="Ví dụ: Thịt heo, Rau cải, Gạo..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Loại nguyên liệu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="type" 
                           value="{{ old('type', $ingredient->type) }}" 
                           required
                           placeholder="Ví dụ: Rau, Thịt, Gia vị, Ngũ cốc..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Phân loại nguyên liệu (rau, thịt, gia vị, v.v.)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="description" 
                              rows="3" 
                              placeholder="Mô tả về nguyên liệu này..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $ingredient->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Thông tin dinh dưỡng -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                Thông tin dinh dưỡng (tính trên 100g)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Calories (Kcal)</label>
                    <input type="number" 
                           name="calories" 
                           value="{{ old('calories', $ingredient->nutrition->calories ?? '') }}" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Protein (g)</label>
                    <input type="number" 
                           name="protein" 
                           value="{{ old('protein', $ingredient->nutrition->protein ?? '') }}" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chất béo (g)</label>
                    <input type="number" 
                           name="fat" 
                           value="{{ old('fat', $ingredient->nutrition->fat ?? '') }}" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Carb (g)</label>
                    <input type="number" 
                           name="carbs" 
                           value="{{ old('carbs', $ingredient->nutrition->carbs ?? '') }}" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chất xơ (g)</label>
                    <input type="number" 
                           name="fiber" 
                           value="{{ old('fiber', $ingredient->nutrition->fiber ?? '') }}" 
                           step="0.01"
                           min="0"
                           placeholder="0.00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vitamin</label>
                    <input type="text" 
                           name="vitamins" 
                           value="{{ old('vitamins', $ingredient->nutrition->vitamins ?? '') }}" 
                           placeholder="Ví dụ: Vitamin A, C, D..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500">Thông tin dinh dưỡng là tùy chọn, có thể cập nhật sau</p>
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
                           value="active" 
                           {{ old('status', $ingredient->status) == 'active' ? 'checked' : '' }}
                           class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-eye text-green-600 mr-1"></i>
                        Hiển thị
                    </span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" 
                           name="status" 
                           value="inactive" 
                           {{ old('status', $ingredient->status) == 'inactive' ? 'checked' : '' }}
                           class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                    <span class="text-sm font-medium text-gray-700">
                        <i class="fas fa-eye-slash text-red-600 mr-1"></i>
                        Ẩn
                    </span>
                </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">Nguyên liệu ẩn sẽ không hiển thị cho người dùng và không được AI gợi ý</p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.ingredients.index') }}" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Hủy
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Cập nhật nguyên liệu</span>
            </button>
        </div>
    </form>
</div>
@endsection


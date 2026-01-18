@extends('layouts.admin.app')

@section('title', 'Thêm món ăn mới')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thêm món ăn mới</h2>
            <p class="text-gray-600 mt-1">Nhập thông tin món ăn và nguyên liệu</p>
        </div>
        <div>
            <a href="{{ route('admin.dishes.index') }}" 
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

    <form method="POST" action="{{ route('admin.dishes.store') }}" class="space-y-6">
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
                        Tên món ăn <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                    <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nguồn gốc</label>
                    <input type="text" 
                           name="origin" 
                           value="{{ old('origin') }}" 
                           placeholder="Ví dụ: Việt Nam, Trung Quốc..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" 
                              rows="4" 
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh món</label>
                    <input type="text" 
                           name="image" 
                           value="{{ old('image') }}" 
                           placeholder="URL ảnh..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Video hướng dẫn (YouTube URL)</label>
                    <input type="url" 
                           name="video_url" 
                           value="{{ old('video_url') }}" 
                           placeholder="https://youtube.com/..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Thông tin nấu -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock mr-2 text-green-600"></i>
                Thông tin nấu
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó</label>
                    <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Chọn độ khó</option>
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian chuẩn bị (phút)</label>
                    <input type="number" 
                           name="prep_time" 
                           value="{{ old('prep_time') }}" 
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Thời gian nấu (phút) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="cook_time" 
                           value="{{ old('cook_time') }}" 
                           required
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Khẩu phần</label>
                    <input type="number" 
                           name="servings" 
                           value="{{ old('servings') }}" 
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Calories</label>
                    <input type="number" 
                           name="calories" 
                           value="{{ old('calories') }}" 
                           min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Quản lý nguyên liệu -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-list mr-2 text-purple-600"></i>
                    Nguyên liệu <span class="text-red-500 ml-1">*</span>
                </span>
                <button type="button" 
                        onclick="addIngredient()" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                    <i class="fas fa-plus mr-1"></i>
                    Thêm nguyên liệu
                </button>
            </h3>

            <div id="ingredients-container" class="space-y-4">
                @if(old('ingredients'))
                    @foreach(old('ingredients') as $index => $ingredient)
                        <div class="ingredient-row flex gap-4 items-start p-4 border border-gray-200 rounded-lg">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nguyên liệu</label>
                                <select name="ingredients[{{ $index }}][ingredient_id]" 
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    <option value="">Chọn nguyên liệu</option>
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}" {{ $ingredient['ingredient_id'] == $ing->id ? 'selected' : '' }}>
                                            {{ $ing->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
                                <input type="text" 
                                       name="ingredients[{{ $index }}][quantity]" 
                                       value="{{ $ingredient['quantity'] ?? '' }}" 
                                       placeholder="200"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Đơn vị</label>
                                <input type="text" 
                                       name="ingredients[{{ $index }}][unit]" 
                                       value="{{ $ingredient['unit'] ?? '' }}" 
                                       placeholder="g, ml..."
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                            <div class="w-32 flex items-end">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" 
                                           name="ingredients[{{ $index }}][is_required]" 
                                           value="1"
                                           {{ ($ingredient['is_required'] ?? true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                    <span class="text-sm text-gray-700">Bắt buộc</span>
                                </label>
                            </div>
                            <div class="flex items-end">
                                <button type="button" 
                                        onclick="removeIngredient(this)" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="ingredient-row flex gap-4 items-start p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nguyên liệu</label>
                            <select name="ingredients[0][ingredient_id]" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="">Chọn nguyên liệu</option>
                                @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
                            <input type="text" 
                                   name="ingredients[0][quantity]" 
                                   placeholder="200"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="w-32">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Đơn vị</label>
                            <input type="text" 
                                   name="ingredients[0][unit]" 
                                   placeholder="g, ml..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="w-32 flex items-end">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="ingredients[0][is_required]" 
                                       value="1"
                                       checked
                                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="text-sm text-gray-700">Bắt buộc</span>
                            </label>
                        </div>
                        <div class="flex items-end">
                            <button type="button" 
                                    onclick="removeIngredient(this)" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.dishes.index') }}" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Hủy
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span>Lưu món ăn</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let ingredientIndex = {{ old('ingredients') ? count(old('ingredients')) : 1 }};
const allIngredients = @json($ingredients);

function addIngredient() {
    const container = document.getElementById('ingredients-container');
    const newRow = document.createElement('div');
    newRow.className = 'ingredient-row flex gap-4 items-start p-4 border border-gray-200 rounded-lg';
    newRow.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nguyên liệu</label>
            <select name="ingredients[${ingredientIndex}][ingredient_id]" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                <option value="">Chọn nguyên liệu</option>
                ${allIngredients.map(ing => `<option value="${ing.id}">${ing.name}</option>`).join('')}
            </select>
        </div>
        <div class="w-32">
            <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
            <input type="text" 
                   name="ingredients[${ingredientIndex}][quantity]" 
                   placeholder="200"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        </div>
        <div class="w-32">
            <label class="block text-sm font-medium text-gray-700 mb-2">Đơn vị</label>
            <input type="text" 
                   name="ingredients[${ingredientIndex}][unit]" 
                   placeholder="g, ml..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
        </div>
        <div class="w-32 flex items-end">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" 
                       name="ingredients[${ingredientIndex}][is_required]" 
                       value="1"
                       checked
                       class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                <span class="text-sm text-gray-700">Bắt buộc</span>
            </label>
        </div>
        <div class="flex items-end">
            <button type="button" 
                    onclick="removeIngredient(this)" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
    ingredientIndex++;
}

function removeIngredient(button) {
    const container = document.getElementById('ingredients-container');
    const rows = container.querySelectorAll('.ingredient-row');
    if (rows.length > 1) {
        button.closest('.ingredient-row').remove();
    } else {
        alert('Món ăn phải có ít nhất 1 nguyên liệu!');
    }
}
</script>
@endsection


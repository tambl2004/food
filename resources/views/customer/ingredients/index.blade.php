@extends('layouts.customer')

@section('title', 'Tủ lạnh của tôi')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-snowflake text-white text-xl"></i>
                        </div>
                        <span>Tủ lạnh của tôi</span>
                    </h1>
                    <p class="text-gray-600 text-lg">Quản lý nguyên liệu trong bếp của bạn</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Thêm nguyên liệu</span>
                    </button>
                    @if($userIngredients->count() > 0)
                        <a href="{{ route('user.ingredients.find-dishes') }}" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-search"></i>
                            <span>Tìm món ăn</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            @if($userIngredients->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Tổng nguyên liệu</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $userIngredients->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-box text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    @php
                        $expiringCount = $userIngredients->filter(function($item) {
                            if (!$item->added_at) return false;
                            return now()->diffInDays($item->added_at) >= 2;
                        })->count();
                    @endphp
                    <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Sắp hết hạn</p>
                                <p class="text-3xl font-bold {{ $expiringCount > 0 ? 'text-orange-600' : 'text-gray-900' }} mt-1">{{ $expiringCount }}</p>
                            </div>
                            <div class="w-12 h-12 {{ $expiringCount > 0 ? 'bg-orange-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle {{ $expiringCount > 0 ? 'text-orange-600' : 'text-gray-400' }} text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Loại nguyên liệu</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $ingredientTypes->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tags text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Món có thể nấu</p>
                                <p class="text-3xl font-bold {{ $cookableDishesCount > 0 ? 'text-green-600' : 'text-gray-900' }} mt-1">{{ $cookableDishesCount }}</p>
                            </div>
                            <div class="w-12 h-12 {{ $cookableDishesCount > 0 ? 'bg-green-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                                <i class="fas fa-utensils {{ $cookableDishesCount > 0 ? 'text-green-600' : 'text-gray-400' }} text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Expiring Warning -->
        @if($userIngredients->count() > 0)
            @php
                $expiringIngredients = $userIngredients->filter(function($item) {
                    if (!$item->added_at) return false;
                    $daysSinceAdded = now()->diffInDays($item->added_at);
                    return $daysSinceAdded >= 2;
                });
            @endphp
            
            @if($expiringIngredients->count() > 0)
                <div class="mb-6 bg-gradient-to-r from-orange-50 to-red-50 border-l-4 border-orange-500 p-5 rounded-xl shadow-md">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Cảnh báo nguyên liệu sắp hết hạn</h3>
                            <p class="text-gray-700 mb-3">Có <span class="font-bold text-orange-600">{{ $expiringIngredients->count() }}</span> nguyên liệu đã được thêm từ 2-3 ngày trước:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($expiringIngredients->take(5) as $item)
                                    @if($item->ingredient)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg shadow-sm border border-orange-200">
                                            <span class="font-medium text-gray-900">{{ $item->ingredient->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $item->added_at->diffForHumans() }}</span>
                                            <button onclick="confirmDelete({{ $item->id }})" class="text-red-500 hover:text-red-700 ml-1">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </span>
                                    @endif
                                @endforeach
                                @if($expiringIngredients->count() > 5)
                                    <span class="inline-flex items-center px-3 py-1.5 bg-white rounded-lg shadow-sm border border-orange-200 text-gray-600 text-sm">
                                        +{{ $expiringIngredients->count() - 5 }} khác
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: My Ingredients -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Category Tabs -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Nguyên liệu của tôi</h2>
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" 
                                       id="searchInput" 
                                       placeholder="Tìm kiếm..." 
                                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Pills -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button onclick="filterByCategory('')" class="category-tab px-4 py-2 rounded-full bg-blue-500 text-white font-medium text-sm transition-all duration-200 hover:bg-blue-600 active">
                            <i class="fas fa-th mr-1"></i>Tất cả
                        </button>
                        @foreach($ingredientTypes as $type)
                            <button onclick="filterByCategory('{{ $type }}')" class="category-tab px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium text-sm transition-all duration-200 hover:bg-gray-200" data-category="{{ $type }}">
                                {{ $type }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Ingredients Grid -->
                    @if($userIngredients->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="ingredientsList">
                            @foreach($userIngredients as $userIngredient)
                                @if($userIngredient->ingredient)
                                    @php
                                        $daysSinceAdded = $userIngredient->added_at ? now()->diffInDays($userIngredient->added_at) : null;
                                        $isExpiring = $daysSinceAdded !== null && $daysSinceAdded >= 2;
                                        $isExpired = $daysSinceAdded !== null && $daysSinceAdded >= 3;
                                    @endphp
                                    <div class="ingredient-item bg-gradient-to-br from-white to-gray-50 rounded-xl p-5 border-2 {{ $isExpired ? 'border-red-300 bg-red-50' : ($isExpiring ? 'border-orange-300 bg-orange-50' : 'border-gray-200') }} hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1"
                                         data-type="{{ $userIngredient->ingredient->type ?? '' }}"
                                         data-name="{{ strtolower($userIngredient->ingredient->name ?? '') }}">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h3 class="text-lg font-bold text-gray-900">{{ $userIngredient->ingredient->name }}</h3>
                                                    @if($isExpired)
                                                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Hết hạn</span>
                                                    @elseif($isExpiring)
                                                        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Sắp hết hạn</span>
                                                    @endif
                                                </div>
                                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">{{ $userIngredient->ingredient->type ?? 'Khác' }}</span>
                                            </div>
                                            <div class="relative">
                                                <button onclick="toggleDropdown({{ $userIngredient->id }})" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                    <i class="fas fa-ellipsis-v text-gray-400"></i>
                                                </button>
                                                <div id="dropdown-{{ $userIngredient->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-10">
                                                    <a href="#" onclick="openEditModal({{ $userIngredient->id }}, {{ $userIngredient->ingredient_id }}, {{ json_encode($userIngredient->ingredient->name) }}, {{ json_encode($userIngredient->quantity ?? '') }}, {{ json_encode($userIngredient->unit ?? '') }}); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                                        <i class="fas fa-edit text-blue-500"></i>Chỉnh sửa
                                                    </a>
                                                    <a href="#" onclick="confirmDelete({{ $userIngredient->id }}); return false;" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center gap-2">
                                                        <i class="fas fa-trash"></i>Xóa
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @if($userIngredient->quantity || $userIngredient->unit)
                                            <div class="mb-3 p-3 bg-white rounded-lg border border-gray-200">
                                                <p class="text-xs text-gray-500 mb-1">Số lượng</p>
                                                <p class="text-sm font-semibold text-gray-900">
                                                    @if($userIngredient->quantity)
                                                        {{ $userIngredient->quantity }}
                                                    @endif
                                                    @if($userIngredient->unit)
                                                        <span class="text-gray-600">{{ $userIngredient->unit }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                        @if($userIngredient->added_at)
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>Thêm: {{ $userIngredient->added_at->format('d/m/Y') }}</span>
                                                <span class="text-gray-400">•</span>
                                                <i class="fas fa-clock"></i>
                                                <span>{{ $userIngredient->added_at->diffForHumans() }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-snowflake text-gray-400 text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Tủ lạnh của bạn đang trống</h3>
                            <p class="text-gray-600 mb-6">Hãy thêm nguyên liệu để bắt đầu!</p>
                            <button onclick="openAddModal()" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>Thêm nguyên liệu ngay
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Browse All Ingredients -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-list text-purple-600"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Duyệt nguyên liệu</h2>
                            <p class="text-xs text-gray-500">Thêm vào tủ lạnh</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2" id="allIngredientsList">
                        @php
                            $allIngredients = \App\Models\Ingredient::where('status', 'active')
                                ->orderBy('name')
                                ->get();
                            $userIngredientIds = $userIngredients->pluck('ingredient_id')->toArray();
                        @endphp
                        @foreach($allIngredients as $ingredient)
                            @php
                                $isAdded = in_array($ingredient->id, $userIngredientIds);
                            @endphp
                            <div class="ingredient-browse-item p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors flex items-center justify-between group"
                                 data-type="{{ $ingredient->type ?? '' }}"
                                 data-name="{{ strtolower($ingredient->name) }}">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $ingredient->name }}</p>
                                    @if($ingredient->type)
                                        <p class="text-xs text-gray-500">{{ $ingredient->type }}</p>
                                    @endif
                                </div>
                                @if($isAdded)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full flex items-center gap-1">
                                        <i class="fas fa-check"></i>Đã thêm
                                    </span>
                                @else
                                    <button onclick="quickAddIngredient({{ $ingredient->id }}, '{{ addslashes($ingredient->name) }}')" class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-1">
                                        <i class="fas fa-plus"></i>Thêm
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="ingredientModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-2xl font-bold text-gray-900">Thêm nguyên liệu</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <form id="ingredientForm" method="POST" class="p-6">
            @csrf
            <div id="formMethod"></div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Chọn nguyên liệu <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" 
                           id="ingredient_search" 
                           placeholder="Tìm kiếm nguyên liệu..."
                           autocomplete="off"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="hidden" id="ingredient_id" name="ingredient_id">
                    <div id="ingredientDropdown" class="hidden absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl max-h-60 overflow-y-auto"></div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số lượng</label>
                    <input type="text" 
                           id="quantity" 
                           name="quantity" 
                           placeholder="VD: 500"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Đơn vị</label>
                    <input type="text" 
                           id="unit" 
                           name="unit" 
                           placeholder="VD: g, kg, quả"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Hủy
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Lưu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Xác nhận xóa</h3>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-6">Bạn có chắc chắn muốn xóa nguyên liệu này khỏi tủ lạnh?</p>
            <form id="deleteForm" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                    Hủy
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Xóa
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let searchTimeout;
let selectedIngredientId = null;
let selectedIngredientName = '';
let currentCategory = '';

// Modal functions
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Thêm nguyên liệu';
    document.getElementById('ingredientForm').action = '{{ route("user.ingredients.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('ingredient_id').value = '';
    document.getElementById('ingredient_search').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    selectedIngredientId = null;
    document.getElementById('ingredientDropdown').classList.add('hidden');
    document.getElementById('ingredientModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('ingredientModal').classList.add('hidden');
}

function openEditModal(userIngredientId, ingredientId, ingredientName, quantity, unit) {
    document.getElementById('modalTitle').textContent = 'Chỉnh sửa nguyên liệu';
    document.getElementById('ingredientForm').action = `/user/ingredients/${userIngredientId}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('ingredient_id').value = ingredientId;
    document.getElementById('ingredient_search').value = typeof ingredientName === 'string' ? ingredientName : '';
    document.getElementById('quantity').value = typeof quantity === 'string' ? quantity : '';
    document.getElementById('unit').value = typeof unit === 'string' ? unit : '';
    selectedIngredientId = ingredientId;
    document.getElementById('ingredientDropdown').classList.add('hidden');
    document.getElementById('ingredientModal').classList.remove('hidden');
}

function confirmDelete(userIngredientId) {
    document.getElementById('deleteForm').action = `/user/ingredients/${userIngredientId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function toggleDropdown(id) {
    const dropdown = document.getElementById(`dropdown-${id}`);
    document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
        if (d.id !== `dropdown-${id}`) d.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');
}

// Quick add ingredient - mở modal với form
function quickAddIngredient(ingredientId, ingredientName) {
    document.getElementById('modalTitle').textContent = 'Thêm nguyên liệu';
    document.getElementById('ingredientForm').action = '{{ route("user.ingredients.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('ingredient_id').value = ingredientId;
    document.getElementById('ingredient_search').value = ingredientName;
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    selectedIngredientId = ingredientId;
    document.getElementById('ingredientDropdown').classList.add('hidden');
    document.getElementById('ingredientModal').classList.remove('hidden');
}

// Filter by category
function filterByCategory(category) {
    currentCategory = category;
    const tabs = document.querySelectorAll('.category-tab');
    tabs.forEach(tab => {
        if (tab.getAttribute('data-category') === category || (category === '' && tab.classList.contains('active'))) {
            tab.classList.remove('bg-gray-100', 'text-gray-700');
            tab.classList.add('bg-blue-500', 'text-white');
            tab.classList.add('active');
        } else {
            tab.classList.remove('bg-blue-500', 'text-white', 'active');
            tab.classList.add('bg-gray-100', 'text-gray-700');
        }
    });
    
    const items = document.querySelectorAll('.ingredient-item, .ingredient-browse-item');
    items.forEach(item => {
        const itemType = item.getAttribute('data-type') || '';
        if (category === '' || itemType === category) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const items = document.querySelectorAll('.ingredient-item, .ingredient-browse-item');
    
    items.forEach(item => {
        const itemName = item.getAttribute('data-name') || '';
        const itemType = item.getAttribute('data-type') || '';
        
        const matchesSearch = searchTerm === '' || itemName.includes(searchTerm);
        const matchesCategory = currentCategory === '' || itemType === currentCategory;
        
        if (matchesSearch && matchesCategory) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

// Ingredient search autocomplete
document.getElementById('ingredient_search').addEventListener('input', function(e) {
    const searchTerm = e.target.value;
    const dropdown = document.getElementById('ingredientDropdown');
    
    if (searchTerm.length < 2) {
        dropdown.classList.add('hidden');
        return;
    }
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('api.user.ingredients.search') }}?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    dropdown.innerHTML = '';
                    data.data.forEach(ingredient => {
                        const item = document.createElement('div');
                        item.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0';
                        item.innerHTML = `
                            <div class="font-medium text-gray-900">${ingredient.name}</div>
                            <div class="text-xs text-gray-500">${ingredient.type || 'Khác'}</div>
                        `;
                        item.addEventListener('click', function(e) {
                            e.preventDefault();
                            selectedIngredientId = ingredient.id;
                            selectedIngredientName = ingredient.name;
                            document.getElementById('ingredient_id').value = ingredient.id;
                            document.getElementById('ingredient_search').value = ingredient.name;
                            dropdown.classList.add('hidden');
                        });
                        dropdown.appendChild(item);
                    });
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.innerHTML = '<div class="p-3 text-gray-500 text-center">Không tìm thấy nguyên liệu</div>';
                    dropdown.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }, 300);
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('ingredientDropdown');
    const searchInput = document.getElementById('ingredient_search');
    if (!dropdown.contains(e.target) && !searchInput.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// Form validation
document.getElementById('ingredientForm').addEventListener('submit', function(e) {
    if (!document.getElementById('ingredient_id').value) {
        e.preventDefault();
        alert('Vui lòng chọn nguyên liệu!');
        return false;
    }
});

// Close modals on outside click
document.getElementById('ingredientModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection

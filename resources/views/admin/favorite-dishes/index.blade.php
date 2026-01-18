@extends('layouts.admin.app')

@section('title', 'Quản lý món ăn yêu thích')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý món ăn yêu thích</h2>
            <p class="text-gray-600 mt-1">Danh sách tất cả món ăn được người dùng yêu thích</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-red-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số yêu thích</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalFavorites }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Người dùng có yêu thích</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Món ăn được yêu thích</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalProducts }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('admin.favorite-dishes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm món ăn</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nhập tên món ăn..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm người dùng</label>
                <input type="text" 
                       name="user_search" 
                       value="{{ request('user_search') }}" 
                       placeholder="Nhập tên hoặc email..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lọc theo người dùng</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Tất cả người dùng</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex gap-2">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                    <span>Tìm kiếm</span>
                </button>
                <a href="{{ route('admin.favorite-dishes.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                    <i class="fas fa-redo"></i>
                    <span>Làm mới</span>
                </a>
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

    <!-- Favorite Dishes List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STT</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Món ăn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người dùng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian nấu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày lưu</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($favoriteDishes as $index => $favorite)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">#{{ $favoriteDishes->firstItem() + $index }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($favorite->product->image)
                                        <img src="{{ $favorite->product->image }}" alt="{{ $favorite->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $favorite->product->name }}</div>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-heart mr-1"></i>
                                                Yêu thích
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($favorite->product->category)
                                    <span class="text-sm text-gray-900">{{ $favorite->product->category->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $favorite->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $favorite->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($favorite->product->prep_time || $favorite->product->cook_time)
                                    <div class="text-sm text-gray-900">
                                        @if($favorite->product->prep_time)
                                            <span>Chuẩn bị: {{ $favorite->product->prep_time }} phút</span>
                                        @endif
                                        @if($favorite->product->cook_time)
                                            <span class="block">Nấu: {{ $favorite->product->cook_time }} phút</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $favorite->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $favorite->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.favorite-dishes.show', $favorite) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                        <i class="fas fa-eye mr-1"></i>
                                        Xem chi tiết
                                    </a>
                                    <form method="POST" action="{{ route('admin.favorite-dishes.destroy', $favorite) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa món ăn yêu thích này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition">
                                            <i class="fas fa-trash mr-1"></i>
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-heart text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Chưa có món ăn yêu thích nào</p>
                                    <p class="text-gray-400 text-sm mt-2">Người dùng sẽ lưu các món ăn yêu thích tại đây</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($favoriteDishes->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ $favoriteDishes->firstItem() }} đến {{ $favoriteDishes->lastItem() }} trong tổng số {{ $favoriteDishes->total() }} mục
                    </div>
                    <div>
                        {{ $favoriteDishes->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


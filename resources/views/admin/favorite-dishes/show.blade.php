@extends('layouts.admin.app')

@section('title', 'Chi tiết món ăn yêu thích')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết món ăn yêu thích</h2>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về món ăn được người dùng yêu thích</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.favorite-dishes.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại</span>
            </a>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <!-- Product Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-utensils mr-2 text-green-600"></i>
                    Thông tin món ăn
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Image -->
                    <div>
                        @if($favoriteDish->product->image)
                            <img src="{{ $favoriteDish->product->image }}" 
                                 alt="{{ $favoriteDish->product->name }}" 
                                 class="w-full h-64 object-cover rounded-lg border border-gray-200">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center border border-gray-200">
                                <i class="fas fa-image text-gray-400 text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tên món ăn</label>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $favoriteDish->product->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Danh mục</label>
                            <p class="text-gray-900 mt-1">
                                @if($favoriteDish->product->category)
                                    {{ $favoriteDish->product->category->name }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Mô tả</label>
                            <p class="text-gray-900 mt-1">{{ Str::limit($favoriteDish->product->description, 200) }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            @if($favoriteDish->product->prep_time)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Thời gian chuẩn bị</label>
                                    <p class="text-gray-900 mt-1">{{ $favoriteDish->product->prep_time }} phút</p>
                                </div>
                            @endif

                            @if($favoriteDish->product->cook_time)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Thời gian nấu</label>
                                    <p class="text-gray-900 mt-1">{{ $favoriteDish->product->cook_time }} phút</p>
                                </div>
                            @endif

                            @if($favoriteDish->product->servings)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Số phần ăn</label>
                                    <p class="text-gray-900 mt-1">{{ $favoriteDish->product->servings }} phần</p>
                                </div>
                            @endif

                            @if($favoriteDish->product->difficulty)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Độ khó</label>
                                    <p class="text-gray-900 mt-1">
                                        @if($favoriteDish->product->difficulty == 'easy')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-medium">Dễ</span>
                                        @elseif($favoriteDish->product->difficulty == 'medium')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm font-medium">Trung bình</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm font-medium">Khó</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if($favoriteDish->product->video_url)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Video hướng dẫn</label>
                                <a href="{{ $favoriteDish->product->video_url }}" 
                                   target="_blank"
                                   class="text-green-600 hover:text-green-700 mt-1 inline-flex items-center space-x-1">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Xem video</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('products.show', $favoriteDish->product->id) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Xem chi tiết món ăn
                    </a>
                </div>
            </div>

            <!-- User Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Thông tin người dùng
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tên người dùng</label>
                        <p class="text-gray-900 mt-1 font-medium">{{ $favoriteDish->user->name }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 mt-1">{{ $favoriteDish->user->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Tổng số món yêu thích</label>
                        <p class="text-gray-900 mt-1 font-semibold">{{ $userFavoriteCount }} món</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Vai trò</label>
                        <p class="text-gray-900 mt-1">
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm font-medium">
                                {{ ucfirst($favoriteDish->user->role->value) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Favorite Information Section -->
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-heart mr-2 text-red-600"></i>
                    Thông tin yêu thích
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ngày lưu yêu thích</label>
                        <p class="text-gray-900 mt-1">{{ $favoriteDish->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $favoriteDish->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Số người yêu thích món này</label>
                        <p class="text-gray-900 mt-1 font-semibold">{{ $productFavoriteCount }} người</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.favorite-dishes.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Quay lại danh sách
                </a>
                <form method="POST" 
                      action="{{ route('admin.favorite-dishes.destroy', $favoriteDish) }}" 
                      class="inline-block"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa món ăn yêu thích này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>
                        Xóa yêu thích
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


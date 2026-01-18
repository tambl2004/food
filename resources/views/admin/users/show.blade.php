@extends('layouts.admin.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Chi tiết người dùng</h2>
            <p class="text-gray-600 mt-1">Thông tin chi tiết về tài khoản và hành vi sử dụng hệ thống</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.index') }}" 
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
            <!-- User Information Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    Thông tin cơ bản
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tên người dùng</label>
                        <div class="flex items-center space-x-3 mt-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=10b981&color=fff" 
                                 alt="{{ $user->name }}" 
                                 class="w-16 h-16 rounded-full">
                            <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 mt-1 font-medium">{{ $user->email }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Vai trò</label>
                        <p class="text-gray-900 mt-1">
                            @if($user->role->value === 'admin')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    {{ $user->role->label() }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    {{ $user->role->label() }}
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Trạng thái</label>
                        <p class="text-gray-900 mt-1">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>
                                    Bị khóa
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Ngày đăng ký</label>
                        <p class="text-gray-900 mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Lần đăng nhập gần nhất</label>
                        <p class="text-gray-900 mt-1">
                            @if($user->last_login)
                                {{ $user->last_login->format('d/m/Y H:i') }}
                                <span class="block text-gray-500 text-sm mt-1">{{ $user->last_login->diffForHumans() }}</span>
                            @else
                                <span class="text-gray-400">Chưa đăng nhập</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                    Thống kê hành vi
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-3">
                            <i class="fas fa-eye text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Số món đã xem</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['viewed_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-3">
                            <i class="fas fa-utensils text-green-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Số món đã nấu</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['cooked_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg mb-3">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Món yêu thích</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['favorite_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg mb-3">
                            <i class="fas fa-camera text-yellow-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Lần quét camera</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['scan_count'] }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-3">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Số đánh giá</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $statistics['review_count'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Actions Section -->
            @if($user->id !== auth()->id())
                <div class="pb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2 text-orange-600"></i>
                        Hành động quản trị
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @if($user->status === 'active')
                            <form method="POST" action="{{ route('admin.users.updateStatus', $user) }}" 
                                  class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn khóa tài khoản này?');">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="blocked">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2.5 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition">
                                    <i class="fas fa-lock mr-2"></i>
                                    Khóa tài khoản
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.updateStatus', $user) }}" 
                                  class="inline-block"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn mở khóa tài khoản này?');">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-2.5 border border-green-300 shadow-sm text-sm font-medium rounded-lg text-green-700 bg-white hover:bg-green-50 transition">
                                    <i class="fas fa-unlock mr-2"></i>
                                    Mở khóa tài khoản
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="pb-6">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                            <p class="text-sm text-yellow-800">Bạn không thể thực hiện hành động quản trị trên chính tài khoản của mình.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


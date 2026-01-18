<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - AI Chef - Smart Recipe System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('scripts')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Left Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">AI Chef</h1>
                        <p class="text-xs text-gray-500">Smart Recipe System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @php
                    $isDashboard = request()->routeIs('admin.dashboard');
                    $isDishes = request()->routeIs('admin.dishes.*');
                    $isCategories = request()->routeIs('admin.categories.*');
                    $isFavoriteDishes = request()->routeIs('admin.favorite-dishes.*');
                    $isIngredients = request()->routeIs('admin.ingredients.*');
                    $isReviews = request()->routeIs('admin.reviews.*');
                    $isUsers = request()->routeIs('admin.users.*');
                @endphp
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isDashboard ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Trang chủ</span>
                </a>
                <a href="{{ route('admin.dishes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isDishes ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-utensils w-5"></i>
                    <span>Quản lý món ăn</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isCategories ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-folder w-5"></i>
                    <span>Danh mục món ăn</span>
                </a>
                <a href="{{ route('admin.ingredients.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isIngredients ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-list w-5"></i>
                    <span>Nguyên liệu</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    <i class="fas fa-clock w-5"></i>
                    <span>Lịch sử</span>
                </a>
                <a href="{{ route('admin.favorite-dishes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isFavoriteDishes ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-heart w-5"></i>
                    <span>Món ăn yêu thích</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isReviews ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-star w-5"></i>
                    <span>Quản lý đánh giá</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ $isUsers ? 'bg-green-50 text-green-600' : 'text-gray-700 hover:bg-gray-100' }} transition">
                    <i class="fas fa-users w-5"></i>
                    <span>Quản lý người dùng</span>
                </a>
               
            </nav>

            <!-- User Profile Section -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 px-2 py-2 rounded-lg hover:bg-gray-100 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=10b981&color=fff" alt="User" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Tìm kiếm món ăn..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    <button class="ml-4 relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
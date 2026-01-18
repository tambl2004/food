@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- AI Recommendation Banner -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center space-x-2 mb-2">
                <span class="text-green-100 font-semibold text-sm uppercase tracking-wide">AI RECOMMENDATION</span>
            </div>
            <h2 class="text-2xl font-bold mb-2">Món ăn phù hợp với bạn hôm nay</h2>
            <p class="text-green-50 mb-4 max-w-2xl">
                Dựa trên thói quen ăn uống và nguyên liệu sẵn có, AI đề xuất 3 món ăn tốt nhất cho bữa tối của bạn
            </p>
            <button class="bg-white text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-green-50 transition flex items-center space-x-2">
                <span>Xem gợi ý</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="absolute right-0 top-0 bottom-0 w-64 opacity-20">
            <i class="fas fa-robot text-9xl text-white"></i>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Available Ingredients Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-lightbulb text-purple-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-semibold flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +12%
                </span>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Nguyên liệu có sẵn</h3>
            <p class="text-3xl font-bold text-gray-800">24</p>
        </div>

        <!-- Suitable Recipes Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-purple-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-semibold flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +8
                </span>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Công thức phù hợp</h3>
            <p class="text-3xl font-bold text-gray-800">156</p>
        </div>

        <!-- Cooking Time Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Thời gian nấu</h3>
            <p class="text-3xl font-bold text-gray-800">35 <span class="text-lg font-normal text-gray-500">phút</span></p>
            <p class="text-sm text-gray-500 mt-1">Trung bình</p>
        </div>

        <!-- Average Calories Card -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-fire text-red-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Calories trung bình</h3>
            <p class="text-3xl font-bold text-gray-800">520 <span class="text-lg font-normal text-gray-500">kcal</span></p>
            <p class="text-sm text-green-600 font-medium mt-1">Tốt</p>
        </div>
    </div>

    <!-- Suggested Dishes Section with Ingredients -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Suggested Dishes - Takes 2 columns -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Món ăn được đề xuất</h2>
                <a href="#" class="text-green-600 hover:text-green-700 font-medium flex items-center space-x-1">
                    <span>Xem tất cả</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="space-y-6">
            <!-- Recipe Card 1: Phở bò -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                <div class="flex">
                    <div class="w-48 h-48 bg-gray-200 flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1526318896980-cf78c088247c?w=400&h=400&fit=crop" 
                             alt="Phở bò truyền thống" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Phở bò truyền thống</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-clock"></i>
                                        <span>45 phút</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-fire"></i>
                                        <span>450 kcal</span>
                                    </span>
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-xs">Trung bình</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-50 px-3 py-1 rounded-full">
                                <i class="fas fa-heart text-green-600"></i>
                                <span class="text-green-600 font-semibold text-sm">98% phù hợp</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Món phở bò truyền thống với nước dùng đậm đà, thịt bò mềm và rau thơm tươi ngon. Phù hợp với khẩu vị và thói quen ăn uống của bạn.
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span class="font-medium">Nguyên liệu:</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Thịt bò</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Bánh phở</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Rau thơm</span>
                            </div>
                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                                Nấu ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipe Card 2: Gà xào rau củ -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                <div class="flex">
                    <div class="w-48 h-48 bg-gray-200 flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&h=400&fit=crop" 
                             alt="Gà xào rau củ" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Gà xào rau củ</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-clock"></i>
                                        <span>30 phút</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-fire"></i>
                                        <span>380 kcal</span>
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Dễ</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-50 px-3 py-1 rounded-full">
                                <i class="fas fa-heart text-green-600"></i>
                                <span class="text-green-600 font-semibold text-sm">95% phù hợp</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Món ăn nhẹ nhàng, giàu protein và vitamin từ rau củ tươi. Phù hợp cho bữa tối lành mạnh và nhanh chóng.
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span class="font-medium">Nguyên liệu:</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Thịt gà</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Cà rốt</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Ớt chuông</span>
                            </div>
                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                                Nấu ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipe Card 3: Cá hồi nướng chanh -->
            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition">
                <div class="flex">
                    <div class="w-48 h-48 bg-gray-200 flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400&h=400&fit=crop" 
                             alt="Cá hồi nướng chanh" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Cá hồi nướng chanh</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-clock"></i>
                                        <span>25 phút</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-fire"></i>
                                        <span>420 kcal</span>
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Dễ</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1 bg-green-50 px-3 py-1 rounded-full">
                                <i class="fas fa-heart text-green-600"></i>
                                <span class="text-green-600 font-semibold text-sm">92% phù hợp</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 leading-relaxed">
                            Cá hồi giàu omega-3, nướng thơm phức cùng chanh tươi và thảo mộc. Món ăn healthy cho người quan tâm sức khỏe.
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span class="font-medium">Nguyên liệu:</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Cá hồi</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Chanh</span>
                                <span class="px-2 py-1 bg-gray-100 rounded">Thảo mộc</span>
                            </div>
                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                                Nấu ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Your Ingredients Section - Takes 1 column -->
        <div class="lg:col-span-1 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">Nguyên liệu của bạn</h3>
                <button class="text-green-600 hover:text-green-700">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <!-- Search Input -->
            <div class="relative mb-4">
                <input type="text" 
                       placeholder="Tìm nguyên liệu..." 
                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Ingredients List -->
            <div class="space-y-3 mb-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium text-gray-800">Cà rốt</p>
                        <p class="text-sm text-gray-500">500g</p>
                    </div>
                    <button class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium text-gray-800">Thịt gà</p>
                        <p class="text-sm text-gray-500">800g</p>
                    </div>
                    <button class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium text-gray-800">Ớt chuông</p>
                        <p class="text-sm text-gray-500">300g</p>
                    </div>
                    <button class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="font-medium text-gray-800">Cá hồi</p>
                    </div>
                    <button class="text-gray-400 hover:text-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <button class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-green-500 hover:text-green-600 transition font-medium">
                <i class="fas fa-plus mr-2"></i>
                Thêm nguyên liệu
            </button>

            <!-- Eating Preferences Section -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Sở thích ăn uống</h3>
                
                <!-- Taste Section -->
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Khẩu vị</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Cay</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Ngọt</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Chua</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Mặn</span>
                    </div>
                </div>

                <!-- Dish Type Section -->
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Loại món</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Việt Nam</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Hàn Quốc</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Nhật Bản</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Healthy</span>
                    </div>
                </div>

                <!-- Restrictions Section -->
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Hạn chế</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium flex items-center">
                            <i class="fas fa-circle text-red-500 text-xs mr-2"></i>
                            Đậu phộng
                        </span>
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium flex items-center">
                            <i class="fas fa-circle text-red-500 text-xs mr-2"></i>
                            Hải sản
                        </span>
                    </div>
                </div>

                <button class="w-full py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center space-x-2">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Chỉnh sửa</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
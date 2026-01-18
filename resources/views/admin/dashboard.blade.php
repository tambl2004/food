@extends('layouts.admin.app')

@section('title', 'Dashboard Thống kê')

@section('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Thống kê</h1>
            <p class="text-gray-600 mt-1">Tổng quan hoạt động hệ thống và hiệu quả AI</p>
        </div>
    </div>

    <!-- 1. Summary Cards (Tổng quan nhanh) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Tổng số người dùng -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <span class="text-green-600 text-sm font-semibold flex items-center">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +{{ $summary['new_users_today'] }} hôm nay
                </span>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số người dùng</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_users']) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $summary['new_users_this_month'] }} người dùng mới trong tháng này</p>
        </div>

        <!-- Tổng số món ăn -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-green-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số món ăn</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_dishes']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Món ăn trong hệ thống</p>
        </div>

        <!-- Tổng số nguyên liệu -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-list text-purple-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Tổng số nguyên liệu</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_ingredients']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Nguyên liệu trong hệ thống</p>
        </div>

        <!-- Số lượt gợi ý AI -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot text-orange-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Số lượt gợi ý AI</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_ai_recommendations']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Tổng lượt gợi ý</p>
        </div>

        <!-- Số lượt quét camera -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-camera text-pink-600 text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm mb-1">Số lượt quét camera</h3>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($summary['total_camera_scans']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Tổng lượt quét</p>
        </div>
    </div>

    <!-- 2. System Activity Charts (Biểu đồ hoạt động hệ thống) -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Hoạt động hệ thống (7 ngày gần nhất)</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Biểu đồ số user mới theo ngày -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Số người dùng mới theo ngày</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- Biểu đồ số lượt sử dụng AI -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Số lượt sử dụng AI gợi ý</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="aiUsageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Recommendation Analytics (Thống kê gợi ý món ăn) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top 5 món được gợi ý nhiều nhất -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Top 5 món được gợi ý nhiều nhất</h2>
            </div>
            @if($recommendationAnalytics['top_recommended_dishes']->count() > 0)
                <div class="space-y-4">
                    @foreach($recommendationAnalytics['top_recommended_dishes'] as $index => $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $item['dish']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} lượt</p>
                                </div>
                            </div>
                            @php
                                $routeName = isset($item['is_product']) && $item['is_product'] 
                                    ? 'admin.products.show' 
                                    : 'admin.dishes.show';
                            @endphp
                            @if(isset($item['dish']) && $item['dish'])
                                <a href="{{ route($routeName, $item['dish']->id) }}" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Chưa có dữ liệu</p>
            @endif
        </div>

        <!-- Top 5 món được nấu nhiều nhất -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Top 5 món được nấu nhiều nhất</h2>
            </div>
            @if($recommendationAnalytics['top_cooked_dishes']->count() > 0)
                <div class="space-y-4">
                    @foreach($recommendationAnalytics['top_cooked_dishes'] as $index => $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-orange-500 text-white rounded-lg flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $item['dish']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} lượt</p>
                                </div>
                            </div>
                            @php
                                $routeName = isset($item['is_product']) && $item['is_product'] 
                                    ? 'admin.products.show' 
                                    : 'admin.dishes.show';
                            @endphp
                            @if(isset($item['dish']) && $item['dish'])
                                <a href="{{ route($routeName, $item['dish']->id) }}" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Chưa có dữ liệu</p>
            @endif

            <!-- Tỷ lệ chuyển đổi -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Tỷ lệ chuyển đổi (Gợi ý → Nấu)</span>
                    <span class="text-lg font-bold text-green-600">{{ number_format($recommendationAnalytics['recommendation_to_cook_rate'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full" style="width: {{ min($recommendationAnalytics['recommendation_to_cook_rate'], 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Review Analytics (Thống kê đánh giá & Feedback) -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Thống kê đánh giá & Feedback</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Điểm đánh giá trung bình -->
            <div class="text-center p-6 bg-blue-50 rounded-xl">
                <i class="fas fa-star text-blue-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ $reviewAnalytics['average_rating'] }}</p>
                <p class="text-sm text-gray-600 mt-1">Điểm đánh giá trung bình</p>
                <p class="text-xs text-gray-500 mt-2">Tổng: {{ number_format($reviewAnalytics['total_reviews']) }} đánh giá</p>
            </div>

            <!-- Top món được đánh giá cao -->
            <div class="text-center p-6 bg-green-50 rounded-xl">
                <i class="fas fa-thumbs-up text-green-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ $reviewAnalytics['top_rated_dishes']->count() }}</p>
                <p class="text-sm text-gray-600 mt-1">Món được đánh giá cao</p>
            </div>

            <!-- Món cần cải thiện -->
            <div class="text-center p-6 bg-orange-50 rounded-xl">
                <i class="fas fa-exclamation-triangle text-orange-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ $reviewAnalytics['low_rated_dishes']->count() }}</p>
                <p class="text-sm text-gray-600 mt-1">Món cần cải thiện</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top món được đánh giá cao -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Top món được đánh giá cao</h3>
                @if($reviewAnalytics['top_rated_dishes']->count() > 0)
                    <div class="space-y-3">
                        @foreach($reviewAnalytics['top_rated_dishes'] as $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $item['dish']->name }}</h4>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item['avg_rating'] ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                            <span class="text-sm text-gray-600 ml-2">{{ $item['avg_rating'] }} ({{ $item['review_count'] }} đánh giá)</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.dishes.show', $item['dish']->id) }}" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Chưa có dữ liệu</p>
                @endif
            </div>

            <!-- Top món bị đánh giá thấp -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Món cần cải thiện</h3>
                @if($reviewAnalytics['low_rated_dishes']->count() > 0)
                    <div class="space-y-3">
                        @foreach($reviewAnalytics['low_rated_dishes'] as $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $item['dish']->name }}</h4>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $item['avg_rating'] ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                            @endfor
                                            <span class="text-sm text-gray-600 ml-2">{{ $item['avg_rating'] }} ({{ $item['review_count'] }} đánh giá)</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.dishes.show', $item['dish']->id) }}" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Không có món nào cần cải thiện</p>
                @endif
            </div>
        </div>
    </div>

    <!-- 5. Camera Analytics (Thống kê camera & dinh dưỡng) -->
    @if($cameraAnalytics['total_scans'] > 0 || true) {{-- Placeholder: luôn hiển thị để demo --}}
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Thống kê Camera & Nhận diện</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="text-center p-6 bg-purple-50 rounded-xl">
                <i class="fas fa-camera-retro text-purple-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($cameraAnalytics['total_scans']) }}</p>
                <p class="text-sm text-gray-600 mt-1">Tổng lượt quét</p>
            </div>

            <div class="text-center p-6 bg-blue-50 rounded-xl">
                <i class="fas fa-check-circle text-blue-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($cameraAnalytics['average_confidence'], 1) }}%</p>
                <p class="text-sm text-gray-600 mt-1">Độ chính xác trung bình</p>
            </div>

            <div class="text-center p-6 bg-green-50 rounded-xl">
                <i class="fas fa-calendar-day text-green-600 text-3xl mb-3"></i>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($cameraAnalytics['scans_today']) }}</p>
                <p class="text-sm text-gray-600 mt-1">Lượt quét hôm nay</p>
            </div>
        </div>

        @if($cameraAnalytics['top_scanned_ingredients']->count() > 0)
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Top nguyên liệu được quét nhiều nhất</h3>
                <div class="space-y-3">
                    @foreach($cameraAnalytics['top_scanned_ingredients'] as $index => $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-purple-500 text-white rounded-lg flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $item['ingredient']->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item['count'] }} lượt quét</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-info-circle text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">Chức năng camera quét nguyên liệu đang được phát triển</p>
                <p class="text-sm text-gray-400 mt-2">Dữ liệu sẽ được cập nhật khi có bảng scan_history</p>
            </div>
        @endif
    </div>
    @endif
</div>

<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = {
        primary: 'rgb(34, 197, 94)',
        secondary: 'rgb(59, 130, 246)',
        tertiary: 'rgb(249, 115, 22)',
    };

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart');
    if (userGrowthCtx) {
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: @json($activityCharts['labels']),
                datasets: [{
                    label: 'Người dùng mới',
                    data: @json($activityCharts['user_growth']),
                    borderColor: chartColors.secondary,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // AI Usage Chart
    const aiUsageCtx = document.getElementById('aiUsageChart');
    if (aiUsageCtx) {
        new Chart(aiUsageCtx, {
            type: 'bar',
            data: {
                labels: @json($activityCharts['labels']),
                datasets: [{
                    label: 'Lượt sử dụng AI',
                    data: @json($activityCharts['ai_usage']),
                    backgroundColor: chartColors.tertiary,
                    borderColor: chartColors.tertiary,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection

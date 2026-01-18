<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Dish;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Review;
use App\Models\FavoriteDish;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tổng quan nhanh (Summary Cards)
        $summary = $this->getSummary();
        
        // 2. Biểu đồ hoạt động hệ thống (System Activity Charts)
        $activityCharts = $this->getActivityCharts();
        
        // 3. Thống kê gợi ý món ăn (Recommendation Analytics)
        $recommendationAnalytics = $this->getRecommendationAnalytics();
        
        // 4. Thống kê đánh giá & Feedback (Review Analytics)
        $reviewAnalytics = $this->getReviewAnalytics();
        
        // 5. Thống kê camera & dinh dưỡng (Camera Analytics)
        // Note: Nếu chưa có bảng scan_history, sẽ dùng placeholders
        $cameraAnalytics = $this->getCameraAnalytics();

        return view('admin.dashboard', compact(
            'summary',
            'activityCharts',
            'recommendationAnalytics',
            'reviewAnalytics',
            'cameraAnalytics'
        ));
    }

    /**
     * Lấy tổng quan nhanh (Summary Cards)
     */
    private function getSummary()
    {
        return [
            'total_users' => User::count(),
            'total_dishes' => Dish::count(),
            'total_ingredients' => Ingredient::count(),
            'total_ai_recommendations' => FavoriteDish::count(), // Proxy: dùng favorite_dishes
            'total_camera_scans' => 0, // Placeholder: chưa có bảng scan_history
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
        ];
    }

    /**
     * Lấy dữ liệu cho biểu đồ hoạt động hệ thống
     */
    private function getActivityCharts()
    {
        // Số user mới theo ngày (7 ngày gần nhất)
        $last7Days = [];
        $userGrowthData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $userGrowthData[] = User::whereDate('created_at', $date)->count();
        }

        // Số lượt sử dụng AI gợi ý (7 ngày gần nhất) - dùng favorite_dishes làm proxy
        $aiUsageData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $aiUsageData[] = FavoriteDish::whereDate('created_at', $date)->count();
        }

        // Số lượt quét camera (7 ngày gần nhất) - placeholder
        $cameraScanData = array_fill(0, 7, 0); // Placeholder: chưa có bảng scan_history

        return [
            'labels' => $last7Days,
            'user_growth' => $userGrowthData,
            'ai_usage' => $aiUsageData,
            'camera_scans' => $cameraScanData,
        ];
    }

    /**
     * Thống kê gợi ý món ăn (Recommendation Analytics)
     */
    private function getRecommendationAnalytics()
    {
        // Top 5 món được yêu thích nhiều nhất (proxy cho "gợi ý nhiều nhất")
        // Note: FavoriteDish sử dụng product_id, không phải dish_id
        $topRecommendedDishes = FavoriteDish::select('product_id', DB::raw('count(*) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Thử lấy Product trước (vì favorite_dishes dùng product_id)
                $product = Product::find($item->product_id);
                if ($product) {
                    return [
                        'dish' => $product, // Dùng product như dish để tương thích với view
                        'count' => $item->count,
                        'is_product' => true,
                    ];
                }
                // Nếu không tìm thấy Product, thử tìm Dish
                $dish = Dish::find($item->product_id);
                if ($dish) {
                    return [
                        'dish' => $dish,
                        'count' => $item->count,
                        'is_product' => false,
                    ];
                }
                return null;
            })
            ->filter(fn($item) => $item !== null);

        // Top 5 món được nấu nhiều nhất (dùng favorite_dishes làm proxy)
        $topCookedDishes = $topRecommendedDishes; // Có thể tách riêng nếu có bảng cook_history

        // Tính tỷ lệ: Gợi ý → Nấu (dùng favorite_dishes làm proxy)
        $totalFavorites = FavoriteDish::count();
        $recommendationToCookRate = $totalFavorites > 0 ? 
            ($totalFavorites / max($totalFavorites, 1)) * 100 : 0;

        return [
            'top_recommended_dishes' => $topRecommendedDishes,
            'top_cooked_dishes' => $topCookedDishes,
            'recommendation_to_cook_rate' => round($recommendationToCookRate, 2),
            'total_recommendations' => $totalFavorites,
        ];
    }

    /**
     * Thống kê đánh giá & Feedback (Review Analytics)
     */
    private function getReviewAnalytics()
    {
        // Điểm đánh giá trung bình toàn hệ thống (chỉ tính dish reviews)
        $averageRating = Review::whereNotNull('dish_id')
            ->where('status', 'visible')
            ->avg('rating') ?? 0;

        // Top món ăn được đánh giá cao
        $topRatedDishes = Review::whereNotNull('dish_id')
            ->where('status', 'visible')
            ->select('dish_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
            ->groupBy('dish_id')
            ->orderBy('avg_rating', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $dish = Dish::find($item->dish_id);
                return [
                    'dish' => $dish,
                    'avg_rating' => round($item->avg_rating, 1),
                    'review_count' => $item->review_count,
                ];
            })
            ->filter(fn($item) => $item['dish'] !== null);

        // Top món ăn bị đánh giá thấp
        $lowRatedDishes = Review::whereNotNull('dish_id')
            ->where('status', 'visible')
            ->having(DB::raw('AVG(rating)'), '<', 3)
            ->select('dish_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
            ->groupBy('dish_id')
            ->orderBy('avg_rating', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $dish = Dish::find($item->dish_id);
                return [
                    'dish' => $dish,
                    'avg_rating' => round($item->avg_rating, 1),
                    'review_count' => $item->review_count,
                ];
            })
            ->filter(fn($item) => $item['dish'] !== null);

        return [
            'average_rating' => round($averageRating, 1),
            'total_reviews' => Review::whereNotNull('dish_id')->where('status', 'visible')->count(),
            'top_rated_dishes' => $topRatedDishes,
            'low_rated_dishes' => $lowRatedDishes,
        ];
    }

    /**
     * Thống kê camera & dinh dưỡng (Camera Analytics)
     * Note: Placeholder - chưa có bảng scan_history
     */
    private function getCameraAnalytics()
    {
        // Top nguyên liệu được quét nhiều nhất - placeholder
        // Khi có bảng scan_history, sẽ query:
        // SELECT ingredient_id, COUNT(*) as count FROM scan_history GROUP BY ingredient_id ORDER BY count DESC LIMIT 5
        
        return [
            'top_scanned_ingredients' => collect([]), // Placeholder
            'average_confidence' => 0, // Placeholder
            'total_scans' => 0, // Placeholder
            'scans_today' => 0, // Placeholder
        ];
    }
}
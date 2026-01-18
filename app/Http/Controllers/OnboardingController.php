<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /**
     * Display the onboarding preferences form.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        
        // Nếu đã có preferences, redirect về home
        if ($user->userPreference) {
            return redirect()->route('home');
        }

        // Lấy danh sách categories từ database hoặc định nghĩa tĩnh
        $dishCategories = Dish::where('status', 'active')
            ->with('category')
            ->get()
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Danh sách sẵn có cho form
        $favoriteCategories = [
            'mon_man' => 'Món mặn',
            'mon_chay' => 'Món chay',
            'mon_cay' => 'Món cay',
            'mon_ngot' => 'Món ngọt',
            'mon_trang_mieng' => 'Món tráng miệng',
            'mon_an_nhanh' => 'Món ăn nhanh',
            'mon_soup' => 'Món canh/súp',
            'mon_nuong' => 'Món nướng',
            'mon_xao' => 'Món xào',
        ];

        $origins = [
            'viet_nam' => 'Việt Nam',
            'han_quoc' => 'Hàn Quốc',
            'nhat_ban' => 'Nhật Bản',
            'trung_quoc' => 'Trung Quốc',
            'au_my' => 'Âu - Mỹ',
            'thai_lan' => 'Thái Lan',
            'italy' => 'Italy',
            'khac' => 'Khác',
        ];

        $dietTypes = [
            'eat_clean' => 'Eat Clean',
            'chay' => 'Chay',
            'keto' => 'Keto',
            'healthy' => 'Healthy',
            'khong_thit_do' => 'Không ăn thịt đỏ',
        ];

        $spicyLevels = [
            0 => 'Không cay',
            1 => 'Cay nhẹ',
            2 => 'Cay vừa',
            3 => 'Cay nhiều',
        ];

        $allergies = [
            'trung' => 'Trứng',
            'hai_san' => 'Hải sản',
            'sua' => 'Sữa',
            'dau_phong' => 'Đậu phộng',
            'lac' => 'Lạc',
            'toi' => 'Tỏi',
            'hanh_tay' => 'Hành tây',
        ];

        return view('customer.onboarding.preferences', [
            'favoriteCategories' => $favoriteCategories,
            'origins' => $origins,
            'dietTypes' => $dietTypes,
            'spicyLevels' => $spicyLevels,
            'allergies' => $allergies,
        ]);
    }

    /**
     * Store user preferences and redirect to recommendations.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Nếu đã có preferences, không cho phép tạo lại (redirect)
        if ($user->userPreference) {
            return redirect()->route('home');
        }

        $validated = $request->validate([
            'favorite_categories' => ['required', 'array', 'min:1'],
            'favorite_categories.*' => ['string', 'max:255'],
            'origins' => ['nullable', 'array'],
            'origins.*' => ['string', 'max:255'],
            'diet_types' => ['nullable', 'array'],
            'diet_types.*' => ['string', 'max:255'],
            'spicy_level' => ['required', 'integer', 'min:0', 'max:3'],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string', 'max:255'],
            'allergies_custom' => ['nullable', 'string', 'max:500'],
        ], [
            'favorite_categories.required' => 'Vui lòng chọn ít nhất một loại món yêu thích.',
            'favorite_categories.min' => 'Vui lòng chọn ít nhất một loại món yêu thích.',
            'spicy_level.required' => 'Vui lòng chọn mức độ ăn cay.',
        ]);

        // Xử lý allergies: kết hợp allergies checkbox và allergies_custom
        $allergies = $validated['allergies'] ?? [];
        if (!empty($validated['allergies_custom'])) {
            // Chia allergies_custom theo dấu phẩy hoặc xuống dòng
            $customAllergies = array_map('trim', explode(',', $validated['allergies_custom']));
            $customAllergies = array_filter($customAllergies); // Loại bỏ rỗng
            $allergies = array_merge($allergies, $customAllergies);
        }

        // Tạo UserPreference
        $preferences = UserPreference::create([
            'user_id' => $user->id,
            'favorite_categories' => $validated['favorite_categories'],
            'origins' => $validated['origins'] ?? [],
            'diet_types' => $validated['diet_types'] ?? [],
            'diet_type' => !empty($validated['diet_types']) ? $validated['diet_types'][0] : null, // Giữ tương thích
            'spicy_level' => $validated['spicy_level'],
            'allergies' => $allergies,
            'disliked_ingredients' => $allergies, // Sử dụng allergies cho disliked_ingredients
            'health_goal' => null, // Có thể thêm sau
        ]);

        // Redirect đến trang recommendations với thông báo
        return redirect()->route('recommendations.index')
            ->with('success', 'Hoàn tất! Dưới đây là những món ăn phù hợp với sở thích của bạn.');
    }
}
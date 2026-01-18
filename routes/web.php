<?php

use Illuminate\Support\Facades\Route;

// --- Import Controllers ---
// Controllers cho Khách hàng
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserPreferenceController;
use App\Http\Controllers\UserIngredientController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ShoppingListController;


// Controllers cho Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\IngredientController as AdminIngredientController;
use App\Http\Controllers\Admin\FavoriteDishController as AdminFavoriteDishController;
use App\Http\Controllers\Admin\DishController as AdminDishController;

/*
|--------------------------------------------------------------------------
| KHU VỰC ROUTE CHO KHÁCH (GUEST & CUSTOMER)
|--------------------------------------------------------------------------
|
| Các route này dành cho tất cả người dùng, bao gồm cả khách vãng lai.
|
*/
// Trang chủ mới với sản phẩm nổi bật
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard cho người dùng thường
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Hồ sơ người dùng (sửa, cập nhật, xoá tài khoản)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Onboarding - Thu thập sở thích ban đầu
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/preferences', [App\Http\Controllers\OnboardingController::class, 'show'])->name('preferences');
        Route::post('/preferences', [App\Http\Controllers\OnboardingController::class, 'store'])->name('preferences.store');
    });
    
    // Sở thích ăn uống
    Route::get('/preferences', [UserPreferenceController::class, 'show'])->name('preferences.show');
    Route::put('/preferences', [UserPreferenceController::class, 'update'])->name('preferences.update');
    Route::post('/preferences/reset', [UserPreferenceController::class, 'reset'])->name('preferences.reset');
    
    // Nguyên liệu của tôi
    Route::get('/user/ingredients', [UserIngredientController::class, 'index'])->name('user.ingredients.index');
    Route::post('/user/ingredients', [UserIngredientController::class, 'store'])->name('user.ingredients.store');
    Route::put('/user/ingredients/{userIngredient}', [UserIngredientController::class, 'update'])->name('user.ingredients.update');
    Route::delete('/user/ingredients/{userIngredient}', [UserIngredientController::class, 'destroy'])->name('user.ingredients.destroy');
    Route::get('/api/user/ingredients/search', [UserIngredientController::class, 'getIngredients'])->name('api.user.ingredients.search');
    Route::get('/user/ingredients/find-dishes', [UserIngredientController::class, 'findDishes'])->name('user.ingredients.find-dishes');
    
    // Gợi ý món ăn
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::get('/api/recommendations', [RecommendationController::class, 'getRecommendationsApi'])->name('api.recommendations');
    Route::post('/recommendations/dishes/{dish}/view', [RecommendationController::class, 'logView'])->name('recommendations.log-view');
    Route::post('/recommendations/dishes/{dish}/cook', [RecommendationController::class, 'logCook'])->name('recommendations.log-cook');
    
    // Nguyên liệu cần mua (Shopping List)
    Route::get('/shopping-list', [ShoppingListController::class, 'index'])->name('shopping-list.index');
    Route::get('/api/recommendations/missing-ingredients', [ShoppingListController::class, 'getMissingIngredients'])->name('api.missing-ingredients');
    Route::post('/api/shopping-list/mark-purchased', [ShoppingListController::class, 'markAsPurchased'])->name('api.shopping-list.mark-purchased');
});

// Trang hiển thị TẤT CẢ sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Trang hiển thị chi tiết món ăn (Dish)
Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

// Tin tức
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| KHU VỰC ROUTE XÁC THỰC (AUTH)
|--------------------------------------------------------------------------
|
| Các route xử lý việc đăng ký, đăng nhập, đăng xuất và xác thực email.
|
*/

// --- Email Verification ---
// Các route xác thực đã được định nghĩa tập trung trong routes/auth.php

/*
|--------------------------------------------------------------------------
| KHU VỰC ROUTE CỦA KHÁCH HÀNG ĐÃ ĐĂNG NHẬP
|--------------------------------------------------------------------------
|
| Các route này yêu cầu người dùng phải đăng nhập và đã xác thực email.
|
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Reviews - Chức năng đánh giá sản phẩm
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| KHU VỰC ROUTE CỦA ADMIN
|--------------------------------------------------------------------------
|
| Tất cả các route trong này đều được bảo vệ và có tiền tố /admin.
|
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::put('/categories/{category}/status', [AdminCategoryController::class, 'updateStatus'])->name('categories.updateStatus');
    Route::resource('users', AdminUserController::class)->only(['index', 'show']);
    Route::put('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::resource('news', AdminNewsController::class);
    
    // Admin Reviews Management (Dish Reviews & Feedback)
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::put('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    // Product reviews management (giữ lại để tương thích)
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::get('/reviews/statistics', [AdminReviewController::class, 'statistics'])->name('reviews.statistics');

    // FAQ management
    Route::resource('faq', AdminFaqController::class)->except(['show']);
    
    // Ingredients management (food domain)
    Route::resource('ingredients', AdminIngredientController::class);
    Route::put('/ingredients/{ingredient}/status', [AdminIngredientController::class, 'updateStatus'])->name('ingredients.updateStatus');
    
    // Favorite dishes management
    Route::resource('favorite-dishes', AdminFavoriteDishController::class)->only(['index', 'show', 'destroy']);
    
    // Dishes management
    Route::resource('dishes', AdminDishController::class);
    Route::put('/dishes/{dish}/status', [AdminDishController::class, 'updateStatus'])->name('dishes.updateStatus');
});

// Nạp nhóm route xác thực (register/login/verify...) để có route verification.verify
require __DIR__.'/auth.php';

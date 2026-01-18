<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoriteDish;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteDishController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Hiển thị danh sách tất cả món ăn yêu thích
     */
    public function index(Request $request)
    {
        $query = FavoriteDish::with(['user', 'product.category'])->latest('created_at');

        // Lọc theo user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Tìm kiếm theo tên món ăn
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Tìm kiếm theo tên người dùng
        if ($request->has('user_search') && $request->user_search != '') {
            $userSearch = $request->user_search;
            $query->whereHas('user', function($q) use ($userSearch) {
                $q->where('name', 'like', '%' . $userSearch . '%')
                  ->orWhere('email', 'like', '%' . $userSearch . '%');
            });
        }

        $favoriteDishes = $query->paginate(15);

        // Lấy danh sách users để filter
        $users = User::orderBy('name')->get();

        // Thống kê
        $totalFavorites = FavoriteDish::count();
        $totalUsers = FavoriteDish::distinct('user_id')->count('user_id');
        $totalProducts = FavoriteDish::distinct('product_id')->count('product_id');

        return view('admin.favorite-dishes.index', compact(
            'favoriteDishes', 
            'users',
            'totalFavorites',
            'totalUsers',
            'totalProducts'
        ));
    }

    /**
     * Hiển thị chi tiết món ăn yêu thích
     */
    public function show(FavoriteDish $favoriteDish)
    {
        $favoriteDish->load(['user', 'product.category']);
        
        // Lấy thống kê liên quan
        $userFavoriteCount = FavoriteDish::where('user_id', $favoriteDish->user_id)->count();
        $productFavoriteCount = FavoriteDish::where('product_id', $favoriteDish->product_id)->count();
        
        return view('admin.favorite-dishes.show', compact(
            'favoriteDish',
            'userFavoriteCount',
            'productFavoriteCount'
        ));
    }

    /**
     * Xóa món ăn yêu thích
     */
    public function destroy(FavoriteDish $favoriteDish)
    {
        $favoriteDish->delete();

        return redirect()->route('admin.favorite-dishes.index')
                        ->with('success', 'Món ăn yêu thích đã được xóa!');
    }
}


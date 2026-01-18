<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FavoriteDish;
use App\Models\User;
use App\Models\Product;

class FavoriteDishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả users và products
        $users = User::where('role', 'user')->get(); // Chỉ lấy user thường, không lấy admin
        $products = Product::all();
        
        if ($users->count() > 0 && $products->count() > 0) {
            // Mỗi user sẽ yêu thích từ 2-8 món ăn ngẫu nhiên
            foreach ($users as $user) {
                $favoriteCount = rand(2, min(8, $products->count()));
                
                // Lấy ngẫu nhiên các món ăn chưa được user này yêu thích
                $availableProducts = $products->shuffle();
                
                for ($i = 0; $i < $favoriteCount; $i++) {
                    $product = $availableProducts[$i];
                    
                    // Kiểm tra xem user đã yêu thích món này chưa (tránh trùng lặp)
                    $existingFavorite = FavoriteDish::where('user_id', $user->id)
                                                   ->where('product_id', $product->id)
                                                   ->first();
                    
                    if (!$existingFavorite) {
                        FavoriteDish::create([
                            'user_id' => $user->id,
                            'product_id' => $product->id,
                            'created_at' => now()->subDays(rand(0, 30)), // Ngày lưu trong vòng 30 ngày gần đây
                        ]);
                    }
                }
            }
        }
    }
}


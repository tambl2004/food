<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // --- Dữ liệu nguồn để tạo món ăn/recipe ---
        $foodNames = [
            'Phở Bò', 'Bún Chả', 'Bánh Mì', 'Cơm Tấm', 'Bánh Xèo',
            'Gỏi Cuốn', 'Bún Bò Huế', 'Chả Giò', 'Bánh Cuốn', 'Nem Nướng',
            'Bún Riêu', 'Cao Lầu', 'Mì Quảng', 'Hủ Tiếu', 'Bún Thịt Nướng'
        ];
        
        $difficulties = ['Dễ', 'Trung bình', 'Khó'];
        
        // Generate food images using Unsplash
        $foodImages = [
            'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?w=400&h=400&fit=crop&crop=center',
            'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=400&h=400&fit=crop&crop=center'
        ];
        
        $productName = Arr::random($foodNames);
        $imageUrl = Arr::random($foodImages);
        $difficulty = Arr::random($difficulties);

        return [
            'name' => $productName,
            'description' => "Món ăn {$productName} thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.",
            'image' => $imageUrl,
            'difficulty' => $difficulty,
            'prep_time' => fake()->numberBetween(10, 60), // minutes
            'cook_time' => fake()->numberBetween(15, 120), // minutes
            'servings' => fake()->numberBetween(2, 8),
        ];
    }
}
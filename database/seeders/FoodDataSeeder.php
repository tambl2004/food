<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Dish;
use Illuminate\Support\Str;

class FoodDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Categories
        $categories = $this->seedCategories();
        
        // Seed Ingredients
        $ingredients = $this->seedIngredients();
        
        // Seed Dishes
        $this->seedDishes($categories, $ingredients);
    }

    /**
     * Seed Categories
     */
    private function seedCategories(): array
    {
        $categoryData = [
            // Món Việt Nam
            ['name' => 'Món chính Việt Nam', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món canh Việt Nam', 'meal_type' => 'soup', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món chay', 'meal_type' => 'main', 'diet_type' => 'vegetarian', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món khai vị', 'meal_type' => 'appetizer', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Bún & Phở', 'meal_type' => 'noodle', 'diet_type' => 'normal', 'meal_time' => ['breakfast', 'lunch']],
            ['name' => 'Cơm & Xôi', 'meal_type' => 'rice', 'diet_type' => 'normal', 'meal_time' => ['breakfast', 'lunch', 'dinner']],
            
            // Món Á
            ['name' => 'Món Nhật Bản', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món Hàn Quốc', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món Trung Hoa', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món Thái Lan', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            
            // Món Âu Mỹ
            ['name' => 'Món Ý', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Món Mỹ', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Pizza & Pasta', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            ['name' => 'Burger & Sandwich', 'meal_type' => 'fast_food', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
            
            // Món tráng miệng
            ['name' => 'Tráng miệng Việt Nam', 'meal_type' => 'dessert', 'diet_type' => 'normal', 'meal_time' => ['any']],
            ['name' => 'Tráng miệng Âu Mỹ', 'meal_type' => 'dessert', 'diet_type' => 'normal', 'meal_time' => ['any']],
            ['name' => 'Bánh ngọt', 'meal_type' => 'dessert', 'diet_type' => 'normal', 'meal_time' => ['breakfast', 'any']],
            ['name' => 'Kem & Sorbet', 'meal_type' => 'dessert', 'diet_type' => 'normal', 'meal_time' => ['any']],
            
            // Món ăn nhanh
            ['name' => 'Đồ chiên rán', 'meal_type' => 'fast_food', 'diet_type' => 'normal', 'meal_time' => ['any']],
            ['name' => 'Đồ nướng', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['dinner']],
            ['name' => 'Salad & Gỏi', 'meal_type' => 'salad', 'diet_type' => 'healthy', 'meal_time' => ['lunch', 'dinner']],
            
            // Món đặc biệt
            ['name' => 'Món ăn sáng', 'meal_type' => 'breakfast', 'diet_type' => 'normal', 'meal_time' => ['breakfast']],
            ['name' => 'Món lẩu', 'meal_type' => 'hotpot', 'diet_type' => 'normal', 'meal_time' => ['dinner']],
            ['name' => 'Món hải sản', 'meal_type' => 'main', 'diet_type' => 'normal', 'meal_time' => ['lunch', 'dinner']],
        ];

        $categories = [];
        foreach ($categoryData as $data) {
            $category = Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $this->getCategoryDescription($data['name']),
                'meal_type' => $data['meal_type'],
                'diet_type' => $data['diet_type'],
                'meal_time' => $data['meal_time'],
                'status' => 1,
            ]);
            $categories[$data['name']] = $category;
        }

        $this->command->info('Created ' . count($categories) . ' categories.');
        return $categories;
    }

    /**
     * Seed Ingredients
     */
    private function seedIngredients(): array
    {
        $ingredientData = [
            // Rau củ
            ['name' => 'Cà chua', 'type' => 'Rau củ', 'unit' => 'quả'],
            ['name' => 'Hành tây', 'type' => 'Rau củ', 'unit' => 'củ'],
            ['name' => 'Hành lá', 'type' => 'Rau củ', 'unit' => 'nhánh'],
            ['name' => 'Tỏi', 'type' => 'Gia vị', 'unit' => 'tép'],
            ['name' => 'Gừng', 'type' => 'Gia vị', 'unit' => 'củ'],
            ['name' => 'Ớt', 'type' => 'Gia vị', 'unit' => 'quả'],
            ['name' => 'Cà rốt', 'type' => 'Rau củ', 'unit' => 'củ'],
            ['name' => 'Khoai tây', 'type' => 'Rau củ', 'unit' => 'củ'],
            ['name' => 'Cải bắp', 'type' => 'Rau củ', 'unit' => 'lá'],
            ['name' => 'Cải thảo', 'type' => 'Rau củ', 'unit' => 'lá'],
            ['name' => 'Rau muống', 'type' => 'Rau củ', 'unit' => 'bó'],
            ['name' => 'Rau cải', 'type' => 'Rau củ', 'unit' => 'bó'],
            ['name' => 'Rau mồng tơi', 'type' => 'Rau củ', 'unit' => 'bó'],
            ['name' => 'Rau ngót', 'type' => 'Rau củ', 'unit' => 'bó'],
            ['name' => 'Bí đỏ', 'type' => 'Rau củ', 'unit' => 'kg'],
            ['name' => 'Bí xanh', 'type' => 'Rau củ', 'unit' => 'kg'],
            ['name' => 'Đậu cô ve', 'type' => 'Rau củ', 'unit' => 'g'],
            ['name' => 'Đậu bắp', 'type' => 'Rau củ', 'unit' => 'quả'],
            ['name' => 'Mướp', 'type' => 'Rau củ', 'unit' => 'quả'],
            ['name' => 'Cà tím', 'type' => 'Rau củ', 'unit' => 'quả'],
            ['name' => 'Ớt chuông', 'type' => 'Rau củ', 'unit' => 'quả'],
            ['name' => 'Nấm hương', 'type' => 'Nấm', 'unit' => 'g'],
            ['name' => 'Nấm kim châm', 'type' => 'Nấm', 'unit' => 'g'],
            ['name' => 'Nấm rơm', 'type' => 'Nấm', 'unit' => 'g'],
            
            // Thịt
            ['name' => 'Thịt heo', 'type' => 'Thịt', 'unit' => 'g'],
            ['name' => 'Thịt bò', 'type' => 'Thịt', 'unit' => 'g'],
            ['name' => 'Thịt gà', 'type' => 'Thịt', 'unit' => 'g'],
            ['name' => 'Thịt vịt', 'type' => 'Thịt', 'unit' => 'g'],
            ['name' => 'Xúc xích', 'type' => 'Thịt chế biến', 'unit' => 'cái'],
            ['name' => 'Thịt nguội', 'type' => 'Thịt chế biến', 'unit' => 'g'],
            ['name' => 'Thịt xông khói', 'type' => 'Thịt chế biến', 'unit' => 'g'],
            
            // Hải sản
            ['name' => 'Tôm', 'type' => 'Hải sản', 'unit' => 'con'],
            ['name' => 'Cua', 'type' => 'Hải sản', 'unit' => 'con'],
            ['name' => 'Cá', 'type' => 'Hải sản', 'unit' => 'kg'],
            ['name' => 'Mực', 'type' => 'Hải sản', 'unit' => 'con'],
            ['name' => 'Nghêu', 'type' => 'Hải sản', 'unit' => 'kg'],
            ['name' => 'Sò', 'type' => 'Hải sản', 'unit' => 'kg'],
            ['name' => 'Tôm sú', 'type' => 'Hải sản', 'unit' => 'con'],
            ['name' => 'Cá hồi', 'type' => 'Hải sản', 'unit' => 'g'],
            ['name' => 'Cá basa', 'type' => 'Hải sản', 'unit' => 'g'],
            ['name' => 'Cá lóc', 'type' => 'Hải sản', 'unit' => 'g'],
            
            // Trứng & Sữa
            ['name' => 'Trứng gà', 'type' => 'Trứng', 'unit' => 'quả'],
            ['name' => 'Trứng vịt', 'type' => 'Trứng', 'unit' => 'quả'],
            ['name' => 'Sữa tươi', 'type' => 'Sữa', 'unit' => 'ml'],
            ['name' => 'Phô mai', 'type' => 'Sữa', 'unit' => 'g'],
            ['name' => 'Bơ', 'type' => 'Sữa', 'unit' => 'g'],
            ['name' => 'Sữa chua', 'type' => 'Sữa', 'unit' => 'hũ'],
            
            // Gia vị & Nước chấm
            ['name' => 'Nước mắm', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Nước tương', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Dầu ăn', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Muối', 'type' => 'Gia vị', 'unit' => 'g'],
            ['name' => 'Đường', 'type' => 'Gia vị', 'unit' => 'g'],
            ['name' => 'Bột ngọt', 'type' => 'Gia vị', 'unit' => 'g'],
            ['name' => 'Tiêu', 'type' => 'Gia vị', 'unit' => 'g'],
            ['name' => 'Tương ớt', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Sốt cà chua', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Mật ong', 'type' => 'Gia vị', 'unit' => 'ml'],
            ['name' => 'Sả', 'type' => 'Gia vị', 'unit' => 'cây'],
            ['name' => 'Lá chanh', 'type' => 'Gia vị', 'unit' => 'lá'],
            ['name' => 'Ngò rí', 'type' => 'Gia vị', 'unit' => 'nhánh'],
            ['name' => 'Rau răm', 'type' => 'Gia vị', 'unit' => 'nhánh'],
            ['name' => 'Lá lốt', 'type' => 'Gia vị', 'unit' => 'lá'],
            ['name' => 'Nghệ', 'type' => 'Gia vị', 'unit' => 'củ'],
            
            // Đậu & Hạt
            ['name' => 'Đậu phụ', 'type' => 'Đậu', 'unit' => 'miếng'],
            ['name' => 'Đậu nành', 'type' => 'Đậu', 'unit' => 'g'],
            ['name' => 'Đậu xanh', 'type' => 'Đậu', 'unit' => 'g'],
            ['name' => 'Đậu đỏ', 'type' => 'Đậu', 'unit' => 'g'],
            ['name' => 'Đậu phộng', 'type' => 'Đậu', 'unit' => 'g'],
            ['name' => 'Đậu đen', 'type' => 'Đậu', 'unit' => 'g'],
            
            // Tinh bột
            ['name' => 'Gạo', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Bún', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Phở', 'type' => 'Tinh bột', 'unit' => 'phần'],
            ['name' => 'Mì gói', 'type' => 'Tinh bột', 'unit' => 'gói'],
            ['name' => 'Mì tươi', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Bánh phở', 'type' => 'Tinh bột', 'unit' => 'kg'],
            ['name' => 'Mì ống', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Bánh mì', 'type' => 'Tinh bột', 'unit' => 'ổ'],
            ['name' => 'Bột mì', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Bột năng', 'type' => 'Tinh bột', 'unit' => 'g'],
            ['name' => 'Bột gạo', 'type' => 'Tinh bột', 'unit' => 'g'],
            
            // Khác
            ['name' => 'Nước dừa', 'type' => 'Đồ uống', 'unit' => 'ml'],
            ['name' => 'Dừa nạo', 'type' => 'Trái cây', 'unit' => 'g'],
            ['name' => 'Chuối', 'type' => 'Trái cây', 'unit' => 'quả'],
            ['name' => 'Chanh', 'type' => 'Trái cây', 'unit' => 'quả'],
            ['name' => 'Dứa', 'type' => 'Trái cây', 'unit' => 'quả'],
            ['name' => 'Xoài', 'type' => 'Trái cây', 'unit' => 'quả'],
        ];

        $ingredients = [];
        foreach ($ingredientData as $data) {
            $ingredient = Ingredient::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'type' => $data['type'],
                'unit' => $data['unit'],
                'description' => $this->getIngredientDescription($data['name'], $data['type']),
                'status' => 'active',
            ]);
            $ingredients[$data['name']] = $ingredient;
        }

        $this->command->info('Created ' . count($ingredients) . ' ingredients.');
        return $ingredients;
    }

    /**
     * Seed Dishes
     */
    private function seedDishes(array $categories, array $ingredients): void
    {
        $dishData = $this->getDishData();
        $dishCount = 0;

        foreach ($dishData as $data) {
            // Tìm category
            $category = $categories[$data['category']] ?? null;
            if (!$category) {
                continue;
            }

            // Tạo dish
            $dish = Dish::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'category_id' => $category->id,
                'origin' => $data['origin'] ?? 'Việt Nam',
                'description' => $data['description'] ?? '',
                'difficulty' => $data['difficulty'] ?? 'medium',
                'prep_time' => $data['prep_time'] ?? 15,
                'cook_time' => $data['cook_time'] ?? 30,
                'servings' => $data['servings'] ?? 4,
                'calories' => $data['calories'] ?? 300,
                'status' => 'active',
            ]);

            // Thêm ingredients cho dish
            if (isset($data['ingredients']) && is_array($data['ingredients'])) {
                foreach ($data['ingredients'] as $ingData) {
                    $ingredientName = $ingData['name'];
                    if (isset($ingredients[$ingredientName])) {
                        $dish->ingredients()->attach($ingredients[$ingredientName]->id, [
                            'quantity' => $ingData['quantity'] ?? '1',
                            'unit' => $ingData['unit'] ?? 'g',
                            'is_required' => $ingData['is_required'] ?? true,
                        ]);
                    }
                }
            }

            $dishCount++;
        }

        $this->command->info('Created ' . $dishCount . ' dishes.');
    }

    /**
     * Get Dish Data
     */
    private function getDishData(): array
    {
        return [
            // Món Việt Nam - Chính
            [
                'name' => 'Phở bò',
                'category' => 'Bún & Phở',
                'origin' => 'Việt Nam',
                'description' => 'Món phở truyền thống với nước dùng thơm ngon và thịt bò mềm',
                'difficulty' => 'medium',
                'prep_time' => 20,
                'cook_time' => 120,
                'servings' => 4,
                'calories' => 450,
                'ingredients' => [
                    ['name' => 'Phở', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Thịt bò', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Hành tây', 'quantity' => '2', 'unit' => 'củ'],
                    ['name' => 'Gừng', 'quantity' => '1', 'unit' => 'củ'],
                    ['name' => 'Hành lá', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => 'Nước mắm', 'quantity' => '50', 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Bún chả',
                'category' => 'Bún & Phở',
                'origin' => 'Việt Nam',
                'description' => 'Bún chả Hà Nội nổi tiếng với thịt nướng thơm lừng',
                'difficulty' => 'medium',
                'prep_time' => 30,
                'cook_time' => 45,
                'servings' => 4,
                'calories' => 500,
                'ingredients' => [
                    ['name' => 'Bún', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Thịt heo', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Nước mắm', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Đường', 'quantity' => '50', 'unit' => 'g'],
                    ['name' => 'Tỏi', 'quantity' => '10', 'unit' => 'tép'],
                    ['name' => 'Ớt', 'quantity' => '5', 'unit' => 'quả'],
                ]
            ],
            [
                'name' => 'Cơm gà Hội An',
                'category' => 'Cơm & Xôi',
                'origin' => 'Việt Nam',
                'description' => 'Cơm gà thơm ngon với gà luộc và nước chấm đặc biệt',
                'difficulty' => 'easy',
                'prep_time' => 15,
                'cook_time' => 40,
                'servings' => 4,
                'calories' => 550,
                'ingredients' => [
                    ['name' => 'Gạo', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Thịt gà', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Gừng', 'quantity' => '1', 'unit' => 'củ'],
                    ['name' => 'Hành lá', 'quantity' => '50', 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Canh chua cá',
                'category' => 'Món canh Việt Nam',
                'origin' => 'Việt Nam',
                'description' => 'Canh chua cá với vị chua ngọt đặc trưng miền Nam',
                'difficulty' => 'easy',
                'prep_time' => 15,
                'cook_time' => 25,
                'servings' => 4,
                'calories' => 200,
                'ingredients' => [
                    ['name' => 'Cá', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Dứa', 'quantity' => '1', 'unit' => 'quả'],
                    ['name' => 'Cà chua', 'quantity' => '3', 'unit' => 'quả'],
                    ['name' => 'Đậu bắp', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Cà rốt', 'quantity' => '2', 'unit' => 'củ'],
                    ['name' => 'Nước mắm', 'quantity' => '50', 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Gà nướng muối ớt',
                'category' => 'Đồ nướng',
                'origin' => 'Việt Nam',
                'description' => 'Gà nướng với muối ớt cay thơm lừng',
                'difficulty' => 'medium',
                'prep_time' => 30,
                'cook_time' => 60,
                'servings' => 4,
                'calories' => 400,
                'ingredients' => [
                    ['name' => 'Thịt gà', 'quantity' => '1', 'unit' => 'kg'],
                    ['name' => 'Muối', 'quantity' => '20', 'unit' => 'g'],
                    ['name' => 'Ớt', 'quantity' => '10', 'unit' => 'quả'],
                    ['name' => 'Tỏi', 'quantity' => '10', 'unit' => 'tép'],
                    ['name' => 'Gừng', 'quantity' => '1', 'unit' => 'củ'],
                ]
            ],
            [
                'name' => 'Nem nướng Nha Trang',
                'category' => 'Món khai vị',
                'origin' => 'Việt Nam',
                'description' => 'Nem nướng thơm ngon đặc sản Nha Trang',
                'difficulty' => 'medium',
                'prep_time' => 45,
                'cook_time' => 20,
                'servings' => 6,
                'calories' => 350,
                'ingredients' => [
                    ['name' => 'Thịt heo', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Bột năng', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => 'Tỏi', 'quantity' => '5', 'unit' => 'tép'],
                    ['name' => 'Đường', 'quantity' => '30', 'unit' => 'g'],
                    ['name' => 'Nước mắm', 'quantity' => '30', 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Bánh xèo',
                'category' => 'Món ăn sáng',
                'origin' => 'Việt Nam',
                'description' => 'Bánh xèo giòn rụm với nhân tôm thịt',
                'difficulty' => 'medium',
                'prep_time' => 30,
                'cook_time' => 30,
                'servings' => 4,
                'calories' => 380,
                'ingredients' => [
                    ['name' => 'Bột gạo', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Tôm', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Thịt heo', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Giá đỗ', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Dầu ăn', 'quantity' => '100', 'unit' => 'ml'],
                ]
            ],
            [
                'name' => 'Chả giò',
                'category' => 'Đồ chiên rán',
                'origin' => 'Việt Nam',
                'description' => 'Chả giò giòn rụm với nhân thịt rau củ',
                'difficulty' => 'easy',
                'prep_time' => 40,
                'cook_time' => 20,
                'servings' => 6,
                'calories' => 320,
                'ingredients' => [
                    ['name' => 'Bánh tráng', 'quantity' => '20', 'unit' => 'lá'],
                    ['name' => 'Thịt heo', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Cà rốt', 'quantity' => '2', 'unit' => 'củ'],
                    ['name' => 'Nấm hương', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => 'Miến', 'quantity' => '100', 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Cá kho tộ',
                'category' => 'Món chính Việt Nam',
                'origin' => 'Việt Nam',
                'description' => 'Cá kho tộ với nước kho đậm đà',
                'difficulty' => 'easy',
                'prep_time' => 10,
                'cook_time' => 60,
                'servings' => 4,
                'calories' => 300,
                'ingredients' => [
                    ['name' => 'Cá', 'quantity' => '600', 'unit' => 'g'],
                    ['name' => 'Nước mắm', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Đường', 'quantity' => '50', 'unit' => 'g'],
                    ['name' => 'Tiêu', 'quantity' => '10', 'unit' => 'g'],
                    ['name' => 'Ớt', 'quantity' => '3', 'unit' => 'quả'],
                ]
            ],
            [
                'name' => 'Thịt kho tàu',
                'category' => 'Món chính Việt Nam',
                'origin' => 'Việt Nam',
                'description' => 'Thịt heo kho tàu với trứng và nước dừa',
                'difficulty' => 'easy',
                'prep_time' => 15,
                'cook_time' => 90,
                'servings' => 4,
                'calories' => 450,
                'ingredients' => [
                    ['name' => 'Thịt heo', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Trứng gà', 'quantity' => '6', 'unit' => 'quả'],
                    ['name' => 'Nước dừa', 'quantity' => '200', 'unit' => 'ml'],
                    ['name' => 'Nước mắm', 'quantity' => '80', 'unit' => 'ml'],
                    ['name' => 'Đường', 'quantity' => '40', 'unit' => 'g'],
                ]
            ],
            // Thêm nhiều món nữa...
            // Tôi sẽ tạo tiếp phần còn lại do giới hạn độ dài
        ];

        // Thêm thêm các món ăn để có nhiều dữ liệu
        return array_merge($dishData, $this->getMoreDishData());
    }

    /**
     * Get more dish data
     */
    private function getMoreDishData(): array
    {
        return [
            // Món chay
            [
                'name' => 'Đậu phụ sốt cà chua',
                'category' => 'Món chay',
                'origin' => 'Việt Nam',
                'description' => 'Đậu phụ chiên giòn sốt cà chua chua ngọt',
                'difficulty' => 'easy',
                'prep_time' => '10',
                'cook_time' => '20',
                'servings' => '4',
                'calories' => '250',
                'ingredients' => [
                    ['name' => 'Đậu phụ', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Cà chua', 'quantity' => '4', 'unit' => 'quả'],
                    ['name' => 'Hành tây', 'quantity' => '1', 'unit' => 'củ'],
                ]
            ],
            [
                'name' => 'Chả chay',
                'category' => 'Món chay',
                'origin' => 'Việt Nam',
                'description' => 'Chả chay làm từ đậu và nấm',
                'difficulty' => 'medium',
                'prep_time' => '30',
                'cook_time' => '30',
                'servings' => '4',
                'calories' => '200',
                'ingredients' => [
                    ['name' => 'Đậu phụ', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Nấm hương', 'quantity' => '150', 'unit' => 'g'],
                    ['name' => 'Cà rốt', 'quantity' => '2', 'unit' => 'củ'],
                ]
            ],
            // Món Nhật
            [
                'name' => 'Sushi cá hồi',
                'category' => 'Món Nhật Bản',
                'origin' => 'Nhật Bản',
                'description' => 'Sushi cá hồi tươi ngon',
                'difficulty' => 'hard',
                'prep_time' => '60',
                'cook_time' => '30',
                'servings' => '4',
                'calories' => '350',
                'ingredients' => [
                    ['name' => 'Cá hồi', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Gạo', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Rong biển', 'quantity' => '10', 'unit' => 'lá'],
                ]
            ],
            [
                'name' => 'Ramen',
                'category' => 'Món Nhật Bản',
                'origin' => 'Nhật Bản',
                'description' => 'Mì ramen với nước dùng đậm đà',
                'difficulty' => 'medium',
                'prep_time' => '30',
                'cook_time' => '120',
                'servings' => '4',
                'calories' => '500',
                'ingredients' => [
                    ['name' => 'Mì tươi', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Thịt heo', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Trứng gà', 'quantity' => '4', 'unit' => 'quả'],
                ]
            ],
            // Món Hàn
            [
                'name' => 'Kimchi',
                'category' => 'Món Hàn Quốc',
                'origin' => 'Hàn Quốc',
                'description' => 'Kimchi chua cay đặc trưng Hàn Quốc',
                'difficulty' => 'medium',
                'prep_time' => '60',
                'cook_time' => '0',
                'servings' => '10',
                'calories' => '30',
                'ingredients' => [
                    ['name' => 'Cải thảo', 'quantity' => '1', 'unit' => 'kg'],
                    ['name' => 'Ớt', 'quantity' => '50', 'unit' => 'g'],
                    ['name' => 'Tỏi', 'quantity' => '10', 'unit' => 'tép'],
                ]
            ],
            [
                'name' => 'Bulgogi',
                'category' => 'Món Hàn Quốc',
                'origin' => 'Hàn Quốc',
                'description' => 'Thịt bò nướng kiểu Hàn',
                'difficulty' => 'easy',
                'prep_time' => '30',
                'cook_time' => '20',
                'servings' => '4',
                'calories' => '400',
                'ingredients' => [
                    ['name' => 'Thịt bò', 'quantity' => '500', 'unit' => 'g'],
                    ['name' => 'Nước tương', 'quantity' => '100', 'unit' => 'ml'],
                    ['name' => 'Mật ong', 'quantity' => '30', 'unit' => 'ml'],
                ]
            ],
            // Món Ý
            [
                'name' => 'Spaghetti Carbonara',
                'category' => 'Pizza & Pasta',
                'origin' => 'Ý',
                'description' => 'Mì spaghetti với sốt carbonara',
                'difficulty' => 'medium',
                'prep_time' => '15',
                'cook_time' => '25',
                'servings' => '4',
                'calories' => '600',
                'ingredients' => [
                    ['name' => 'Mì ống', 'quantity' => '400', 'unit' => 'g'],
                    ['name' => 'Thịt xông khói', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Trứng gà', 'quantity' => '4', 'unit' => 'quả'],
                    ['name' => 'Phô mai', 'quantity' => '100', 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Pizza Margherita',
                'category' => 'Pizza & Pasta',
                'origin' => 'Ý',
                'description' => 'Pizza cổ điển với cà chua và phô mai',
                'difficulty' => 'medium',
                'prep_time' => '60',
                'cook_time' => '15',
                'servings' => '4',
                'calories' => '550',
                'ingredients' => [
                    ['name' => 'Bột mì', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Cà chua', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Phô mai', 'quantity' => '200', 'unit' => 'g'],
                ]
            ],
            // Món Thái
            [
                'name' => 'Pad Thai',
                'category' => 'Món Thái Lan',
                'origin' => 'Thái Lan',
                'description' => 'Mì xào kiểu Thái với tôm và đậu phộng',
                'difficulty' => 'medium',
                'prep_time' => '20',
                'cook_time' => '15',
                'servings' => '4',
                'calories' => '450',
                'ingredients' => [
                    ['name' => 'Mì gói', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Tôm', 'quantity' => '200', 'unit' => 'g'],
                    ['name' => 'Đậu phộng', 'quantity' => '50', 'unit' => 'g'],
                ]
            ],
            [
                'name' => 'Tom Yum',
                'category' => 'Món Thái Lan',
                'origin' => 'Thái Lan',
                'description' => 'Canh chua cay kiểu Thái',
                'difficulty' => 'easy',
                'prep_time' => '15',
                'cook_time' => '20',
                'servings' => '4',
                'calories' => '150',
                'ingredients' => [
                    ['name' => 'Tôm', 'quantity' => '300', 'unit' => 'g'],
                    ['name' => 'Nấm hương', 'quantity' => '100', 'unit' => 'g'],
                    ['name' => 'Sả', 'quantity' => '3', 'unit' => 'cây'],
                    ['name' => 'Lá chanh', 'quantity' => '10', 'unit' => 'lá'],
                ]
            ],
        ];
    }

    /**
     * Get category description
     */
    private function getCategoryDescription(string $name): string
    {
        $descriptions = [
            'Món chính Việt Nam' => 'Các món ăn chính đặc trưng của ẩm thực Việt Nam',
            'Món canh Việt Nam' => 'Các món canh truyền thống Việt Nam',
            'Món chay' => 'Các món ăn chay thơm ngon và bổ dưỡng',
            // Thêm mô tả cho các category khác
        ];
        
        return $descriptions[$name] ?? 'Danh mục món ăn ' . $name;
    }

    /**
     * Get ingredient description
     */
    private function getIngredientDescription(string $name, string $type): string
    {
        return "Nguyên liệu {$name} thuộc loại {$type}";
    }
}

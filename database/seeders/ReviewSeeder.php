<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
 
class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một số user và product để tạo review mẫu
        $users = User::all();
        $products = Product::all();
        
        if ($users->count() > 0 && $products->count() > 0) {
            $sampleReviews = [
                [
                    'rating' => 5,
                    'comment' => 'Sản phẩm rất tốt, chất lượng xuất sắc, đóng gói cẩn thận. Tôi rất hài lòng với sản phẩm này!'
                ],
                [
                    'rating' => 4,
                    'comment' => 'Bálo chắc chắn, thiết kế đẹp, có nhiều ngăn tiện dụng. Giá cả hợp lý.'
                ],
                [
                    'rating' => 5,
                    'comment' => 'Mình rất thích thiết kế của bálo này. Vận chuyển nhanh, báo hàng kỹ càng.'
                ],
                [
                    'rating' => 3,
                    'comment' => 'Sản phẩm ổn, nhưng chất liệu hơi kém hơn mình mong đợi. Nhìn chung còn chấp nhận được.'
                ],
                [
                    'rating' => 4,
                    'comment' => 'Bálo rất gắn gọn, đủ chỗ để laptop và tài liệu. Có thể mang đi làm và đi học.'
                ],
                [
                    'rating' => 5,
                    'comment' => 'Tương tự chat lượng, giao hàng nhanh chóng. Sẽ mua thêm các sản phẩm khác.'
                ],
                [
                    'rating' => 2,
                    'comment' => 'Chất lượng không đúng như mô tả. Dây kéo hơi khó kéo, chất liệu nhào nhào.'
                ],
                [
                    'rating' => 4,
                    'comment' => 'Mua làm quà tặng, người nhận rất thích. Thiết kế trẻ trung, hiện đại.'
                ],
                [
                    'rating' => 5,
                    'comment' => 'Quá đỉnh! Bálo vừa đẹp vừa tiện dụng. Chất lượng đáng tiền. Khuyến nghị mua!'
                ],
                [
                    'rating' => 3,
                    'comment' => 'Bình thường, chấp nhận được với giá tiền này. Có thể cải tiến thêm ở chi tiết.'
                ]
            ];
            
            // Tạo review ngẫu nhiên
            for ($i = 0; $i < 20; $i++) {
                $user = $users->random();
                $product = $products->random();
                
                // Kiểm tra xem user đã review sản phẩm chưa
                $existingReview = Review::where('user_id', $user->id)
                                       ->where('product_id', $product->id)
                                       ->first();
                
                if (!$existingReview) {
                    $reviewData = $sampleReviews[array_rand($sampleReviews)];
                    
                    Review::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'rating' => $reviewData['rating'],
                        'comment' => $reviewData['comment'],
                        'is_approved' => rand(0, 10) > 1 // 90% chance được duyệt
                    ]);
                }
            }
        }
    }
}

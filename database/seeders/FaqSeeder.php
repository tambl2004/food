<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Thời gian giao hàng mất bao lâu?',
                'answer' => 'Đơn nội thành 1-2 ngày, ngoại tỉnh 2-5 ngày làm việc. Bạn sẽ nhận SMS kèm mã vận đơn để theo dõi.',
                'display_order' => 1,
            ],
            [
                'question' => 'Chính sách bảo hành như thế nào?',
                'answer' => 'Tất cả laptop chính hãng bảo hành tối thiểu 12 tháng tại TTBH uỷ quyền. Chúng tôi hỗ trợ nhận - trả máy bảo hành miễn phí.',
                'display_order' => 2,
            ],
            [
                'question' => 'Có hỗ trợ trả góp không?',
                'answer' => 'Có. Hỗ trợ trả góp qua thẻ tín dụng hoặc công ty tài chính, lãi suất ưu đãi. Liên hệ CSKH để được tư vấn chi tiết.',
                'display_order' => 3,
            ],
            [
                'question' => 'Nếu sản phẩm bị lỗi tôi phải làm gì?',
                'answer' => 'Trong 7 ngày đầu, lỗi do nhà sản xuất được 1 đổi 1. Sau thời gian này áp dụng bảo hành tiêu chuẩn tại TTBH.',
                'display_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true])
            );
        }
    }
}



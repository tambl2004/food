<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsData = [
            [
                'title' => 'Laptop Gaming Mới Nhất 2024: Hiệu Suất Vượt Trội',
                'excerpt' => 'Khám phá những chiếc laptop gaming mới nhất với card đồ họa RTX 40 series và bộ xử lý Intel Core i9 thế hệ 14.',
                'content' => 'Năm 2024 đánh dấu một bước tiến lớn trong công nghệ laptop gaming. Các nhà sản xuất hàng đầu như ASUS, MSI, và Dell đã cho ra mắt những mẫu laptop gaming với hiệu suất vượt trội.

**Những điểm nổi bật:**

1. **Card đồ họa RTX 40 series**: Mang lại hiệu suất gaming cao hơn 30% so với thế hệ trước
2. **Bộ xử lý Intel Core i9 thế hệ 14**: Tốc độ xử lý nhanh hơn, tiết kiệm điện năng
3. **Màn hình OLED 240Hz**: Trải nghiệm gaming mượt mà và chân thực
4. **Hệ thống tản nhiệt tiên tiến**: Giữ laptop luôn mát mẻ trong quá trình gaming

**Giá cả và khuyến mãi:**
- Laptop gaming cao cấp: từ 25-50 triệu đồng
- Laptop gaming tầm trung: từ 15-25 triệu đồng
- Nhiều chương trình khuyến mãi hấp dẫn trong tháng này

Hãy đến cửa hàng để trải nghiệm trực tiếp những chiếc laptop gaming mới nhất!',
                'image_url' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'author' => 'Admin',
                'is_featured' => true,
                'is_published' => true,
                'views' => 1250,
            ],
            [
                'title' => 'Cách Chọn Laptop Văn Phòng Phù Hợp',
                'excerpt' => 'Hướng dẫn chi tiết cách chọn laptop văn phòng phù hợp với nhu cầu công việc và ngân sách.',
                'content' => 'Việc chọn laptop văn phòng phù hợp là rất quan trọng để đảm bảo hiệu quả công việc. Dưới đây là những tiêu chí quan trọng:

**1. Hiệu suất xử lý:**
- Intel Core i5 hoặc AMD Ryzen 5 cho công việc văn phòng cơ bản
- Intel Core i7 hoặc AMD Ryzen 7 cho công việc nặng hơn
- RAM tối thiểu 8GB, khuyến nghị 16GB

**2. Dung lượng lưu trữ:**
- SSD 256GB trở lên cho tốc độ khởi động nhanh
- HDD 1TB cho lưu trữ dữ liệu lớn
- Kết hợp SSD + HDD là lý tưởng nhất

**3. Màn hình:**
- Kích thước 14-15.6 inch phù hợp cho văn phòng
- Độ phân giải Full HD (1920x1080) trở lên
- Màn hình IPS cho góc nhìn rộng

**4. Pin và di động:**
- Thời lượng pin 8-10 giờ cho cả ngày làm việc
- Trọng lượng dưới 2kg để dễ di chuyển
- Cổng kết nối đầy đủ: USB, HDMI, Thunderbolt

**5. Giá cả:**
- Tầm trung: 15-25 triệu đồng
- Cao cấp: 25-40 triệu đồng

Liên hệ tư vấn để chọn được laptop phù hợp nhất!',
                'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'author' => 'Admin',
                'is_featured' => true,
                'is_published' => true,
                'views' => 980,
            ],
            [
                'title' => 'Xu Hướng Laptop Học Tập 2024',
                'excerpt' => 'Những xu hướng laptop học tập mới nhất với giá cả hợp lý và tính năng phù hợp cho sinh viên.',
                'content' => 'Năm 2024, thị trường laptop học tập có nhiều thay đổi tích cực với những sản phẩm chất lượng cao và giá cả hợp lý.

**Xu hướng nổi bật:**

**1. Laptop Chromebook:**
- Giá rẻ, phù hợp với ngân sách sinh viên
- Thời lượng pin dài, khởi động nhanh
- Tích hợp Google Workspace cho học tập

**2. Laptop Windows giá rẻ:**
- Intel Pentium hoặc Celeron cho công việc cơ bản
- RAM 4-8GB, SSD 128-256GB
- Giá từ 8-15 triệu đồng

**3. Laptop 2-in-1:**
- Có thể chuyển đổi thành tablet
- Phù hợp cho ghi chú và vẽ
- Màn hình cảm ứng tiện lợi

**4. Laptop gaming tầm trung:**
- Card đồ họa tích hợp hoặc GTX 1650
- Phù hợp cho cả học tập và giải trí
- Giá từ 15-25 triệu đồng

**Khuyến nghị theo ngành học:**
- **Kỹ thuật**: Laptop có card đồ họa, RAM 16GB+
- **Thiết kế**: Màn hình màu sắc chính xác, card đồ họa mạnh
- **Kinh tế**: Laptop cơ bản, giá rẻ, pin dài
- **Y khoa**: Laptop nhẹ, pin dài, màn hình tốt

**Chương trình hỗ trợ sinh viên:**
- Giảm giá 5-10% cho sinh viên
- Bảo hành mở rộng
- Hỗ trợ trả góp 0% lãi suất',
                'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'author' => 'Admin',
                'is_featured' => true,
                'is_published' => true,
                'views' => 756,
            ],
            [
                'title' => 'So Sánh Intel vs AMD: Nên Chọn Bộ Xử Lý Nào?',
                'excerpt' => 'Phân tích chi tiết về ưu nhược điểm của Intel và AMD để giúp bạn chọn bộ xử lý phù hợp.',
                'content' => 'Cuộc chiến giữa Intel và AMD đã kéo dài nhiều năm với những cải tiến liên tục. Dưới đây là so sánh chi tiết:

**Intel Core Series:**

**Ưu điểm:**
- Hiệu suất đơn nhân mạnh mẽ
- Tương thích tốt với phần mềm cũ
- Hỗ trợ Thunderbolt và Quick Sync
- Tiết kiệm điện năng tốt

**Nhược điểm:**
- Giá cao hơn AMD
- Hiệu suất đa nhân kém hơn
- Tản nhiệt cao hơn

**AMD Ryzen Series:**

**Ưu điểm:**
- Hiệu suất đa nhân vượt trội
- Giá cả cạnh tranh
- Tích hợp card đồ họa Vega/RDNA
- Nhiều lõi và luồng hơn

**Nhược điểm:**
- Hiệu suất đơn nhân kém hơn Intel
- Tiêu thụ điện năng cao hơn
- Tương thích phần mềm cũ kém hơn

**Khuyến nghị theo nhu cầu:**

**Gaming:**
- Intel Core i5/i7 cho gaming thuần túy
- AMD Ryzen 5/7 cho gaming + streaming

**Văn phòng:**
- Intel Core i3/i5 cho công việc cơ bản
- AMD Ryzen 3/5 cho đa tác vụ

**Sáng tạo nội dung:**
- AMD Ryzen 7/9 cho render video
- Intel Core i7/i9 cho Adobe Creative Suite

**Học tập:**
- AMD Ryzen 3/5 cho ngân sách hạn chế
- Intel Core i3/i5 cho ổn định lâu dài

Cả hai đều là lựa chọn tốt, quan trọng là phù hợp với nhu cầu và ngân sách của bạn.',
                'image_url' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'author' => 'Admin',
                'is_featured' => false,
                'is_published' => true,
                'views' => 432,
            ],
            [
                'title' => 'Cách Bảo Quản Laptop Đúng Cách',
                'excerpt' => 'Hướng dẫn chi tiết cách bảo quản laptop để tăng tuổi thọ và duy trì hiệu suất tốt nhất.',
                'content' => 'Việc bảo quản laptop đúng cách sẽ giúp tăng tuổi thọ và duy trì hiệu suất tốt nhất. Dưới đây là những lời khuyên hữu ích:

**1. Vệ sinh định kỳ:**
- Lau màn hình bằng khăn mềm và dung dịch chuyên dụng
- Vệ sinh bàn phím bằng bàn chải mềm
- Làm sạch cổng kết nối bằng tăm bông
- Vệ sinh quạt tản nhiệt mỗi 3-6 tháng

**2. Quản lý nhiệt độ:**
- Sử dụng đế tản nhiệt khi làm việc lâu
- Không đặt laptop trên đùi hoặc chăn
- Đảm bảo không khí lưu thông tốt
- Tắt laptop khi không sử dụng

**3. Quản lý pin:**
- Không để pin cạn kiệt hoàn toàn
- Sạc pin khi còn 20-30%
- Tháo sạc khi pin đầy
- Calibrate pin mỗi 2-3 tháng

**4. Bảo vệ vật lý:**
- Sử dụng túi chống sốc khi di chuyển
- Tránh va đập và rơi rớt
- Không đặt vật nặng lên laptop
- Đóng mở laptop nhẹ nhàng

**5. Bảo mật dữ liệu:**
- Cài đặt phần mềm diệt virus
- Sao lưu dữ liệu định kỳ
- Cập nhật hệ điều hành thường xuyên
- Sử dụng mật khẩu mạnh

**6. Phần mềm:**
- Gỡ bỏ phần mềm không cần thiết
- Dọn dẹp ổ cứng định kỳ
- Cập nhật driver thiết bị
- Sử dụng phần mềm quản lý hệ thống

**7. Bảo hành và sửa chữa:**
- Giữ hóa đơn mua hàng
- Đăng ký bảo hành đầy đủ
- Liên hệ trung tâm bảo hành khi có lỗi
- Không tự ý tháo lắp laptop

Làm theo những hướng dẫn này sẽ giúp laptop của bạn hoạt động tốt và bền lâu hơn.',
                'image_url' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'author' => 'Admin',
                'is_featured' => false,
                'is_published' => true,
                'views' => 321,
            ],
        ];

        foreach ($newsData as $data) {
            News::create($data);
        }
    }
}
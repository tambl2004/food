<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Tr%E1%BA%A1ng%20th%C3%A1i-Development-yellow" alt="Status">
  <img src="https://img.shields.io/badge/Phi%C3%AAn%20b%E1%BA%A3n-1.0.0-blue" alt="Version">
  <img src="https://img.shields.io/badge/C%E1%BA%ADp%20nh%E1%BA%ADt-01/2026-orange" alt="Last Update">
  <br/>
  <strong>AI Chef - Smart Recipe System</strong>
  <br/>
  Hệ thống gợi ý món ăn thông minh sử dụng AI
  
</p>

## 📋 Nội dung

- [Tổng quan](#tổng-quan)
- [Tính năng nổi bật](#tính-năng-nổi-bật)
- [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
- [Cài đặt nhanh](#cài-đặt-nhanh)
- [Tài khoản mặc định](#tài-khoản-mặc-định)
- [Các lệnh hữu ích](#các-lệnh-hữu-ích)
- [Khắc phục sự cố](#khắc-phục-sự-cố)

## 🎯 Tổng quan

**AI Chef - Smart Recipe System** là một hệ thống web thông minh được xây dựng bằng Laravel, sử dụng công nghệ AI để gợi ý món ăn phù hợp dựa trên:

- **Thói quen ăn uống**: Khẩu vị (cay, ngọt, chua, mặn), loại món ưa thích, chế độ ăn kiêng, dị ứng thực phẩm
- **Nguyên liệu sẵn có**: Tự động đề xuất công thức từ những nguyên liệu bạn đang có
- **Lịch sử nấu nướng**: Học từ các món ăn đã nấu trước đó để cải thiện gợi ý

Hệ thống giúp người dùng:
- Tiết kiệm thời gian suy nghĩ "Hôm nay ăn gì?"
- Khám phá các món ăn mới phù hợp với sở thích
- Tận dụng tối đa nguyên liệu có sẵn
- Xây dựng thói quen ăn uống lành mạnh

## ✨ Tính năng nổi bật

### 🤖 AI Recommendation Engine
- **Gợi ý món ăn thông minh**: Sử dụng AI để phân tích và đề xuất 3 món ăn tốt nhất cho bữa ăn hôm nay
- **Matching Algorithm**: Tính toán độ phù hợp (%) dựa trên thói quen ăn uống, nguyên liệu sẵn có và lịch sử

### 👤 Quản lý Thông tin Người dùng
- **Nguyên liệu của bạn**: Thêm, xóa, tìm kiếm nguyên liệu trong kho bếp
- **Sở thích ăn uống**: 
  - Khẩu vị: Cay, Ngọt, Chua, Mặn
  - Loại món: Việt Nam, Hàn Quốc, Nhật Bản, Healthy, ...
  - Hạn chế: Dị ứng, không ăn được (đậu phộng, hải sản, ...)

### 📊 Dashboard Thông minh
- **Thống kê tổng quan**: 
  - Số lượng nguyên liệu có sẵn
  - Số công thức phù hợp
  - Thời gian nấu trung bình
  - Calories trung bình
- **Món ăn được đề xuất**: Hiển thị chi tiết với hình ảnh, thời gian nấu, calories, độ khó

### 📝 Quản lý Công thức
- Danh sách công thức nấu ăn đầy đủ
- Chi tiết từng món: Nguyên liệu, hướng dẫn nấu, video tutorial
- Đánh giá và yêu thích món ăn
- Lịch sử nấu nướng

### 🔍 Phân tích Thói quen Ăn uống
- Phân tích xu hướng ăn uống
- Kế hoạch bữa ăn theo tuần/tháng
- Theo dõi calories và dinh dưỡng

### 🎨 Giao diện Hiện đại
- Responsive design với Tailwind CSS
- UI/UX thân thiện, dễ sử dụng
- Dashboard admin chuyên nghiệp

## Yêu cầu hệ thống

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Node.js**: >= 16.0 và **NPM**: >= 8.0
- **MySQL**: >= 8.0 hoặc MariaDB >= 10.5
- **Web Server**: Apache hoặc Nginx

## Cài đặt nhanh

> Lưu ý: Các lệnh dưới đây dành cho môi trường phát triển local. Trên Windows, nên chạy Terminal ở chế độ Administrator khi cần.

### 1) Clone dự án

```bash
git clone https://github.com/tambl2004/food.git
cd food
```

### 2) Cài đặt dependencies

```bash
# PHP dependencies
composer update

# Node dependencies
npm install

# Cài Tailwind plugin cho Vite (nếu sử dụng)
npm install -D @tailwindcss/vite
```

Nếu gặp lỗi khi chạy composer trên Windows (thiếu extension), thử:

```bash
# Mở file php.ini và bật các extension cần thiết
# Ví dụ với XAMPP (tuỳ máy): C:\xampp\php\php.ini
# Bỏ dấu ; trước các dòng:
#   extension=gd
#   extension=zip
#   extension=mbstring

composer clear-cache
composer update
```

### 3) Cấu hình môi trường

```bash
cp .env.example .env
php artisan config:clear
php artisan key:generate
# Tuỳ chọn: cache lại cấu hình sau khi đã chỉnh .env
php artisan config:cache
```

### 4) Cấu hình database (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_food
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
```

### 5) Khởi tạo cơ sở dữ liệu

- Tạo database mới tên `db_food` trong MySQL/MariaDB
- Import file SQL mẫu (nếu có) hoặc chạy migration:

```bash
# Tạo database và chạy migration
php artisan migrate

# Hoặc reset và seed dữ liệu mẫu
php artisan migrate:fresh --seed
```

### 6) Liên kết storage

```bash
php artisan storage:link
```

### 7) Build assets

```bash
# Production build
npm run build

# Hoặc chạy chế độ phát triển
npm run dev
```

### 8) Chạy server

```bash
php artisan serve
```

Truy cập: `http://localhost:8000`

## 🔐 Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với:

- **Admin**: 
  - Email: `admin@admin.com` 
  - Mật khẩu: `12345678`
  - Truy cập: `/admin/dashboard`

- **User thường**: 
  - Email: Tạo mới hoặc theo seeder
  - Mật khẩu mặc định: `12345678`

## 🛠️ Các lệnh hữu ích

### Quản lý Cache
```bash
# Xoá tất cả cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Cache lại để tối ưu hiệu suất
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database
```bash
# Tạo dữ liệu mẫu
php artisan db:seed

# Reset và seed lại toàn bộ database
php artisan migrate:fresh --seed

# Tạo migration mới
php artisan make:migration create_table_name
```

### Assets & Storage
```bash
# Tạo lại storage link (cho upload ảnh/video)
php artisan storage:link

# Build assets cho production
npm run build

# Chạy development server với hot reload
npm run dev
```

### Development
```bash
# Xem danh sách route
php artisan route:list

# Xem danh sách command
php artisan list

# Tạo model, controller, migration cùng lúc
php artisan make:model ModelName -mc
```

## 🐛 Khắc phục sự cố

### Lỗi thường gặp

#### 1. "Class not found" hoặc "Target class does not exist"
```bash
# Clear cache và regenerate autoload
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

#### 2. Không kết nối được Database
- Kiểm tra MySQL/MariaDB đang chạy
- Kiểm tra file `.env` đã cấu hình đúng:
  ```env
  DB_DATABASE=db_food
  DB_USERNAME=root
  DB_PASSWORD=
  ```
- Tạo database nếu chưa có:
  ```sql
  CREATE DATABASE db_food CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

#### 3. Permission storage (Linux/Mac)
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

#### 4. Lỗi npm install hoặc build assets
```bash
# Clear npm cache
npm cache clean --force

# Xóa node_modules và cài lại
rm -rf node_modules package-lock.json
npm install

# Hoặc trên Windows
rmdir /s node_modules
del package-lock.json
npm install
```

#### 5. Lỗi Tailwind CSS không hoạt động
```bash
# Đảm bảo đã cài đúng dependencies
npm install -D tailwindcss @tailwindcss/forms autoprefixer

# Rebuild assets
npm run dev
# hoặc
npm run build
```

#### 6. Lỗi "Vite manifest not found"
```bash
# Build lại assets
npm run build

# Hoặc chạy dev server
npm run dev
# (Giữ terminal này chạy khi đang development)
```

#### 7. Lỗi 404 khi truy cập routes
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

## 📚 Công nghệ sử dụng

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Tailwind CSS
- **JavaScript**: Alpine.js
- **Database**: MySQL/MariaDB
- **Build Tool**: Vite
- **Package Manager**: Composer (PHP), NPM (Node.js)

## 📝 License

Dự án này được phát triển cho mục đích học tập và nghiên cứu.

## 👥 Tác giả

Phát triển bởi: [Tên tác giả]

## 🙏 Lời cảm ơn

Cảm ơn Laravel Community và tất cả các thư viện mã nguồn mở đã hỗ trợ dự án này.

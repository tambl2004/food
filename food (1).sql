-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 19, 2026 lúc 04:05 AM
-- Phiên bản máy phục vụ: 5.7.24
-- Phiên bản PHP: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `food`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meal_time` json DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `meal_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diet_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `meal_time`, `origin`, `status`, `meal_type`, `diet_type`, `created_at`, `updated_at`) VALUES
(1, 'Bánh mì', 'banh-mi', NULL, NULL, NULL, 1, 'breakfast', 'regular', '2026-01-18 01:25:21', '2026-01-18 01:25:21'),
(2, 'Món chính Việt Nam', 'mon-chinh-viet-nam', 'Các món ăn chính đặc trưng của ẩm thực Việt Nam', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(3, 'Món canh Việt Nam', 'mon-canh-viet-nam', 'Các món canh truyền thống Việt Nam', '[\"lunch\", \"dinner\"]', NULL, 1, 'soup', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(4, 'Món chay', 'mon-chay', 'Các món ăn chay thơm ngon và bổ dưỡng', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'vegetarian', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(5, 'Món khai vị', 'mon-khai-vi', 'Danh mục món ăn Món khai vị', '[\"lunch\", \"dinner\"]', NULL, 1, 'appetizer', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(6, 'Bún & Phở', 'bun-pho', 'Danh mục món ăn Bún & Phở', '[\"breakfast\", \"lunch\"]', NULL, 1, 'noodle', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(7, 'Cơm & Xôi', 'com-xoi', 'Danh mục món ăn Cơm & Xôi', '[\"breakfast\", \"lunch\", \"dinner\"]', NULL, 1, 'rice', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(8, 'Món Nhật Bản', 'mon-nhat-ban', 'Danh mục món ăn Món Nhật Bản', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(9, 'Món Hàn Quốc', 'mon-han-quoc', 'Danh mục món ăn Món Hàn Quốc', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(10, 'Món Trung Hoa', 'mon-trung-hoa', 'Danh mục món ăn Món Trung Hoa', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(11, 'Món Thái Lan', 'mon-thai-lan', 'Danh mục món ăn Món Thái Lan', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(12, 'Món Ý', 'mon-y', 'Danh mục món ăn Món Ý', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(13, 'Món Mỹ', 'mon-my', 'Danh mục món ăn Món Mỹ', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(14, 'Pizza & Pasta', 'pizza-pasta', 'Danh mục món ăn Pizza & Pasta', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(15, 'Burger & Sandwich', 'burger-sandwich', 'Danh mục món ăn Burger & Sandwich', '[\"lunch\", \"dinner\"]', NULL, 1, 'fast_food', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(16, 'Tráng miệng Việt Nam', 'trang-mieng-viet-nam', 'Danh mục món ăn Tráng miệng Việt Nam', '[\"any\"]', NULL, 1, 'dessert', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(17, 'Tráng miệng Âu Mỹ', 'trang-mieng-au-my', 'Danh mục món ăn Tráng miệng Âu Mỹ', '[\"any\"]', NULL, 1, 'dessert', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(18, 'Bánh ngọt', 'banh-ngot', 'Danh mục món ăn Bánh ngọt', '[\"breakfast\", \"any\"]', NULL, 1, 'dessert', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(19, 'Kem & Sorbet', 'kem-sorbet', 'Danh mục món ăn Kem & Sorbet', '[\"any\"]', NULL, 1, 'dessert', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(20, 'Đồ chiên rán', 'do-chien-ran', 'Danh mục món ăn Đồ chiên rán', '[\"any\"]', NULL, 1, 'fast_food', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(21, 'Đồ nướng', 'do-nuong', 'Danh mục món ăn Đồ nướng', '[\"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(22, 'Salad & Gỏi', 'salad-goi', 'Danh mục món ăn Salad & Gỏi', '[\"lunch\", \"dinner\"]', NULL, 1, 'salad', 'healthy', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(23, 'Món ăn sáng', 'mon-an-sang', 'Danh mục món ăn Món ăn sáng', '[\"breakfast\"]', NULL, 1, 'breakfast', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(24, 'Món lẩu', 'mon-lau', 'Danh mục món ăn Món lẩu', '[\"dinner\"]', NULL, 1, 'hotpot', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(25, 'Món hải sản', 'mon-hai-san', 'Danh mục món ăn Món hải sản', '[\"lunch\", \"dinner\"]', NULL, 1, 'main', 'normal', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(26, 'Đồ uống', 'do-uong', 'Nước ép trái cây', NULL, NULL, 1, 'dessert', 'regular', '2026-01-18 20:46:39', '2026-01-18 20:46:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dishes`
--

CREATE TABLE `dishes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `difficulty` enum('easy','medium','hard') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prep_time` int(10) UNSIGNED DEFAULT NULL COMMENT 'Thời gian chuẩn bị (phút)',
  `cook_time` int(10) UNSIGNED DEFAULT NULL COMMENT 'Thời gian nấu (phút)',
  `servings` int(10) UNSIGNED DEFAULT NULL COMMENT 'Khẩu phần',
  `calories` int(10) UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dishes`
--

INSERT INTO `dishes` (`id`, `name`, `slug`, `category_id`, `origin`, `description`, `difficulty`, `prep_time`, `cook_time`, `servings`, `calories`, `image`, `video_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bánh mì Pate', 'banh-mi-pate', 1, 'Việt Nam', 'ngon', 'easy', 10, 10, 1, 2, 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/2024_2_19_638439762164888519_cach-lam-banh-mi-pate-trung-7.jpg', 'https://www.youtube.com/watch?v=qDgMARnOtYo', 'active', '2026-01-18 01:34:17', '2026-01-18 01:34:17'),
(2, 'Phở bò', 'pho-bo', 6, 'Việt Nam', 'Món phở truyền thống với nước dùng thơm ngon và thịt bò mềm', 'medium', 20, 120, 4, 450, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(3, 'Bún chả', 'bun-cha', 6, 'Việt Nam', 'Bún chả Hà Nội nổi tiếng với thịt nướng thơm lừng', 'medium', 30, 45, 4, 500, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(4, 'Cơm gà Hội An', 'com-ga-hoi-an', 7, 'Việt Nam', 'Cơm gà thơm ngon với gà luộc và nước chấm đặc biệt', 'easy', 15, 40, 4, 550, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(5, 'Canh chua cá', 'canh-chua-ca', 3, 'Việt Nam', 'Canh chua cá với vị chua ngọt đặc trưng miền Nam', 'easy', 15, 25, 4, 200, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(6, 'Gà nướng muối ớt', 'ga-nuong-muoi-ot', 21, 'Việt Nam', 'Gà nướng với muối ớt cay thơm lừng', 'medium', 30, 60, 4, 400, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(7, 'Nem nướng Nha Trang', 'nem-nuong-nha-trang', 5, 'Việt Nam', 'Nem nướng thơm ngon đặc sản Nha Trang', 'medium', 45, 20, 6, 350, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(8, 'Bánh xèo', 'banh-xeo', 23, 'Việt Nam', 'Bánh xèo giòn rụm với nhân tôm thịt', 'medium', 30, 30, 4, 380, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(9, 'Chả giò', 'cha-gio', 20, 'Việt Nam', 'Chả giò giòn rụm với nhân thịt rau củ', 'easy', 40, 20, 6, 320, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(10, 'Cá kho tộ', 'ca-kho-to', 2, 'Việt Nam', 'Cá kho tộ với nước kho đậm đà', 'easy', 10, 60, 4, 300, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(11, 'Thịt kho tàu', 'thit-kho-tau', 2, 'Việt Nam', 'Thịt heo kho tàu với trứng và nước dừa', 'easy', 15, 90, 4, 450, NULL, NULL, 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(12, 'Nước ép cam', 'nuoc-ep-cam', 26, 'Việt Nam', 'Nước ép cam là thức uống được làm từ cam tươi ép trực tiếp, có vị ngọt chua tự nhiên, hương thơm dễ chịu và màu vàng cam đặc trưng. Thức uống này giàu vitamin C, giúp tăng cường sức đề kháng, giải khát hiệu quả và tốt cho làn da. Nước ép cam thường được dùng lạnh, phù hợp cho mọi lứa tuổi và phổ biến trong thực đơn đồ uống lành mạnh.', 'easy', 5, 1, 1, 24, 'https://thanhnien.mediacdn.vn/uploaded/triquang/2017_11_12/camep_OCMA.jpg?width=500', 'https://www.youtube.com/watch?v=wNPjXplib_M', 'active', '2026-01-18 20:57:36', '2026-01-18 20:57:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dish_ingredients`
--

CREATE TABLE `dish_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dish_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số lượng (ví dụ: 200, 1.5)',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đơn vị (ví dụ: g, kg, ml, cup)',
  `is_required` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Bắt buộc hay tùy chọn',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dish_ingredients`
--

INSERT INTO `dish_ingredients` (`id`, `dish_id`, `ingredient_id`, `quantity`, `unit`, `is_required`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '100', 'g', 1, '2026-01-18 01:34:17', '2026-01-18 01:34:17'),
(2, 2, 73, '400', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(3, 2, 27, '300', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(4, 2, 3, '2', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(5, 2, 6, '1', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(6, 2, 4, '100', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(7, 2, 49, '50', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(8, 3, 72, '500', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(9, 3, 26, '400', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(10, 3, 49, '100', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(11, 3, 53, '50', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(12, 3, 5, '10', 'tép', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(13, 3, 7, '5', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(14, 4, 71, '400', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(15, 4, 28, '500', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(16, 4, 6, '1', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(17, 4, 4, '50', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(18, 5, 35, '500', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(19, 5, 86, '1', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(20, 5, 2, '3', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(21, 5, 19, '200', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(22, 5, 8, '2', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(23, 5, 49, '50', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(24, 6, 28, '1', 'kg', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(25, 6, 52, '20', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(26, 6, 7, '10', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(27, 6, 5, '10', 'tép', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(28, 6, 6, '1', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(29, 7, 26, '500', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(30, 7, 80, '100', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(31, 7, 5, '5', 'tép', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(32, 7, 53, '30', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(33, 7, 49, '30', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(34, 8, 81, '300', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(35, 8, 33, '200', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(36, 8, 26, '200', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(37, 8, 51, '100', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(38, 9, 26, '300', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(39, 9, 8, '2', 'củ', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(40, 9, 23, '100', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(41, 10, 35, '600', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(42, 10, 49, '100', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(43, 10, 53, '50', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(44, 10, 55, '10', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(45, 10, 7, '3', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(46, 11, 26, '500', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(47, 11, 43, '6', 'quả', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(48, 11, 82, '200', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(49, 11, 49, '80', 'ml', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(50, 11, 53, '40', 'g', 1, '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(52, 12, 88, '200', 'g', 1, '2026-01-18 20:59:09', '2026-01-18 20:59:09'),
(53, 12, 89, '50', 'g', 1, '2026-01-18 20:59:09', '2026-01-18 20:59:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Thời gian giao hàng mất bao lâu?', 'Đơn nội thành 1-2 ngày, ngoại tỉnh 2-5 ngày làm việc. Bạn sẽ nhận SMS kèm mã vận đơn để theo dõi.', 1, 1, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(2, 'Chính sách bảo hành như thế nào?', 'Tất cả laptop chính hãng bảo hành tối thiểu 12 tháng tại TTBH uỷ quyền. Chúng tôi hỗ trợ nhận - trả máy bảo hành miễn phí.', 1, 2, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(3, 'Có hỗ trợ trả góp không?', 'Có. Hỗ trợ trả góp qua thẻ tín dụng hoặc công ty tài chính, lãi suất ưu đãi. Liên hệ CSKH để được tư vấn chi tiết.', 1, 3, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(4, 'Nếu sản phẩm bị lỗi tôi phải làm gì?', 'Trong 7 ngày đầu, lỗi do nhà sản xuất được 1 đổi 1. Sau thời gian này áp dụng bảo hành tiêu chuẩn tại TTBH.', 1, 4, '2026-01-17 11:22:58', '2026-01-17 11:22:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorite_dishes`
--

CREATE TABLE `favorite_dishes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `favorite_dishes`
--

INSERT INTO `favorite_dishes` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 4, 14, '2026-01-04 00:45:38'),
(2, 4, 7, '2026-01-15 00:45:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredients`
--

CREATE TABLE `ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`, `slug`, `type`, `unit`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pate', 'pate', 'Gan', NULL, NULL, 'active', '2026-01-18 01:32:44', '2026-01-18 01:32:44'),
(2, 'Cà chua', 'ca-chua', 'Rau củ', 'quả', 'Nguyên liệu Cà chua thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(3, 'Hành tây', 'hanh-tay', 'Rau củ', 'củ', 'Nguyên liệu Hành tây thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(4, 'Hành lá', 'hanh-la', 'Rau củ', 'nhánh', 'Nguyên liệu Hành lá thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(5, 'Tỏi', 'toi', 'Gia vị', 'tép', 'Nguyên liệu Tỏi thuộc loại Gia vị', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(6, 'Gừng', 'gung', 'Gia vị', 'củ', 'Nguyên liệu Gừng thuộc loại Gia vị', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(7, 'Ớt', 'ot', 'Gia vị', 'quả', 'Nguyên liệu Ớt thuộc loại Gia vị', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(8, 'Cà rốt', 'ca-rot', 'Rau củ', 'củ', 'Nguyên liệu Cà rốt thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(9, 'Khoai tây', 'khoai-tay', 'Rau củ', 'củ', 'Nguyên liệu Khoai tây thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(10, 'Cải bắp', 'cai-bap', 'Rau củ', 'lá', 'Nguyên liệu Cải bắp thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(11, 'Cải thảo', 'cai-thao', 'Rau củ', 'lá', 'Nguyên liệu Cải thảo thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(12, 'Rau muống', 'rau-muong', 'Rau củ', 'bó', 'Nguyên liệu Rau muống thuộc loại Rau củ', 'active', '2026-01-18 08:54:17', '2026-01-18 08:54:17'),
(13, 'Rau cải', 'rau-cai', 'Rau củ', 'bó', 'Nguyên liệu Rau cải thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(14, 'Rau mồng tơi', 'rau-mong-toi', 'Rau củ', 'bó', 'Nguyên liệu Rau mồng tơi thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(15, 'Rau ngót', 'rau-ngot', 'Rau củ', 'bó', 'Nguyên liệu Rau ngót thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(16, 'Bí đỏ', 'bi-do', 'Rau củ', 'kg', 'Nguyên liệu Bí đỏ thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(17, 'Bí xanh', 'bi-xanh', 'Rau củ', 'kg', 'Nguyên liệu Bí xanh thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(18, 'Đậu cô ve', 'dau-co-ve', 'Rau củ', 'g', 'Nguyên liệu Đậu cô ve thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(19, 'Đậu bắp', 'dau-bap', 'Rau củ', 'quả', 'Nguyên liệu Đậu bắp thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(20, 'Mướp', 'muop', 'Rau củ', 'quả', 'Nguyên liệu Mướp thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(21, 'Cà tím', 'ca-tim', 'Rau củ', 'quả', 'Nguyên liệu Cà tím thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(22, 'Ớt chuông', 'ot-chuong', 'Rau củ', 'quả', 'Nguyên liệu Ớt chuông thuộc loại Rau củ', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(23, 'Nấm hương', 'nam-huong', 'Nấm', 'g', 'Nguyên liệu Nấm hương thuộc loại Nấm', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(24, 'Nấm kim châm', 'nam-kim-cham', 'Nấm', 'g', 'Nguyên liệu Nấm kim châm thuộc loại Nấm', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(25, 'Nấm rơm', 'nam-rom', 'Nấm', 'g', 'Nguyên liệu Nấm rơm thuộc loại Nấm', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(26, 'Thịt heo', 'thit-heo', 'Thịt', 'g', 'Nguyên liệu Thịt heo thuộc loại Thịt', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(27, 'Thịt bò', 'thit-bo', 'Thịt', 'g', 'Nguyên liệu Thịt bò thuộc loại Thịt', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(28, 'Thịt gà', 'thit-ga', 'Thịt', 'g', 'Nguyên liệu Thịt gà thuộc loại Thịt', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(29, 'Thịt vịt', 'thit-vit', 'Thịt', 'g', 'Nguyên liệu Thịt vịt thuộc loại Thịt', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(30, 'Xúc xích', 'xuc-xich', 'Thịt chế biến', 'cái', 'Nguyên liệu Xúc xích thuộc loại Thịt chế biến', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(31, 'Thịt nguội', 'thit-nguoi', 'Thịt chế biến', 'g', 'Nguyên liệu Thịt nguội thuộc loại Thịt chế biến', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(32, 'Thịt xông khói', 'thit-xong-khoi', 'Thịt chế biến', 'g', 'Nguyên liệu Thịt xông khói thuộc loại Thịt chế biến', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(33, 'Tôm', 'tom', 'Hải sản', 'con', 'Nguyên liệu Tôm thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(34, 'Cua', 'cua', 'Hải sản', 'con', 'Nguyên liệu Cua thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(35, 'Cá', 'ca', 'Hải sản', 'kg', 'Nguyên liệu Cá thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(36, 'Mực', 'muc', 'Hải sản', 'con', 'Nguyên liệu Mực thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(37, 'Nghêu', 'ngheu', 'Hải sản', 'kg', 'Nguyên liệu Nghêu thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(38, 'Sò', 'so', 'Hải sản', 'kg', 'Nguyên liệu Sò thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(39, 'Tôm sú', 'tom-su', 'Hải sản', 'con', 'Nguyên liệu Tôm sú thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(40, 'Cá hồi', 'ca-hoi', 'Hải sản', 'g', 'Nguyên liệu Cá hồi thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(41, 'Cá basa', 'ca-basa', 'Hải sản', 'g', 'Nguyên liệu Cá basa thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(42, 'Cá lóc', 'ca-loc', 'Hải sản', 'g', 'Nguyên liệu Cá lóc thuộc loại Hải sản', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(43, 'Trứng gà', 'trung-ga', 'Trứng', 'quả', 'Nguyên liệu Trứng gà thuộc loại Trứng', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(44, 'Trứng vịt', 'trung-vit', 'Trứng', 'quả', 'Nguyên liệu Trứng vịt thuộc loại Trứng', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(45, 'Sữa tươi', 'sua-tuoi', 'Sữa', 'ml', 'Nguyên liệu Sữa tươi thuộc loại Sữa', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(46, 'Phô mai', 'pho-mai', 'Sữa', 'g', 'Nguyên liệu Phô mai thuộc loại Sữa', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(47, 'Bơ', 'bo', 'Sữa', 'g', 'Nguyên liệu Bơ thuộc loại Sữa', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(48, 'Sữa chua', 'sua-chua', 'Sữa', 'hũ', 'Nguyên liệu Sữa chua thuộc loại Sữa', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(49, 'Nước mắm', 'nuoc-mam', 'Gia vị', 'ml', 'Nguyên liệu Nước mắm thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(50, 'Nước tương', 'nuoc-tuong', 'Gia vị', 'ml', 'Nguyên liệu Nước tương thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(51, 'Dầu ăn', 'dau-an', 'Gia vị', 'ml', 'Nguyên liệu Dầu ăn thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(52, 'Muối', 'muoi', 'Gia vị', 'g', 'Nguyên liệu Muối thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(53, 'Đường', 'duong', 'Gia vị', 'g', 'Nguyên liệu Đường thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(54, 'Bột ngọt', 'bot-ngot', 'Gia vị', 'g', 'Nguyên liệu Bột ngọt thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(55, 'Tiêu', 'tieu', 'Gia vị', 'g', 'Nguyên liệu Tiêu thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(56, 'Tương ớt', 'tuong-ot', 'Gia vị', 'ml', 'Nguyên liệu Tương ớt thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(57, 'Sốt cà chua', 'sot-ca-chua', 'Gia vị', 'ml', 'Nguyên liệu Sốt cà chua thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(58, 'Mật ong', 'mat-ong', 'Gia vị', 'ml', 'Nguyên liệu Mật ong thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(59, 'Sả', 'sa', 'Gia vị', 'cây', 'Nguyên liệu Sả thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(60, 'Lá chanh', 'la-chanh', 'Gia vị', 'lá', 'Nguyên liệu Lá chanh thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(61, 'Ngò rí', 'ngo-ri', 'Gia vị', 'nhánh', 'Nguyên liệu Ngò rí thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(62, 'Rau răm', 'rau-ram', 'Gia vị', 'nhánh', 'Nguyên liệu Rau răm thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(63, 'Lá lốt', 'la-lot', 'Gia vị', 'lá', 'Nguyên liệu Lá lốt thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(64, 'Nghệ', 'nghe', 'Gia vị', 'củ', 'Nguyên liệu Nghệ thuộc loại Gia vị', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(65, 'Đậu phụ', 'dau-phu', 'Đậu', 'miếng', 'Nguyên liệu Đậu phụ thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(66, 'Đậu nành', 'dau-nanh', 'Đậu', 'g', 'Nguyên liệu Đậu nành thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(67, 'Đậu xanh', 'dau-xanh', 'Đậu', 'g', 'Nguyên liệu Đậu xanh thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(68, 'Đậu đỏ', 'dau-do', 'Đậu', 'g', 'Nguyên liệu Đậu đỏ thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(69, 'Đậu phộng', 'dau-phong', 'Đậu', 'g', 'Nguyên liệu Đậu phộng thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(70, 'Đậu đen', 'dau-den', 'Đậu', 'g', 'Nguyên liệu Đậu đen thuộc loại Đậu', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(71, 'Gạo', 'gao', 'Tinh bột', 'g', 'Nguyên liệu Gạo thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(72, 'Bún', 'bun', 'Tinh bột', 'g', 'Nguyên liệu Bún thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(73, 'Phở', 'pho', 'Tinh bột', 'phần', 'Nguyên liệu Phở thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(74, 'Mì gói', 'mi-goi', 'Tinh bột', 'gói', 'Nguyên liệu Mì gói thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(75, 'Mì tươi', 'mi-tuoi', 'Tinh bột', 'g', 'Nguyên liệu Mì tươi thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(76, 'Bánh phở', 'banh-pho', 'Tinh bột', 'kg', 'Nguyên liệu Bánh phở thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(77, 'Mì ống', 'mi-ong', 'Tinh bột', 'g', 'Nguyên liệu Mì ống thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(78, 'Bánh mì', 'banh-mi', 'Tinh bột', 'ổ', 'Nguyên liệu Bánh mì thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(79, 'Bột mì', 'bot-mi', 'Tinh bột', 'g', 'Nguyên liệu Bột mì thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(80, 'Bột năng', 'bot-nang', 'Tinh bột', 'g', 'Nguyên liệu Bột năng thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(81, 'Bột gạo', 'bot-gao', 'Tinh bột', 'g', 'Nguyên liệu Bột gạo thuộc loại Tinh bột', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(82, 'Nước dừa', 'nuoc-dua', 'Đồ uống', 'ml', 'Nguyên liệu Nước dừa thuộc loại Đồ uống', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(83, 'Dừa nạo', 'dua-nao', 'Trái cây', 'g', 'Nguyên liệu Dừa nạo thuộc loại Trái cây', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(84, 'Chuối', 'chuoi', 'Trái cây', 'quả', 'Nguyên liệu Chuối thuộc loại Trái cây', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(85, 'Chanh', 'chanh', 'Trái cây', 'quả', 'Nguyên liệu Chanh thuộc loại Trái cây', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(86, 'Dứa', 'dua', 'Trái cây', 'quả', 'Nguyên liệu Dứa thuộc loại Trái cây', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(87, 'Xoài', 'xoai', 'Trái cây', 'quả', 'Nguyên liệu Xoài thuộc loại Trái cây', 'active', '2026-01-18 08:54:18', '2026-01-18 08:54:18'),
(88, 'Quả cam', 'qua-cam', 'Trái cây', NULL, 'Cam là loại trái cây họ cam quýt, vị ngọt chua, giàu vitamin C, giúp tăng sức đề kháng và tốt cho da.', 'active', '2026-01-18 20:45:46', '2026-01-18 20:45:46'),
(89, 'Đá viên', 'da-vien', 'Đá viên', NULL, 'lạnh', 'active', '2026-01-18 20:58:28', '2026-01-18 20:58:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredient_nutrition`
--

CREATE TABLE `ingredient_nutrition` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `calories` double DEFAULT NULL COMMENT 'Kcal / 100g',
  `protein` double DEFAULT NULL COMMENT 'Protein (g)',
  `fat` double DEFAULT NULL COMMENT 'Chất béo (g)',
  `carbs` double DEFAULT NULL COMMENT 'Carb (g)',
  `fiber` double DEFAULT NULL COMMENT 'Chất xơ (g)',
  `vitamins` text COLLATE utf8mb4_unicode_ci COMMENT 'Vitamin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredient_nutrition`
--

INSERT INTO `ingredient_nutrition` (`id`, `ingredient_id`, `calories`, `protein`, `fat`, `carbs`, `fiber`, `vitamins`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 10, 2, 2, 0, NULL, '2026-01-18 01:32:44', '2026-01-18 01:32:44'),
(2, 88, 47, 0.9, 0.1, 11.8, 2.4, 'Vitamin C, Vitamin A, Vitamin B1, B9 (Folate)', '2026-01-18 20:45:46', '2026-01-18 20:45:46'),
(3, 89, 0, 0, 0, 0, 0, '0', '2026-01-18 20:58:28', '2026-01-18 20:58:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_27_105427_create_products_table', 1),
(5, '2025_08_27_120133_add_role_to_users_table', 1),
(6, '2025_08_29_014017_create_categories_table', 1),
(7, '2025_08_29_014336_add_category_id_to_products_table', 1),
(8, '2025_09_12_054921_create_news_table', 1),
(9, '2025_09_12_060158_add_image_url_to_news_table', 1),
(10, '2025_09_18_165127_add_google_id_to_users_table', 1),
(11, '2025_09_18_191543_create_reviews_table', 1),
(12, '2025_09_19_000000_create_faqs_table', 1),
(13, '2026_01_03_000000_create_ingredients_table', 1),
(14, '2026_01_03_000200_add_fields_to_categories_table', 1),
(15, '2026_01_03_000300_add_video_url_to_products_table', 1),
(16, '2026_01_03_000400_update_products_fields', 1),
(17, '2026_01_04_000002_ensure_unit_on_ingredients_table', 1),
(18, '2026_01_08_000001_add_origin_to_products_table', 1),
(19, '2026_01_17_181014_convert_role_to_enum_in_users_table', 1),
(20, '2026_01_18_000000_create_favorite_dishes_table', 2),
(21, '2026_01_18_075511_add_status_and_last_login_to_users_table', 3),
(22, '2026_01_19_000000_create_dishes_table', 4),
(23, '2026_01_19_000001_create_dish_ingredients_table', 4),
(24, '2026_01_18_082001_add_meal_type_and_diet_type_to_categories_table', 5),
(25, '2026_01_18_082726_add_status_to_ingredients_table', 6),
(26, '2026_01_18_082733_create_ingredient_nutrition_table', 6),
(27, '2026_01_18_083831_add_dish_id_and_status_to_reviews_table', 7),
(28, '2026_01_18_085531_add_avatar_to_users_table', 8),
(29, '2026_01_18_154255_create_user_preferences_table', 9),
(30, '2026_01_18_155932_create_user_ingredients_table', 10),
(31, '2026_01_18_161839_add_added_at_to_user_ingredients_table', 11),
(32, '2026_01_18_164950_create_user_food_histories_table', 12),
(33, '2026_01_18_165928_add_onboarding_fields_to_user_preferences_table', 13),
(37, '2026_01_18_172739_add_saved_action_to_user_food_histories_table', 14),
(38, '2026_01_19_023036_make_product_id_nullable_in_reviews_table', 14),
(39, '2026_01_19_023637_create_scan_history_table', 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `views` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `image`, `image_url`, `author`, `is_featured`, `is_published`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Gaming Mới Nhất 2024: Hiệu Suất Vượt Trội', 'laptop-gaming-moi-nhat-2024-hieu-suat-vuot-troi', 'Khám phá những chiếc laptop gaming mới nhất với card đồ họa RTX 40 series và bộ xử lý Intel Core i9 thế hệ 14.', 'Năm 2024 đánh dấu một bước tiến lớn trong công nghệ laptop gaming. Các nhà sản xuất hàng đầu như ASUS, MSI, và Dell đã cho ra mắt những mẫu laptop gaming với hiệu suất vượt trội.\n\n**Những điểm nổi bật:**\n\n1. **Card đồ họa RTX 40 series**: Mang lại hiệu suất gaming cao hơn 30% so với thế hệ trước\n2. **Bộ xử lý Intel Core i9 thế hệ 14**: Tốc độ xử lý nhanh hơn, tiết kiệm điện năng\n3. **Màn hình OLED 240Hz**: Trải nghiệm gaming mượt mà và chân thực\n4. **Hệ thống tản nhiệt tiên tiến**: Giữ laptop luôn mát mẻ trong quá trình gaming\n\n**Giá cả và khuyến mãi:**\n- Laptop gaming cao cấp: từ 25-50 triệu đồng\n- Laptop gaming tầm trung: từ 15-25 triệu đồng\n- Nhiều chương trình khuyến mãi hấp dẫn trong tháng này\n\nHãy đến cửa hàng để trải nghiệm trực tiếp những chiếc laptop gaming mới nhất!', NULL, 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80', 'Admin', 1, 1, 1250, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(2, 'Cách Chọn Laptop Văn Phòng Phù Hợp', 'cach-chon-laptop-van-phong-phu-hop', 'Hướng dẫn chi tiết cách chọn laptop văn phòng phù hợp với nhu cầu công việc và ngân sách.', 'Việc chọn laptop văn phòng phù hợp là rất quan trọng để đảm bảo hiệu quả công việc. Dưới đây là những tiêu chí quan trọng:\n\n**1. Hiệu suất xử lý:**\n- Intel Core i5 hoặc AMD Ryzen 5 cho công việc văn phòng cơ bản\n- Intel Core i7 hoặc AMD Ryzen 7 cho công việc nặng hơn\n- RAM tối thiểu 8GB, khuyến nghị 16GB\n\n**2. Dung lượng lưu trữ:**\n- SSD 256GB trở lên cho tốc độ khởi động nhanh\n- HDD 1TB cho lưu trữ dữ liệu lớn\n- Kết hợp SSD + HDD là lý tưởng nhất\n\n**3. Màn hình:**\n- Kích thước 14-15.6 inch phù hợp cho văn phòng\n- Độ phân giải Full HD (1920x1080) trở lên\n- Màn hình IPS cho góc nhìn rộng\n\n**4. Pin và di động:**\n- Thời lượng pin 8-10 giờ cho cả ngày làm việc\n- Trọng lượng dưới 2kg để dễ di chuyển\n- Cổng kết nối đầy đủ: USB, HDMI, Thunderbolt\n\n**5. Giá cả:**\n- Tầm trung: 15-25 triệu đồng\n- Cao cấp: 25-40 triệu đồng\n\nLiên hệ tư vấn để chọn được laptop phù hợp nhất!', NULL, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80', 'Admin', 1, 1, 980, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(3, 'Xu Hướng Laptop Học Tập 2024', 'xu-huong-laptop-hoc-tap-2024', 'Những xu hướng laptop học tập mới nhất với giá cả hợp lý và tính năng phù hợp cho sinh viên.', 'Năm 2024, thị trường laptop học tập có nhiều thay đổi tích cực với những sản phẩm chất lượng cao và giá cả hợp lý.\n\n**Xu hướng nổi bật:**\n\n**1. Laptop Chromebook:**\n- Giá rẻ, phù hợp với ngân sách sinh viên\n- Thời lượng pin dài, khởi động nhanh\n- Tích hợp Google Workspace cho học tập\n\n**2. Laptop Windows giá rẻ:**\n- Intel Pentium hoặc Celeron cho công việc cơ bản\n- RAM 4-8GB, SSD 128-256GB\n- Giá từ 8-15 triệu đồng\n\n**3. Laptop 2-in-1:**\n- Có thể chuyển đổi thành tablet\n- Phù hợp cho ghi chú và vẽ\n- Màn hình cảm ứng tiện lợi\n\n**4. Laptop gaming tầm trung:**\n- Card đồ họa tích hợp hoặc GTX 1650\n- Phù hợp cho cả học tập và giải trí\n- Giá từ 15-25 triệu đồng\n\n**Khuyến nghị theo ngành học:**\n- **Kỹ thuật**: Laptop có card đồ họa, RAM 16GB+\n- **Thiết kế**: Màn hình màu sắc chính xác, card đồ họa mạnh\n- **Kinh tế**: Laptop cơ bản, giá rẻ, pin dài\n- **Y khoa**: Laptop nhẹ, pin dài, màn hình tốt\n\n**Chương trình hỗ trợ sinh viên:**\n- Giảm giá 5-10% cho sinh viên\n- Bảo hành mở rộng\n- Hỗ trợ trả góp 0% lãi suất', NULL, 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80', 'Admin', 1, 1, 756, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(4, 'So Sánh Intel vs AMD: Nên Chọn Bộ Xử Lý Nào?', 'so-sanh-intel-vs-amd-nen-chon-bo-xu-ly-nao', 'Phân tích chi tiết về ưu nhược điểm của Intel và AMD để giúp bạn chọn bộ xử lý phù hợp.', 'Cuộc chiến giữa Intel và AMD đã kéo dài nhiều năm với những cải tiến liên tục. Dưới đây là so sánh chi tiết:\n\n**Intel Core Series:**\n\n**Ưu điểm:**\n- Hiệu suất đơn nhân mạnh mẽ\n- Tương thích tốt với phần mềm cũ\n- Hỗ trợ Thunderbolt và Quick Sync\n- Tiết kiệm điện năng tốt\n\n**Nhược điểm:**\n- Giá cao hơn AMD\n- Hiệu suất đa nhân kém hơn\n- Tản nhiệt cao hơn\n\n**AMD Ryzen Series:**\n\n**Ưu điểm:**\n- Hiệu suất đa nhân vượt trội\n- Giá cả cạnh tranh\n- Tích hợp card đồ họa Vega/RDNA\n- Nhiều lõi và luồng hơn\n\n**Nhược điểm:**\n- Hiệu suất đơn nhân kém hơn Intel\n- Tiêu thụ điện năng cao hơn\n- Tương thích phần mềm cũ kém hơn\n\n**Khuyến nghị theo nhu cầu:**\n\n**Gaming:**\n- Intel Core i5/i7 cho gaming thuần túy\n- AMD Ryzen 5/7 cho gaming + streaming\n\n**Văn phòng:**\n- Intel Core i3/i5 cho công việc cơ bản\n- AMD Ryzen 3/5 cho đa tác vụ\n\n**Sáng tạo nội dung:**\n- AMD Ryzen 7/9 cho render video\n- Intel Core i7/i9 cho Adobe Creative Suite\n\n**Học tập:**\n- AMD Ryzen 3/5 cho ngân sách hạn chế\n- Intel Core i3/i5 cho ổn định lâu dài\n\nCả hai đều là lựa chọn tốt, quan trọng là phù hợp với nhu cầu và ngân sách của bạn.', NULL, 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80', 'Admin', 0, 1, 432, '2026-01-17 11:22:58', '2026-01-17 11:22:58'),
(5, 'Cách Bảo Quản Laptop Đúng Cách', 'cach-bao-quan-laptop-dung-cach', 'Hướng dẫn chi tiết cách bảo quản laptop để tăng tuổi thọ và duy trì hiệu suất tốt nhất.', 'Việc bảo quản laptop đúng cách sẽ giúp tăng tuổi thọ và duy trì hiệu suất tốt nhất. Dưới đây là những lời khuyên hữu ích:\n\n**1. Vệ sinh định kỳ:**\n- Lau màn hình bằng khăn mềm và dung dịch chuyên dụng\n- Vệ sinh bàn phím bằng bàn chải mềm\n- Làm sạch cổng kết nối bằng tăm bông\n- Vệ sinh quạt tản nhiệt mỗi 3-6 tháng\n\n**2. Quản lý nhiệt độ:**\n- Sử dụng đế tản nhiệt khi làm việc lâu\n- Không đặt laptop trên đùi hoặc chăn\n- Đảm bảo không khí lưu thông tốt\n- Tắt laptop khi không sử dụng\n\n**3. Quản lý pin:**\n- Không để pin cạn kiệt hoàn toàn\n- Sạc pin khi còn 20-30%\n- Tháo sạc khi pin đầy\n- Calibrate pin mỗi 2-3 tháng\n\n**4. Bảo vệ vật lý:**\n- Sử dụng túi chống sốc khi di chuyển\n- Tránh va đập và rơi rớt\n- Không đặt vật nặng lên laptop\n- Đóng mở laptop nhẹ nhàng\n\n**5. Bảo mật dữ liệu:**\n- Cài đặt phần mềm diệt virus\n- Sao lưu dữ liệu định kỳ\n- Cập nhật hệ điều hành thường xuyên\n- Sử dụng mật khẩu mạnh\n\n**6. Phần mềm:**\n- Gỡ bỏ phần mềm không cần thiết\n- Dọn dẹp ổ cứng định kỳ\n- Cập nhật driver thiết bị\n- Sử dụng phần mềm quản lý hệ thống\n\n**7. Bảo hành và sửa chữa:**\n- Giữ hóa đơn mua hàng\n- Đăng ký bảo hành đầy đủ\n- Liên hệ trung tâm bảo hành khi có lỗi\n- Không tự ý tháo lắp laptop\n\nLàm theo những hướng dẫn này sẽ giúp laptop của bạn hoạt động tốt và bền lâu hơn.', NULL, 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80', 'Admin', 0, 1, 321, '2026-01-17 11:22:58', '2026-01-17 11:22:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difficulty` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prep_time` int(10) UNSIGNED DEFAULT NULL,
  `cook_time` int(10) UNSIGNED DEFAULT NULL,
  `servings` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `video_url`, `difficulty`, `prep_time`, `cook_time`, `servings`, `created_at`, `updated_at`, `category_id`, `origin`) VALUES
(1, 'Bún Bò Huế', 'Món ăn Bún Bò Huế thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop&crop=center', NULL, 'Khó', 22, 49, 7, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(2, 'Cao Lầu', 'Món ăn Cao Lầu thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop&crop=center', NULL, 'Trung bình', 52, 79, 2, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(3, 'Gỏi Cuốn', 'Món ăn Gỏi Cuốn thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop&crop=center', NULL, 'Trung bình', 14, 67, 4, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(4, 'Bánh Mì', 'Món ăn Bánh Mì thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 11, 87, 4, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(5, 'Bún Bò Huế', 'Món ăn Bún Bò Huế thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 59, 100, 4, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(6, 'Chả Giò', 'Món ăn Chả Giò thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 14, 50, 5, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(7, 'Hủ Tiếu', 'Món ăn Hủ Tiếu thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center', NULL, 'Trung bình', 14, 39, 3, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(8, 'Bún Riêu', 'Món ăn Bún Riêu thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 38, 106, 3, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(9, 'Chả Giò', 'Món ăn Chả Giò thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 37, 85, 2, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(10, 'Bún Riêu', 'Món ăn Bún Riêu thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=400&fit=crop&crop=center', NULL, 'Dễ', 15, 88, 2, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(11, 'Chả Giò', 'Món ăn Chả Giò thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center', NULL, 'Trung bình', 22, 65, 7, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(12, 'Nem Nướng', 'Món ăn Nem Nướng thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&crop=center', NULL, 'Trung bình', 54, 112, 7, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(13, 'Hủ Tiếu', 'Món ăn Hủ Tiếu thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=400&h=400&fit=crop&crop=center', NULL, 'Khó', 39, 25, 4, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(14, 'Bánh Mì', 'Món ăn Bánh Mì thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=400&fit=crop&crop=center', NULL, 'Khó', 53, 61, 5, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL),
(15, 'Cơm Tấm', 'Món ăn Cơm Tấm thơm ngon, đậm đà hương vị truyền thống. Công thức được chế biến cẩn thận với các nguyên liệu tươi ngon, phù hợp cho bữa ăn gia đình.', 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=400&h=400&fit=crop&crop=center', NULL, 'Khó', 52, 51, 5, '2026-01-17 11:22:58', '2026-01-17 11:22:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dish_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('visible','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'visible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `dish_id`, `rating`, `comment`, `is_approved`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, 8, 5, 'ngon', 1, 'visible', '2026-01-18 19:34:31', '2026-01-18 19:34:31');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `scan_history`
--

CREATE TABLE `scan_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detected_ingredients` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `scan_history`
--

INSERT INTO `scan_history` (`id`, `user_id`, `image_path`, `detected_ingredients`, `created_at`, `updated_at`) VALUES
(1, 4, 'temp/scans/dVBwh5jhxqC4r7EPW1vChb3gnFHwArpEW6bfzykD.jpg', '[{\"ingredient_id\": 70, \"ingredient_name\": \"Đậu đen\", \"confidence_score\": 0.71}, {\"ingredient_id\": 60, \"ingredient_name\": \"Lá chanh\", \"confidence_score\": 0.75}]', '2026-01-18 19:45:32', '2026-01-18 19:45:32'),
(2, 4, 'temp/scans/XiSm5ucbQXxwaH6tboSi97nrOAngOiFD21UUMequ.jpg', '[{\"detected_name\": \"Quả cam\", \"ingredient_id\": 88, \"ingredient_name\": \"Quả cam\", \"confidence_score\": 0.95}]', '2026-01-18 21:01:12', '2026-01-18 21:01:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hRJ2YCCbQW6EhaEaczhTZmSn8gEYCgtfaS9dcPAO', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR2lORjEyOENKZGdJMVVtaHdQRjFnbUNacHJCQ2NCd0p1VlJqak1vYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYW1lcmEiO3M6NToicm91dGUiO3M6MTE6ImNhbWVyYS5zY2FuIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1768795302);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `google_id`, `role`, `status`, `email_verified_at`, `last_login`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'admin@gmail.com', NULL, NULL, 'admin', 'active', '2026-01-17 18:26:21', NULL, '$2y$12$dFv64k5.1Kq9AF2PgHqTyeQVcVIIX.lR1x64/TuMsFa3tQPGXsD/y', NULL, '2026-01-17 11:24:05', '2026-01-17 11:24:05'),
(4, 'Đào Văn Tâm', 'tam@gmail.com', 'images/avatars/1768726759_696ca0e7c4519.jpg', NULL, 'user', 'active', '2026-01-19 02:29:15', NULL, '$2y$12$cTiORu/Fh8.QqQmJRgMY8.yLK5uuYWnYO/U4T6k9NQLjX1ITICXja', NULL, '2026-01-17 23:28:36', '2026-01-18 01:59:19'),
(5, 'Nguyễn Văn Việt', 'viet@gmail.com', NULL, NULL, 'user', 'active', NULL, NULL, '$2y$12$TvEgcEGiUEG8AERCGmzFROYKbN4EBIRESgmOsIeUstD.NUuq2GhtG', NULL, '2026-01-18 10:03:11', '2026-01-18 10:03:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_food_histories`
--

CREATE TABLE `user_food_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dish_id` bigint(20) UNSIGNED NOT NULL,
  `action` enum('viewed','saved','cooked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'viewed',
  `action_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_food_histories`
--

INSERT INTO `user_food_histories` (`id`, `user_id`, `dish_id`, `action`, `action_at`, `created_at`, `updated_at`) VALUES
(1, 4, 8, 'cooked', '2026-01-18 09:55:53', '2026-01-18 09:55:53', '2026-01-18 09:55:53'),
(2, 4, 8, 'viewed', '2026-01-18 09:55:54', '2026-01-18 09:55:54', '2026-01-18 09:55:54'),
(3, 5, 8, 'viewed', '2026-01-18 10:26:03', '2026-01-18 10:26:03', '2026-01-18 10:26:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_ingredients`
--

CREATE TABLE `user_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ingredient_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày thêm vào tủ lạnh',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_ingredients`
--

INSERT INTO `user_ingredients` (`id`, `user_id`, `ingredient_id`, `quantity`, `unit`, `added_at`, `created_at`, `updated_at`) VALUES
(1, 4, 2, '2', 'quả', NULL, '2026-01-18 09:06:01', '2026-01-18 09:06:01'),
(2, 4, 35, '4', 'con', NULL, '2026-01-18 09:06:21', '2026-01-18 09:06:21'),
(5, 4, 17, '4', 'quả', '2026-01-18 09:44:52', '2026-01-18 09:44:52', '2026-01-18 09:44:52'),
(6, 4, 81, '500', 'g', '2026-01-18 09:45:04', '2026-01-18 09:45:04', '2026-01-18 09:45:04'),
(7, 4, 26, '500', 'g', '2026-01-18 09:45:53', '2026-01-18 09:45:53', '2026-01-18 09:45:53'),
(8, 4, 33, '5', 'con', '2026-01-18 09:46:03', '2026-01-18 09:46:03', '2026-01-18 09:46:03'),
(9, 4, 51, '500', 'ml', '2026-01-18 09:46:13', '2026-01-18 09:46:13', '2026-01-18 09:46:13'),
(10, 5, 78, '500', 'Chiếc', '2026-01-18 10:24:38', '2026-01-18 10:24:38', '2026-01-18 10:24:38'),
(11, 5, 76, '4', 'Chiếc', '2026-01-18 10:24:51', '2026-01-18 10:24:51', '2026-01-18 10:24:51'),
(12, 5, 81, '500', 'kg', '2026-01-18 10:25:02', '2026-01-18 10:25:02', '2026-01-18 10:25:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_preferences`
--

CREATE TABLE `user_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `favorite_categories` json DEFAULT NULL,
  `origins` json DEFAULT NULL,
  `diet_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diet_types` json DEFAULT NULL,
  `spicy_level` int(11) NOT NULL DEFAULT '0' COMMENT '0=Không cay, 1=Cay nhẹ, 2=Cay vừa, 3=Cay nhiều',
  `disliked_ingredients` json DEFAULT NULL,
  `allergies` json DEFAULT NULL,
  `health_goal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_preferences`
--

INSERT INTO `user_preferences` (`id`, `user_id`, `favorite_categories`, `origins`, `diet_type`, `diet_types`, `spicy_level`, `disliked_ingredients`, `allergies`, `health_goal`, `created_at`, `updated_at`) VALUES
(1, 4, '[\"mon_truyen_thong\"]', NULL, 'binh_thuong', NULL, 0, '[]', NULL, NULL, '2026-01-18 08:45:55', '2026-01-18 08:46:22');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dishes_category_id_foreign` (`category_id`),
  ADD KEY `dishes_status_category_id_index` (`status`,`category_id`),
  ADD KEY `dishes_slug_index` (`slug`);

--
-- Chỉ mục cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dish_ingredients_dish_id_ingredient_id_unique` (`dish_id`,`ingredient_id`),
  ADD KEY `dish_ingredients_ingredient_id_foreign` (`ingredient_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `favorite_dishes`
--
ALTER TABLE `favorite_dishes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorite_dishes_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `favorite_dishes_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingredients_slug_index` (`slug`);

--
-- Chỉ mục cho bảng `ingredient_nutrition`
--
ALTER TABLE `ingredient_nutrition`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ingredient_nutrition_ingredient_id_unique` (`ingredient_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `reviews_dish_id_foreign` (`dish_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `scan_history`
--
ALTER TABLE `scan_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scan_history_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- Chỉ mục cho bảng `user_food_histories`
--
ALTER TABLE `user_food_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_food_histories_user_id_action_action_at_index` (`user_id`,`action`,`action_at`),
  ADD KEY `user_food_histories_dish_id_index` (`dish_id`);

--
-- Chỉ mục cho bảng `user_ingredients`
--
ALTER TABLE `user_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_ingredients_user_id_ingredient_id_unique` (`user_id`,`ingredient_id`),
  ADD KEY `user_ingredients_ingredient_id_foreign` (`ingredient_id`);

--
-- Chỉ mục cho bảng `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_preferences_user_id_unique` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `favorite_dishes`
--
ALTER TABLE `favorite_dishes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `ingredient_nutrition`
--
ALTER TABLE `ingredient_nutrition`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `scan_history`
--
ALTER TABLE `scan_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user_food_histories`
--
ALTER TABLE `user_food_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `user_ingredients`
--
ALTER TABLE `user_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `dishes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD CONSTRAINT `dish_ingredients_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dish_ingredients_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `favorite_dishes`
--
ALTER TABLE `favorite_dishes`
  ADD CONSTRAINT `favorite_dishes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_dishes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `ingredient_nutrition`
--
ALTER TABLE `ingredient_nutrition`
  ADD CONSTRAINT `ingredient_nutrition_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `scan_history`
--
ALTER TABLE `scan_history`
  ADD CONSTRAINT `scan_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_food_histories`
--
ALTER TABLE `user_food_histories`
  ADD CONSTRAINT `user_food_histories_dish_id_foreign` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_food_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_ingredients`
--
ALTER TABLE `user_ingredients`
  ADD CONSTRAINT `user_ingredients_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_ingredients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

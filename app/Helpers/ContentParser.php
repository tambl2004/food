<?php

namespace App\Helpers;

class ContentParser
{
    /**
     * Parse nội dung tin tức để hiển thị đẹp hơn
     * Xử lý các định dạng như bold, danh sách, tiêu đề
     */
    public static function parseNewsContent($content)
    {
        // Chuyển đổi xuống dòng thành HTML
        $content = nl2br($content);
        
        // Xử lý bold text (**text**)
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong class="content-bold">$1</strong>', $content);
        
        // Xử lý italic text (*text*)
        $content = preg_replace('/\*(.*?)\*/', '<em class="content-italic">$1</em>', $content);
        
        // Xử lý tiêu đề chính (## Title)
        $content = preg_replace('/^##\s+(.+?)$/m', '<h3 class="content-subtitle">$1</h3>', $content);
        
        // Xử lý tiêu đề phụ (### Title)
        $content = preg_replace('/^###\s+(.+?)$/m', '<h4 class="content-subtitle-small">$1</h4>', $content);
        
        // Xử lý danh sách có số (1. item)
        $content = preg_replace('/(\d+)\.\s+(.+?)(?=\n|$)/m', '<div class="numbered-list-item"><span class="list-number">$1.</span><span class="list-content">$2</span></div>', $content);
        
        // Xử lý danh sách có dấu gạch đầu dòng (- item)
        $content = preg_replace('/^-\s+(.+?)$/m', '<div class="bullet-list-item"><span class="list-bullet">•</span><span class="list-content">$1</span></div>', $content);
        
        // Xử lý phần giá cả và khuyến mãi đặc biệt
        $content = preg_replace('/\*\*Giá cả và khuyến mãi:\*\*/', '<div class="price-section"><h4>Giá cả và khuyến mãi</h4>', $content);
        
        // Xử lý các dòng giá cả (- Laptop gaming cao cấp: từ 25-50 triệu đồng)
        $content = preg_replace('/^-\s+(.+?):\s+(.+?)$/m', '<div class="price-item"><span class="price-label">$1:</span><span class="price-value">$2</span></div>', $content);
        
        // Đóng phần giá cả
        $content = preg_replace('/(<div class="price-item">.*<\/div>)\s*$/m', '$1</div>', $content);
        
        // Xử lý call to action
        $content = preg_replace('/Hãy đến cửa hàng để trải nghiệm trực tiếp những chiếc laptop gaming mới nhất!/', '<div class="cta-section"><h4>Trải nghiệm ngay</h4><p>Hãy đến cửa hàng để trải nghiệm trực tiếp những chiếc laptop gaming mới nhất!</p><a href="' . route('home') . '" class="btn">Khám phá ngay</a></div>', $content);
        
        // Xử lý đoạn văn (tách theo \n\n)
        $content = preg_replace('/\n\n/', '</p><p class="content-paragraph">', $content);
        
        // Wrap toàn bộ nội dung trong paragraph
        $content = '<p class="content-paragraph">' . $content . '</p>';
        
        // Clean up empty paragraphs
        $content = preg_replace('/<p class="content-paragraph"><\/p>/', '', $content);
        
        // Clean up các thẻ paragraph không cần thiết
        $content = preg_replace('/<p class="content-paragraph">(<h[3-4]|<div)/', '$1', $content);
        $content = preg_replace('/(<\/h[3-4]>|<\/div>)<\/p>/', '$1', $content);
        
        return $content;
    }
    
    /**
     * Parse nội dung đơn giản hơn cho các trường hợp cơ bản
     */
    public static function parseSimpleContent($content)
    {
        // Chuyển đổi xuống dòng thành HTML
        $content = nl2br($content);
        
        // Xử lý bold text (**text**)
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong class="content-bold">$1</strong>', $content);
        
        // Xử lý italic text (*text*)
        $content = preg_replace('/\*(.*?)\*/', '<em class="content-italic">$1</em>', $content);
        
        return $content;
    }
}

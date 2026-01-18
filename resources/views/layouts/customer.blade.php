<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gợi ý Món Ăn AI')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Modular CSS files for better organization --}}
    <link rel="stylesheet" href="{{ asset('css/base-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart-checkout-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/news-styles.css') }}">
    @yield('styles')
</head>
<body>
    {{-- Cấu trúc flex để đẩy footer xuống cuối trang --}}
    <div class="d-flex flex-column min-vh-100">
        @include('partials.navbar')

        <main class="flex-grow-1">
            @yield('content')
        </main>

        {{-- Footer mới --}}
        <footer class="footer mt-auto py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-backpack me-2 text-warning"></i>TastifyAI
                        </h5>
                        <p class="text-white-50 mb-3">Hệ thống gợi ý món ăn thông minh sử dụng AI, giúp bạn khám phá công thức nấu ăn phù hợp với nguyên liệu có sẵn và thói quen ăn uống.</p>
                        <div class="social-links">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-youtube fa-lg"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="fw-bold mb-3">Liên kết</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                            <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                            <li><a href="{{ route('contact.index') }}" class="text-white-50 text-decoration-none">Tính năng</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Cách hoạt động</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h6 class="fw-bold mb-3">Hỗ trợ khách hàng</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white-50 text-decoration-none">Hướng dẫn</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Chính sách</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Bảo hành</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Câu hỏi thường gặp</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <h6 class="fw-bold mb-3">Liên hệ</h6>
                        <div class="contact-info">
                            <p class="text-white-50 mb-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Trường Đại học tài nguyên và môi trường Hà Nội
                            </p>
                            <p class="text-white-50 mb-2">
                                <i class="fas fa-phone me-2"></i>
                                0123 456 789
                            </p>
                            <p class="text-white-50 mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                info@gmail.com
                            </p>
                        </div>
                    </div>
                </div>
               
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
    
    <!-- Customer Toast Container -->
    <div class="customer-toast-container" id="customerToastContainer"></div>

    <!-- Global hidden logout form (used by header dropdown to reliably POST logout) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    
    <script>
        // Khởi tạo customer toast system
        document.addEventListener('DOMContentLoaded', function () {
            initCustomerToastSystem();
        });
        
        // Customer Toast Notification System
        function initCustomerToastSystem() {
            // Tạo container nếu chưa có
            if (!document.getElementById('customerToastContainer')) {
                const container = document.createElement('div');
                container.className = 'customer-toast-container';
                container.id = 'customerToastContainer';
                document.body.appendChild(container);
            }
        }
        
        // Hiển thị customer toast notification
        function showCustomerToast(type, title, message, duration = 4000) {
            const container = document.getElementById('customerToastContainer');
            const toastId = 'customer-toast-' + Date.now();
            
            const toastHtml = `
                <div class="customer-toast customer-toast-${type}" id="${toastId}" data-duration="${duration}">
                    <div class="customer-toast-icon" style="background: ${getCustomerToastConfig(type).bg}">
                        <i class="${getCustomerToastConfig(type).icon}"></i>
                    </div>
                    <div class="customer-toast-content">
                        ${title ? `<div class="customer-toast-title">${title}</div>` : ''}
                        ${message ? `<div class="customer-toast-message">${message}</div>` : ''}
                    </div>
                    <button type="button" class="customer-toast-close" onclick="hideCustomerToast('${toastId}')">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="customer-toast-progress" style="background: ${getCustomerToastConfig(type).bg}; animation-duration: ${duration}ms;"></div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            
            // Hiển thị toast với animation
            setTimeout(() => {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.style.display = 'flex';
                    setTimeout(() => toast.classList.add('show'), 10);
                }
            }, 10);
            
            // Tự động ẩn toast
            if (duration > 0) {
                setTimeout(() => hideCustomerToast(toastId), duration);
            }
        }
        
        // Ẩn customer toast notification
        function hideCustomerToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.add('hide');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 400);
            }
        }
        
        // Cấu hình customer toast
        function getCustomerToastConfig(type) {
            const configs = {
                'success': {
                    bg: 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
                    icon: 'fas fa-check-circle'
                },
                'error': {
                    bg: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    icon: 'fas fa-times-circle'
                },
                'danger': {
                    bg: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                    icon: 'fas fa-exclamation-triangle'
                },
                'warning': {
                    bg: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                    icon: 'fas fa-exclamation-triangle'
                },
                'info': {
                    bg: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
                    icon: 'fas fa-info-circle'
                }
            };
            return configs[type] || configs['info'];
        }
        
        // Xử lý session messages từ Laravel cho customer
        @if(session('success'))
            showCustomerToast('success', 'Thành công', '{{ session('success') }}');
        @endif
        
        @if(session('error'))
            showCustomerToast('error', 'Lỗi', '{{ session('error') }}');
        @endif
        
        @if(session('warning'))
            showCustomerToast('warning', 'Cảnh báo', '{{ session('warning') }}');
        @endif
        
        @if(session('info'))
            showCustomerToast('info', 'Thông tin', '{{ session('info') }}');
        @endif
    </script>
</body>
</html>
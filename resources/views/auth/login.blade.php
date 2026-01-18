<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - TastifyAI</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        /* --- 1. BACKGROUND SLIDESHOW --- */
        .bg-slideshow {
            position: fixed;
            inset: 0;
            z-index: -2;
            overflow: hidden;
            }

            .bg-slide {
                position: absolute;
                inset: 0;

                background-size: cover;
                background-position: center;

                opacity: 0;
                transform: scale(1.08); /* LUÔN có scale */

                transition:
                    opacity 1.5s ease-in-out,
                    transform 12s ease-in-out; /* zoom rất chậm */

                will-change: opacity, transform;
            }

            .bg-slide.active {
                opacity: 1;
                transform: scale(1.12); /* zoom nhẹ thêm */
            }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.2) 100%);
            z-index: -1;
        }

        /* --- 2. LEFT SIDE CONTENT --- */
        .left-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
            padding-left: 60px;
            padding-right: 20px;
            position: relative; /* Để đặt indicators */
        }

        .brand-logo {
            position: absolute;
            top: 30px;
            left: 60px;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .brand-icon {
            background: #ff6b6b;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-heading {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .sub-heading {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 1rem;
            opacity: 0.9;
        }

        .feature-item i {
            margin-right: 15px;
            color: #ff6b6b;
            width: 20px;
            text-align: center;
        }

        /* --- MỚI: CSS CHO THANH CHỈ DẪN (INDICATORS) --- */
        .indicator-container {
            display: flex;
            gap: 8px;
            margin-top: 50px;
        }

        .indicator-bar {
            width: 25px; /* Chiều dài mặc định khi không active */
            height: 4px;
            background: rgba(255,255,255,0.4); /* Màu mờ */
            border-radius: 2px;
            /* Hiệu ứng chuyển động mượt mà */
            transition: all 0.5s cubic-bezier(0.4, 0.0, 0.2, 1); 
            cursor: pointer;
        }

        /* Trạng thái khi active */
        .indicator-bar.active {
            width: 60px; /* Dài ra */
            background: white; /* Sáng lên */
        }


        /* --- 3. RIGHT SIDE (LOGIN FORM) --- */
        .right-content {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .btn-primary {
            background-color: #e85d46;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #d14d38;
        }
        
        .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #fb923c; /* màu cam brand */
        }

        /* Input group (có icon) */
        .input-group:focus-within {
            border-radius: 10px;
            box-shadow: 0 0 0 2px rgba(251,146,60,0.35);
            transition: box-shadow 0.25s ease;
        }

        /* Input nền mượt hơn */
        .form-control {
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }
        
        .social-btn {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 992px) {
            .left-content { display: none; }
            .overlay { background: rgba(0,0,0,0.6); }
        }
    </style>
</head>
<body>

    <div class="bg-slideshow">
        <div class="bg-slide active" style="background-image: url('https://images.unsplash.com/photo-1543353071-873f17a7a088?q=80&w=2070');"></div>
        <div class="bg-slide" style="background-image: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=2070');"></div>
        <div class="bg-slide" style="background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=1974');"></div>
    </div>
    
    <div class="overlay"></div>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-lg-7 d-none d-lg-block">
                <div class="left-content">
                    <div class="brand-logo">
                        <div class="brand-icon"><i class="fa-solid fa-bowl-food text-white"></i></div>
                        TastifyAI
                    </div>

                    <h1 class="main-heading">Nấu ăn thông minh<br>cho mọi nhà</h1>
                    <p class="sub-heading">AI gợi ý món ăn phù hợp với nguyên liệu bạn có sẵn.</p>
                    
                    <ul class="feature-list">
                        <li class="feature-item"><i class="fas fa-magic"></i> Gợi ý món ăn thông minh bằng AI</li>
                        <li class="feature-item"><i class="fas fa-leaf"></i> Tối ưu nguyên liệu có sẵn</li>
                        <li class="feature-item"><i class="fas fa-book-open"></i> Hàng nghìn công thức đa dạng</li>
                    </ul>

                    <div class="indicator-container">
                        <div class="indicator-bar active" onclick="goToSlide(0)"></div>
                        <div class="indicator-bar" onclick="goToSlide(1)"></div>
                        <div class="indicator-bar" onclick="goToSlide(2)"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-12">
                <div class="right-content">
                    <div class="auth-card">
                        <div class="d-flex justify-content-between mb-4 bg-light p-1 rounded-3">
                            <button class="btn btn-white w-50 shadow-sm fw-bold">Đăng nhập</button>
                            <a href="{{ route('register') }}" class="btn w-50 text-muted">Đăng ký</a>
                        </div>

                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Chào mừng trở lại!</h4>
                            <p class="text-muted small">Đăng nhập để tiếp tục khám phá món ăn</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                             @if ($errors->any())
                                <div class="alert alert-danger py-2 small">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label small text-muted">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="far fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="example@email.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0 bg-light" placeholder="••••••••" required>
                                    <span class="input-group-text bg-light border-start-0"><i class="far fa-eye"></i></span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4 small">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label text-muted" for="remember">Ghi nhớ đăng nhập</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-danger text-decoration-none">Quên mật khẩu?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary w-100 text-white mb-3">
                                Đăng nhập <i class="fas fa-arrow-right ms-2 small"></i>
                            </button>

                            <div class="text-center mb-3">
                                <span class="text-muted small bg-white px-2">Hoặc tiếp tục với</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="#" class="social-btn">
                                        <i class="fab fa-google text-danger"></i> Google
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="social-btn">
                                        <i class="fab fa-facebook text-primary"></i> Facebook
                                    </a>
                                </div>
                            </div>

                            <div class="text-center mt-4 small">
                                Chưa có tài khoản? <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const slides = document.querySelectorAll('.bg-slide');
            const indicators = document.querySelectorAll('.indicator-bar'); // Lấy thêm các thanh chỉ dẫn
            let currentSlide = 0;
            const slideInterval = 5000; // 5 giây đổi 1 lần
            let slideTimer;

            // Hàm chuyển đến slide cụ thể
            window.goToSlide = function(index) {
                // Reset timer để tránh xung đột nếu người dùng click thủ công
                clearInterval(slideTimer);
                
                // Xóa active ở slide và indicator cũ
                slides[currentSlide].classList.remove('active');
                indicators[currentSlide].classList.remove('active');
                
                // Cập nhật index mới
                currentSlide = index;
                
                // Thêm active vào slide và indicator mới
                slides[currentSlide].classList.add('active');
                indicators[currentSlide].classList.add('active');

                // Khởi động lại timer
                slideTimer = setInterval(nextSlide, slideInterval);
            }

            // Hàm tự động chuyển slide tiếp theo
            function nextSlide() {
                let nextIndex = (currentSlide + 1) % slides.length;
                goToSlide(nextIndex);
            }

            // Bắt đầu chạy tự động
            slideTimer = setInterval(nextSlide, slideInterval);
        });
    </script>
</body>
</html>
@extends('layouts.customer')

@section('title', 'Gợi ý Món Ăn AI - Trang chủ')

@section('styles')
<style>
    /* Food theme: marquee for partners, rounded card logos */
    .brand-marquee { background:#fff8f2; padding:20px 0; overflow:hidden; border-top:1px solid #fff1e6; border-bottom:1px solid #fff1e6; }
    .brand-inner { display:flex; min-width:200%; }
    .brand-track { display:flex; align-items:center; gap:36px; flex:1 0 50%; animation:brand-scroll 36s linear infinite; will-change:transform; }
    .brand-logo { display:flex; align-items:center; justify-content:center; width:160px; height:100px; border-radius:14px; background:#ffffff; box-shadow:0 8px 20px rgba(2,6,23,0.06); }
    .brand-logo img { max-height:46px; width:auto; object-fit:contain; }
    @keyframes brand-scroll { 0%{ transform:translateX(0); } 100%{ transform:translateX(-50%); } }
    @media (max-width:576px){ .brand-track{ gap:20px; } .brand-logo{ width:120px; height:80px; } .brand-logo img{ max-height:36px; } }
</style>
@endsection

@section('content')

<section class="hero-section">
  <div class="container">
    <div class="row align-items-center">

      <!-- LEFT -->
      <div class="col-lg-6">
        <div class="hero-badge">✨ Được hỗ trợ bởi AI thông minh</div>

        <h1 class="hero-title mt-4">
          Gợi ý món ăn <span class="highlight">thông minh</span><br>
          dành riêng cho bạn
        </h1>

        <p class="hero-desc">
          Khám phá hàng ngàn công thức nấu ăn được AI đề xuất
          dựa trên <span class="text-success">nguyên liệu có sẵn</span>
          và <span class="text-warning">thói quen ăn uống</span>.
        </p>

        <div class="hero-actions">
          <a href="{{ route('register') }}" class="btn-hero-primary">Bắt đầu miễn phí</a>
          <a href="{{ route('login') }}" class="btn-hero-outline">Đăng nhập</a>
        </div>

        <!-- ✅ STATS PHẢI Ở ĐÂY -->
        <div class="hero-stats">
          <div class="hero-stat-item stat-orange">
            <div class="stat-icon">🧠</div>
            <div class="stat-text">
              <strong>10,000+</strong>
              <span>Công thức AI</span>
            </div>
          </div>

          <div class="hero-stat-item stat-green">
            <div class="stat-icon">🌿</div>
            <div class="stat-text">
              <strong>500+</strong>
              <span>Nguyên liệu</span>
            </div>
          </div>

          <div class="hero-stat-item stat-yellow">
            <div class="stat-icon">⏱</div>
            <div class="stat-text">
              <strong>5 phút</strong>
              <span>Gợi ý tức thì</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT (BẮT BUỘC PHẢI CÓ) -->
      <div class="col-lg-6">
        <div class="hero-visual">
          <div class="hero-orbit"></div>

          <div class="hero-image-wrap">
            <img src="/images/hero-food.png" alt="">
          </div>

          <div class="hero-float-badge ai">
            <div class="badge-icon ai">
                ✨
            </div>
            <div class="badge-text">
                <strong>AI đề xuất</strong>
                <span>Phở bò Việt Nam</span>
            </div>
            </div>

          <div class="hero-float-badge ingredient">
            <div class="badge-icon ingredient">
                🌿
            </div>
            <div class="badge-text">
                <strong>Nguyên liệu có sẵn</strong>
                <span>8 món phù hợp</span>
            </div>
            </div>

        </div>
      </div>

    </div>
  </div>
</section>
<!-- Features Section -->
<section id="features" class="features-section py-5">
    <div class="container">

        <div class="text-center mb-5">
            <span class="section-badge">Tính năng nổi bật</span>
            <h2 class="section-title">
                Công nghệ AI phục vụ <span class="highlight">đam mê ẩm thực</span>
            </h2>
            <p class="section-desc">
                Trải nghiệm cách mạng trong việc lên kế hoạch bữa ăn với sự hỗ trợ của trí tuệ nhân tạo
            </p>
        </div>

        <div class="row g-4">
            <!-- 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-orange">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <h5>AI Thông Minh</h5>
                    <p>
                        Thuật toán học máy phân tích sở thích ẩm thực
                        và đề xuất món ăn phù hợp nhất với bạn.
                    </p>
                </div>
            </div>

            <!-- 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h5>Nguyên Liệu Có Sẵn</h5>
                    <p>
                        Nhập các nguyên liệu trong tủ lạnh, AI sẽ gợi ý
                        các món có thể nấu ngay.
                    </p>
                </div>
            </div>

            <!-- 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-red">
                        <i class="fa-solid fa-heart"></i>
                    </div>
                    <h5>Sức Khỏe Cá Nhân</h5>
                    <p>
                        Tùy chỉnh chế độ ăn theo nhu cầu:
                        ít đường, thuần chay, keto, low-carb...
                    </p>
                </div>
            </div>

            <!-- 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-yellow">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h5>Học Theo Thói Quen</h5>
                    <p>
                        Hệ thống ngày càng hiểu bạn hơn qua mỗi lần
                        sử dụng và phản hồi.
                    </p>
                </div>
            </div>

            <!-- 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-green">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <h5>Công Thức Chi Tiết</h5>
                    <p>
                        Hướng dẫn nấu ăn từng bước với hình ảnh,
                        video và mẹo từ đầu bếp chuyên nghiệp.
                    </p>
                </div>
            </div>

            <!-- 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon icon-orange">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h5>Gợi Ý Tức Thì</h5>
                    <p>
                        Nhận đề xuất món ăn trong vài giây,
                        tiết kiệm thời gian lên thực đơn hàng ngày.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>

<section id="how-it-works" class="how-section">
    <div class="container">
        <!-- Header -->
        <div class="how-header text-center">
            <span class="how-badge">Cách hoạt động</span>
            <h2 class="how-title">
                4 bước đơn giản để có <span>bữa ăn hoàn hảo</span>
            </h2>
            <p class="how-desc">
                Quy trình đơn giản, trải nghiệm tuyệt vời – từ tủ lạnh đến bàn ăn chỉ trong vài phút
            </p>
        </div>
        <!-- Steps -->
        <div class="how-steps">
            <!-- Step 1 -->
            <div class="how-step">
                <div class="step-icon orange">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span class="step-number">01</span>
                </div>
                <h5>Nhập nguyên liệu</h5>
                <p>
                    Cho chúng tôi biết bạn có những nguyên liệu gì trong tủ lạnh
                    hoặc những gì bạn muốn ăn hôm nay.
                </p>
            </div>
            <!-- Step 2 -->
            <div class="how-step">
                <div class="step-icon orange">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span class="step-number">02</span>
                </div>
                <h5>AI phân tích</h5>
                <p>
                    Trí tuệ nhân tạo phân tích nguyên liệu, sở thích
                    và lịch sử ăn uống của bạn.
                </p>
            </div>
            <!-- Step 3 -->
            <div class="how-step">
                <div class="step-icon orange">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span class="step-number">03</span>
                </div>
                <h5>Nhận gợi ý</h5>
                <p>
                    Khám phá danh sách món ăn được cá nhân hóa
                    với công thức chi tiết từng bước.
                </p>
            </div>
            <!-- Step 4 -->
            <div class="how-step">
                <div class="step-icon orange">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <span class="step-number">04</span>
                </div>
                <h5>Đánh giá & học</h5>
                <p>
                    Đánh giá món ăn để AI hiểu bạn hơn
                    và đưa ra gợi ý ngày càng chính xác.
                </p>
            </div>
        </div>
        <!-- Line -->
        <div class="how-line"></div>

    </div>
</section>

<!-- Featured Recipes Section -->
<section id="dish" class="featured-section py-5">
    <div class="container text-center">

        <!-- Badge -->
        <div class="ai-badge">Món ăn nổi bật</div>

        <!-- Heading -->
        <h1 class="featured-title">
            Khám phá công thức <span>được yêu thích</span>
        </h1>

        <!-- Sub -->
        <p class="featured-subtitle">
            Đăng ký để mở khóa hàng ngàn công thức nấu ăn được AI cá nhân hóa
        </p>

        <!-- Cards -->
        <div class="row justify-content-center mt-5">

            @foreach($featuredProducts->take(3) as $product)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="recipe-card">

                    <!-- Image + Lock -->
                    <div class="recipe-image lockable">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">

                        <!-- Tags -->
                        <div class="recipe-tags">
                            @if($product->origin)
                                <span>{{ $product->origin }}</span>
                            @endif
                            @if($product->category)
                                <span>{{ $product->category->name }}</span>
                            @endif
                        </div>

                        <!-- LOCK OVERLAY -->
                        @if(!auth()->check())
                        <div class="lock-overlay" onclick="window.location='{{ route('register') }}'">
                            <div class="lock-content">
                                <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                <p>Đăng ký để xem công thức</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="recipe-content">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ Str::limit($product->description, 90) }}</p>

                        <!-- Meta -->
                        <div class="recipe-meta">
                            <span>⏱ {{ ($product->prep_time + $product->cook_time) ?? 0 }} phút</span>
                            <span>🧑‍🤝‍🧑 {{ $product->servings ?? 1 }} người</span>
                            <span>🔥 {{ $product->calories ?? '---' }} kcal</span>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach

        </div>

        <!-- CTA -->
        <a href="{{ route('register') }}" class="featured-btn">
            Mở khóa tất cả công thức →
        </a>
    </div>
</section>

<!-- Brand Logos: 1 hàng, chuyển động vô hạn từ phải qua trái -->
<section class="brand-marquee" aria-label="Thương hiệu đối tác">
    <div class="container-fluid px-0">
        <div class="brand-inner">
            <div class="brand-track">
                <div class="brand-logo"><img src="https://images.prismic.io/ddhomepage/NWMwOTZmNWEtYzZhNC00ZDI5LWExZmYtMzRkOTY2OWQ4NDE5_78c993ab-2085-44a9-aafa-a2e43230fc1f_logo_allrecipes.png?auto=compress,format&rect=0,119,500,261&w=1200&h=627" alt="AllRecipes" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://yt3.googleusercontent.com/KNE8DkDo0taxptChljpuIeuDjCK0UmnIHq_pV3MRbQoOU8zgOd_HdJ0yJZntix0nJCCjyz9Iow=s900-c-k-c0x00ffffff-no-rj" alt="Tasty" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTeT-nYE3PsxmR7jqTPOMz-yR6ty4cTAnHKkw&s" alt="Food Network" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8jCi8AkIiZdrvkXqU25KxRCl7QOsXlGZdWA&s" alt="Epicurious" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTm7QKupYfNZkrQOS2xg2L8WDiPgqvjxlnORQ&s" alt="Yummly" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRL4qcKTgN-o8V4klYdfZEGSrPS19AxFyg1VA&s" alt="Delish" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ6MPLzccIRe-n_C_160J74c0743h8h8A1G3A&s" alt="HelloFresh" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAg0BJJHjzE8OUAy1cJGJT2BCClWOOwY2sUg&s" alt="Blue Apron" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://images.prismic.io/ddhomepage/NWMwOTZmNWEtYzZhNC00ZDI5LWExZmYtMzRkOTY2OWQ4NDE5_78c993ab-2085-44a9-aafa-a2e43230fc1f_logo_allrecipes.png?auto=compress,format&rect=0,119,500,261&w=1200&h=627" alt="AllRecipes" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://yt3.googleusercontent.com/KNE8DkDo0taxptChljpuIeuDjCK0UmnIHq_pV3MRbQoOU8zgOd_HdJ0yJZntix0nJCCjyz9Iow=s900-c-k-c0x00ffffff-no-rj" alt="Tasty" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTeT-nYE3PsxmR7jqTPOMz-yR6ty4cTAnHKkw&s" alt="Food Network" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT8jCi8AkIiZdrvkXqU25KxRCl7QOsXlGZdWA&s" alt="Epicurious" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTm7QKupYfNZkrQOS2xg2L8WDiPgqvjxlnORQ&s" alt="Yummly" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRL4qcKTgN-o8V4klYdfZEGSrPS19AxFyg1VA&s" alt="Delish" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ6MPLzccIRe-n_C_160J74c0743h8h8A1G3A&s" alt="HelloFresh" loading="lazy">
            </div>
                <div class="brand-logo"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRAg0BJJHjzE8OUAy1cJGJT2BCClWOOwY2sUg&s" alt="Blue Apron" loading="lazy">
            </div>
        </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Hero Carousel functionality (if needed)
    // Add to Cart functionality (if needed)
</script>
@endsection

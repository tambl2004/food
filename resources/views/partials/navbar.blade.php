<nav class="navbar navbar-expand-lg w-100 nav-guest" style="z-index:40;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="brand-mark me-2"><i class="fa-solid fa-bowl-food text-white"></i></div>
            <span class="ms-1 fw-bold brand-text">TastifyAI</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#guestNav" aria-controls="guestNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="guestNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 nav-center-links">
                <li class="nav-item px-3"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item px-3"><a class="nav-link" href="{{ route('home') }}#features">Tính năng</a></li>
                <li class="nav-item px-3"><a class="nav-link" href="{{ route('home') }}#how-it-works">Cách hoạt động</a></li>
                <li class="nav-item px-3"><a class="nav-link" href="{{ route('home') }}#dish">Món ăn</a></li>
                @auth
                    <li class="nav-item px-3">
                        <a class="nav-link text-primary fw-semibold" href="{{ route('recommendations.index') }}">
                            <i class="fas fa-robot me-1"></i>Gợi ý món ăn
                        </a>
                    </li>
                @endauth
            </ul>

            <div class="d-flex align-items-center ms-auto header-cta">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-link text-white small me-2">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-cta btn-sm">Đăng ký miễn phí</a>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="userNav" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar me-2"><i class="fas fa-user-circle fa-lg"></i></div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="userNav">
                            <li><a class="dropdown-item" href="{{ route('recommendations.index') }}"><i class="fas fa-robot me-2 text-primary"></i><strong>Gợi ý món ăn</strong></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="{{ route('preferences.show') }}"><i class="fas fa-heart me-2"></i>Sở thích ăn uống</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.ingredients.index') }}"><i class="fas fa-utensils me-2"></i>Nguyên liệu của tôi</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-warning" href="{{ route('admin.dashboard') }}"><i class="fas fa-cogs me-2"></i>Trang Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>


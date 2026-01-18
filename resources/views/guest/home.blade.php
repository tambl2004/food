@extends('layouts.guest')

@section('content')

{{-- HERO --}}
<section class="grid md:grid-cols-2 gap-12 px-10 py-20 items-center">
    <div>
        <span class="bg-orange-100 text-orange-600 px-4 py-1 rounded-full text-sm">
            ✨ Được hỗ trợ bởi AI thông minh
        </span>

        <h1 class="text-5xl font-bold mt-6 leading-tight">
            Gợi ý món ăn <span class="text-orange-500">thông minh</span><br>
            dành riêng cho bạn
        </h1>

        <p class="mt-6 text-gray-600 max-w-lg">
            Khám phá công thức nấu ăn được AI đề xuất dựa trên
            <span class="text-green-600">nguyên liệu có sẵn</span>
            và <span class="text-orange-500">thói quen ăn uống</span>.
        </p>

        <div class="flex gap-4 mt-8">
            <a href="{{ route('register') }}"
               class="bg-orange-500 text-white px-6 py-3 rounded-full">
                Bắt đầu miễn phí
            </a>
            <a href="{{ route('login') }}"
               class="border px-6 py-3 rounded-full">
                Đăng nhập
            </a>
        </div>
    </div>

    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c"
         class="rounded-3xl shadow-lg">
</section>

{{-- TÍNH NĂNG --}}
<section class="px-10 py-20 text-center">
    <h2 class="text-4xl font-bold mb-12">
        Công nghệ AI phục vụ <span class="text-orange-500">đam mê ẩm thực</span>
    </h2>

    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow">🍳 AI Thông minh</div>
        <div class="bg-white p-8 rounded-2xl shadow">🥬 Nguyên liệu có sẵn</div>
        <div class="bg-white p-8 rounded-2xl shadow">⚡ Gợi ý tức thì</div>
    </div>
</section>

@endsection

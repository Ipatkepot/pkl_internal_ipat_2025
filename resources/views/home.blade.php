@extends('layouts.app')

@section('title', 'Beranda - Tokopedia Style')

@section('content')

{{-- SWIPER CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<style>
    :root {
        /* Update ke Biru Steel */
        --tkp-primary: #3B6181;
        --tkp-primary-light: #e8eff5;
        --text-dark: #31353b;
        --text-muted: #6d7588;
        --bg-light: #f0f3f7;
    }

    body {
        background-color: #ffffff;
        color: var(--text-dark);
        font-family: 'Inter', sans-serif;
    }

    /* ===== HERO BANNER SECTION ===== */
    .hero-section {
        padding-top: 20px;
    }

    .heroSwiper {
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
        height: 350px; /* Sesuai request perbesar foto sebelumnya */
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        position: relative;
    }

    .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Navigation Buttons Style */
    .swiper-button-next, 
    .swiper-button-prev {
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        color: var(--text-dark) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
        opacity: 0;
    }

    .heroSwiper:hover .swiper-button-next,
    .heroSwiper:hover .swiper-button-prev {
        opacity: 1;
    }

    .swiper-button-next:hover, 
    .swiper-button-prev:hover {
        background-color: #ffffff;
        color: var(--tkp-primary) !important;
        transform: scale(1.1);
    }

    .swiper-pagination-bullet-active {
        background: var(--tkp-primary) !important;
    }

    /* ===== CATEGORY SECTION ===== */
    .category-wrapper {
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none !important;
        display: block;
    }

    .category-wrapper:hover .category-icon-box {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        border-color: var(--tkp-primary);
    }

    .category-icon-box {
        width: 85px; /* Sesuai request perbesar foto sebelumnya */
        height: 85px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        transition: all 0.3s ease;
    }

    .category-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 2px;
    }

    /* ===== SECTION TITLES ===== */
    .section-title {
        font-weight: 800;
        font-size: 1.3rem;
        letter-spacing: -0.3px;
    }

    .btn-see-all {
        color: var(--tkp-primary);
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
        transition: 0.2s;
    }

    .btn-see-all:hover {
        color: #2d4a63; /* Hover biru lebih gelap */
        text-decoration: underline;
    }

    /* ===== PROMO SECTION ===== */
    .promo-card {
        border-radius: 15px;
        border: none;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .promo-card:hover {
        transform: translateY(-5px);
    }

    .promo-card.bg-warning {
        background: linear-gradient(135deg, #FFB700 0%, #FF8000 100%) !important;
    }

    .promo-card.bg-info {
        /* Diubah ke gradasi Biru Steel */
        background: linear-gradient(135deg, #3B6181 0%, #5a8bb5 100%) !important;
    }

    /* ===== PRODUCT GRID ===== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    @media (min-width: 768px) {
        .product-grid { grid-template-columns: repeat(4, 1fr); }
    }

    @media (min-width: 1200px) {
        .product-grid { grid-template-columns: repeat(5, 1fr); } /* Diubah ke 5 agar foto lebih besar */
    }
</style>
<div class="container-fluid px-lg-5">
    
    {{-- HERO BANNER --}}
    <section class="hero-section mb-5">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="images/iklan.png" class="hero-img">
                </div>
                <div class="swiper-slide">
                   <a href="{{ route('catalog.index') }}"><img src="images/iklan2.png" class="hero-img"></a>
                </div>
                <div class="swiper-slide">
                    <img src="https://www.static-src.com/siva/asset/12_2025/NPI-iPad-Pro-M5-dw2000x500.jpg" class="hero-img">
                </div>
            </div>
            
            {{-- Navigation Arrows --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            {{-- Pagination Dots --}}
            <div class="swiper-pagination"></div>
        </div>
    </section>

    {{-- KATEGORI POPULER --}}
    <section class="mb-5">
        <div class="d-flex align-items-center mb-4">
            <h4 class="section-title mb-0">Kategori Pilihan</h4>
        </div>
        <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-4">
            @foreach($categories as $category)
                <div class="col">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="category-wrapper">
                        <div class="category-icon-box">
                            <img src="{{ $category->image_url }}" width="40" height="40" style="object-fit:contain;">
                        </div>
                        <div class="category-name text-truncate">{{ $category->name }}</div>
                        <small class="text-muted">{{ $category->products_count }} Produk</small>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- PRODUK UNGGULAN --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title mb-0">Produk Unggulan</h4>
            <a href="{{ route('catalog.index') }}" class="btn-see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        <div class="product-grid">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    {{-- PROMO SECTION --}}
    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="promo-card bg-warning p-4 h-100 shadow-sm text-white">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-2">🔥 Flash Sale Seru!</h3>
                            <p class="opacity-90">Jangan sampai kehabisan, diskon gila-gilaan hingga 70% hanya hari ini.</p>
                        </div>
                        <div class="mt-3">
                            <a href="#" class="btn btn-light fw-bold px-4" style="color: #FF8000; border-radius: 8px;">Cek Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="promo-card bg-info p-4 h-100 shadow-sm text-white">
                    <div class="d-flex flex-column h-100 justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-2">🎁 Voucher Pengguna Baru</h3>
                            <p class="opacity-90">Belanja perdana makin hemat! Gunakan kode: <b>BARUUNTUNG</b></p>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('register') }}" class="btn btn-light fw-bold text-info px-4" style="border-radius: 8px;">Ambil Voucher</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUK TERBARU --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="section-title mb-0">Produk Terbaru</h4>
            <a href="{{ route('catalog.index') }}" class="btn-see-all">Lihat Semua <i class="bi bi-chevron-right"></i></a>
        </div>

        <div class="product-grid">
            @foreach($latestProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

</div>

{{-- SWIPER JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper(".heroSwiper", {
            loop: true,
            autoplay: { 
                delay: 4500, 
                disableOnInteraction: false 
            },
            pagination: { 
                el: ".swiper-pagination", 
                clickable: true 
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    });
</script>
@endsection
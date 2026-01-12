@extends('layouts.app')

@section('title', 'GadgetMurah - Home')

@section('content')

{{-- External Resources --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    :root {
        --tkp-primary: #3B6181;
        --tkp-primary-dark: #2d4a63;
        --tkp-primary-light: #f0f5f9;
        --tkp-accent: #FF8000;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --bg-soft: #f8fafc;
        --transition-standard: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    body {
        background-color: #ffffff;
        color: var(--text-dark);
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow-x: hidden;
    }

    /* ===== HERO SECTION UPGRADE ===== */
    .hero-section { padding: 20px 0; }
    .heroSwiper {
        border-radius: 40px;
        height: 390px;
        box-shadow: 0 25px 50px -12px rgba(59, 97, 129, 0.15);
        overflow: hidden;
    }
    .hero-img { 
        width: 100%; height: 100%; object-fit: cover; 
        transition: transform 8s ease;
    }
    .swiper-slide-active .hero-img { transform: scale(1.08); }

    .swiper-button-next, .swiper-button-prev {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(12px);
        width: 45px; height: 45px;
        border-radius: 50%;
        color: white;
        transition: var(--transition-standard);
    }
    .swiper-button-next:hover, .swiper-button-prev:hover { background: var(--tkp-primary); }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px; font-weight: 800; }
    .swiper-pagination-bullet-active { background: var(--tkp-primary) !important; width: 25px !important; border-radius: 5px !important; }

    /* ===== CATEGORY UPGRADE ===== */
    .category-wrapper {
        text-align: center;
        text-decoration: none !important;
        display: block;
        transition: var(--transition-standard);
    }

    .category-icon-box {
        width: 110px; height: 110px;
        border-radius: 100px; /* Smooth Squircle */
        background: var(--bg-soft);
        margin: 0 auto 15px;
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition-standard);
        position: relative;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .category-icon-box img {
        width: 60%; height: 60%;
        object-fit: contain;
        transition: var(--transition-standard);
    }

    .category-wrapper:hover .category-icon-box {
        transform: translateY(-12px) rotate(5deg);
        background: var(--tkp-primary);
        box-shadow: 0 15px 30px rgba(59, 97, 129, 0.2);
    }

    .category-wrapper:hover .category-icon-box img {
        filter: brightness(0) invert(1);
        transform: scale(1.1);
    }

    .category-name {
        font-size: 0.95rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 2px; transition: color 0.3s;
    }
    .category-wrapper:hover .category-name { color: var(--tkp-primary); }

    /* ===== SECTION TITLES ===== */
    .title-tag {
        display: inline-block;
        color: var(--tkp-accent);
        font-weight: 800; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 2px;
        margin-bottom: 8px;
    }
    .section-title {
        font-weight: 800; font-size: 2.2rem;
        background: linear-gradient(135deg, #0f172a 0%, #3b6181 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    /* ===== PRODUCT GRID ===== */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    @media (min-width: 768px) { .product-grid { grid-template-columns: repeat(4, 1fr); gap: 30px; } }
    @media (min-width: 1200px) { .product-grid { grid-template-columns: repeat(5, 1fr); } }

    .hover-lift {
        transition: var(--transition-standard);
    }
    .hover-lift:hover {
        transform: translateY(-12px);
    }

    .btn-see-all {
        color: var(--tkp-primary);
        font-weight: 700; text-decoration: none;
        padding: 12px 24px; border-radius: 100px;
        background: white; border: 2px solid var(--tkp-primary-light);
        transition: var(--transition-standard);
        display: inline-flex; align-items: center;
    }
    .btn-see-all:hover {
        background: var(--tkp-primary); color: white;
        transform: translateX(5px);
        box-shadow: 0 10px 20px rgba(59, 97, 129, 0.1);
    }

    /* Special Background for Featured Section */
    .featured-container {
        background: var(--bg-soft);
        margin: 0 -5vw;
        padding: 80px 5vw;
        border-radius: 80px;
    }
</style>

<div class="container-fluid px-lg-5">
    
    {{-- 1. HERO BANNER --}}
    <section class="hero-section mb-5" data-aos="fade-in">
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
            <div class="swiper-button-next d-none d-md-flex"></div>
            <div class="swiper-button-prev d-none d-md-flex"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    {{-- 2. KATEGORI PILIHAN --}}
    <section class="mb-5 py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="title-tag">Eksplorasi Gadget</span>
            <h4 class="section-title">Kategori Pilihan</h4>
        </div>
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4 justify-content-center">
            @foreach($categories as $index => $category)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="category-wrapper">
                        <div class="category-icon-box">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                        </div>
                        <div class="category-name text-truncate">{{ $category->name }}</div>
                        <span class="text-muted small">{{ $category->products_count }} Item</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 3. PRODUK UNGGULAN --}}
    <section class="mb-5">
        <div class="featured-container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5" data-aos="fade-right">
                <div class="text-center text-md-start mb-4 mb-md-0">
                    <span class="title-tag">Most Wanted</span>
                    <h4 class="section-title mb-0">Produk Unggulan</h4>
                    <p class="text-muted mt-2 mb-0">Koleksi terbaik yang paling banyak dicari minggu ini.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="btn-see-all">
                    Lihat Semua Katalog <i class="bi bi-arrow-right-short fs-4 ms-2"></i>
                </a>
            </div>

            <div class="product-grid" data-aos="fade-up">
                @foreach($featuredProducts as $product)
                    <div class="hover-lift">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. PRODUK TERBARU --}}
    <section class="mb-5 py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5" data-aos="fade-up">
            <div class="text-center text-md-start mb-4 mb-md-0">
                <span class="title-tag">New Arrivals</span>
                <h4 class="section-title mb-0">Baru di Katalog</h4>
                <p class="text-muted mt-2 mb-0">Update stok produk terbaru setiap harinya.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn-see-all">
                Lihat Semua <i class="bi bi-arrow-right-short fs-4 ms-2"></i>
            </a>
        </div>

        <div class="product-grid" data-aos="fade-up">
            @foreach($latestProducts as $product)
                <div class="hover-lift">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </section>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS dengan setting yang lebih smooth
        AOS.init({
            once: true,
            duration: 1000,
            easing: 'ease-in-out-cubic',
            offset: 100
        });

        // Initialize Swiper
        new Swiper(".heroSwiper", {
            loop: true,
            parallax: true,
            speed: 1500,
            autoplay: { 
                delay: 6000, 
                disableOnInteraction: false 
            },
            pagination: { 
                el: ".swiper-pagination", 
                clickable: true, 
                dynamicBullets: true 
            },
            navigation: { 
                nextEl: ".swiper-button-next", 
                prevEl: ".swiper-button-prev" 
            },
        });
    });
</script>
@endsection
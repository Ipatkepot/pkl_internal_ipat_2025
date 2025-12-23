{{-- ================================================
     FILE: resources/views/home.blade.php
     FUNGSI: Halaman utama - Desain Minimalis, Lega & Nyaman 2025
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section - Sangat lega, fokus teks + ilustrasi kecil --}}
 <section class="hero-gradient text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">
                        Belanja Online Mudah & Terpercaya
                    </h1>
                    <p class="lead mb-4">
                        Temukan berbagai produk berkualitas dengan harga terbaik.
                        Gratis ongkir untuk pembelian pertama!
                    </p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
               <div class="col-lg-6 text-center" data-aos="fade-up">
        <img src="
             {{ asset('images/logo.png') }}"
             alt="Ilustrasi Belanja Online"
             class="img-fluid"
             style="max-height: 350px;">
        {{-- Ganti dengan ilustrasi favorit dari rekomendasi di atas --}}
    </div>
            </div>
        </div>
    </section>

    
    {{-- Kategori Populer - Lebih lega & sederhana --}}
    <section class="py-5 py-lg-9">
        <div class="container">
            <h2 class="text-center mb-3 fw-bold h3">Kategori Populer</h2>
            <div class="row g-5 g-lg-6 justify-content-center">
                @foreach($categories as $index => $category)
                    <div class="col-4 col-md-3 col-lg-2"  data-aos="fade-up">
                        <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                           class="text-decoration-none text-dark d-block text-center">
                            <div class="mb-4 rounded-circle overflow-hidden border mx-auto"
                                 style="width: 90px; height: 90px; border-width: 2px !important;">
                                <img src="{{ $category->image_url }}"
                                     alt="{{ $category->name }}"
                                     class="img-fluid w-100 h-100"
                                     style="object-fit: cover;">
                            </div>
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} produk</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Produk Unggulan - Ruang lebih banyak --}}
    <section class="py-1 py-lg-9 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 fw-bold mb-0">Produk Unggulan</h2>
                <a href="{{ route('catalog.index') }}" class="text-primary fw-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="row g-5">
                @foreach($featuredProducts as $index => $product)
                    <div class="col-6 col-md-4 col-lg-3"  data-aos="fade-up">
                        @include('profile.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promo Banner - Lebih sederhana & lega --}}
    <section class="py-5 py-lg-9">
        <div class="container">
            <div class="row g-5 g-lg-7">
                <div class="col-md-6"  data-aos="fade-up">
                    <div class="card border-0 bg-warning-subtle text-dark h-100 rounded-4 p-5 p-lg-6">
                        <h4 class="fw-bold mb-4">Flash Sale!</h4>
                        <p class="mb-5 lead">Diskon hingga 50% untuk produk terpilih</p>
                        <a href="#" class="btn btn-dark rounded-pill px-5 py-3">Lihat Promo</a>
                    </div>
                </div>
                <div class="col-md-6"  data-aos="fade-up">
                    <div class="card border-0 bg-info-subtle text-dark h-100 rounded-4 p-5 p-lg-6">
                        <h4 class="fw-bold mb-4">Member Baru</h4>
                        <p class="mb-5 lead">Dapatkan voucher Rp50.000 untuk pembelian pertama</p>
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-5 py-3">Daftar Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Terbaru - Ruang maksimal --}}
    <section class="py-5 py-lg-9">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold h3">Produk Terbaru</h2>
            <div class="row g-5">
                @foreach($latestProducts as $index => $product)
                    <div class="col-6 col-md-4 col-lg-3"  data-aos="fade-up">
                        @include('profile.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

{{-- CSS Minimal & Lega --}}
@section('styles')
<style>
    section {
        padding-top: 6rem !important;
        padding-bottom: 6rem !important;
    }
    @media (min-width: 992px) {
        section {
            padding-top: 8rem !important;
            padding-bottom: 8rem !important;
        }
    }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .bg-info-subtle { background-color: #f0f9ff !important; }
    .border { border-color: #f1f3f5 !important; }
    a:hover h6 { color: var(--bs-primary); transition: color 0.3s; }
</style>
@endsection

{{-- AOS Animasi Halus --}}
@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: true,
        duration: 800,
        easing: 'ease-out-quart'
    });
</script>
@endpush
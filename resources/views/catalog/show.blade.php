{{-- ================================================
     FILE: resources/views/catalog/show.blade.php
     FUNGSI: Halaman detail produk + Checkout Langsung (Buy Now)
     ================================================ --}}

@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    /* Tema Biru Steel */
    .breadcrumb-item a { color: #3B6181; text-decoration: none; }
    .breadcrumb-item.active { color: #6d7588; }
    .text-biru-steel { color: #3B6181 !important; }
    .btn-biru-steel {
        background-color: #3B6181; border-color: #3B6181; color: white; transition: 0.3s;
    }
    .btn-biru-steel:hover {
        background-color: #2d4a63; border-color: #2d4a63;
    }
    .btn-outline-biru-steel {
        border-color: #3B6181; color: #3B6181;
    }
    .btn-outline-biru-steel:hover {
        background-color: #3B6181; color: white;
    }

    /* Gambar Utama */
    .main-image-container {
        position: relative;
        height: 450px;
        background: #ffffff;
        border-radius: 15px 15px 0 0;
        overflow: hidden;
    }
    #main-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 20px;
        transition: opacity 0.4s ease;
    }

    /* Navigasi Gambar */
    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.85);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #3B6181;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 20;
        transition: all 0.3s;
    }
    .nav-arrow:hover {
        background: white;
        color: #2d4a63;
        transform: translateY(-50%) scale(1.1);
    }
    .nav-prev { left: 20px; }
    .nav-next { right: 20px; }

    /* Thumbnail */
    .thumbnail-scroll {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 12px 0;
        scrollbar-width: none;
    }
    .thumbnail-scroll::-webkit-scrollbar { display: none; }
    .thumbnail-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid transparent;
        opacity: 0.7;
        transition: all 0.3s;
        cursor: pointer;
        flex-shrink: 0;
    }
    .thumbnail-img:hover, .thumbnail-img.active {
        opacity: 1;
        border-color: #3B6181;
        transform: scale(1.08);
    }
</style>

<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}">
                    {{ $product->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Product Images --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="main-image-container">
                    <img src="{{ $product->image_url }}"
                         id="main-image"
                         alt="{{ $product->name }}">

                    @if($product->has_discount)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 fs-6 px-3 py-2 shadow-sm">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif

                    @if($product->images->count() > 1)
                        <button class="nav-arrow nav-prev" onclick="changeMainImage(-1)">‹</button>
                        <button class="nav-arrow nav-next" onclick="changeMainImage(1)">›</button>
                    @endif
                </div>

                @if($product->images->count() > 1)
                    <div class="card-body bg-light border-top">
                        <div class="thumbnail-scroll">
                            @foreach($product->images as $index => $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     class="thumbnail-img {{ $loop->first ? 'active' : '' }}"
                                     data-index="{{ $index }}"
                                     onclick="setMainImage({{ $index }})">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Product Info --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                       class="badge bg-light text-biru-steel text-decoration-none mb-2 px-3 py-2 border">
                        {{ $product->category->name }}
                    </a>

                    <h2 class="fw-bold mb-3" style="color: #31353b;">{{ $product->name }}</h2>

                    <div class="mb-4 p-3 bg-light rounded-3">
                        @if($product->has_discount)
                            <div class="text-muted text-decoration-line-through small">
                                {{ $product->formatted_original_price }}
                            </div>
                        @endif
                        <div class="h2 text-biru-steel fw-bold mb-0">
                            {{ $product->formatted_price }}
                        </div>
                    </div>

                    <div class="mb-4">
                        @if($product->stock > 10)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Stok Tersedia
                            </span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Stok Terbatas: {{ $product->stock }}
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                            </span>
                        @endif
                    </div>

                    {{-- Quantity + Action Buttons --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Jumlah</label>
                        <div class="input-group shadow-sm mb-3" style="width: 140px;">
                            <button type="button" class="btn btn-outline-secondary border-end-0" onclick="decrementQty()">-</button>
                            <input type="number" id="quantity-display" value="1" min="1" max="{{ $product->stock }}"
                                   class="form-control text-center fw-bold border-0" readonly>
                            <button type="button" class="btn btn-outline-secondary border-start-0" onclick="incrementQty()">+</button>
                        </div>

                        <div class="row g-3">
                            <!-- Tambah ke Keranjang -->
                            <div class="col-12 col-md-6">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="quantity-cart" value="1">
                                    <button type="submit" class="btn btn-outline-biru-steel w-100 fw-bold shadow-sm"
                                            @if($product->stock == 0) disabled @endif>
                                        <i class="bi bi-cart-plus me-2"></i> Tambah Keranjang
                                    </button>
                                </form>
                            </div>

                            <!-- Checkout Langsung (Buy Now) -->
                            <div class="col-12 col-md-6">
                                <form action="{{ route('checkout.direct') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="quantity-direct" value="1">
                                    <button type="submit" class="btn btn-biru-steel w-100 fw-bold shadow-sm"
                                            @if($product->stock == 0) disabled @endif>
                                        <i class="bi bi-credit-card-2-front me-2"></i> Checkout Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Wishlist --}}
                    @auth
                        <button type="button"
                                onclick="toggleWishlist({{ $product->id }})"
                                class="btn btn-outline-danger w-100 mb-4 wishlist-btn-{{ $product->id }} border-2">
                            <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                            {{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}
                        </button>
                    @endauth

                    @guest
                        <div class="alert alert-info mb-4 py-3 text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Login dulu</strong> untuk belanja atau wishlist!
                            <a href="{{ route('login') }}" class="btn btn-biru-steel btn-sm ms-3">Login Sekarang</a>
                        </div>
                    @endguest

                    <hr class="my-4" style="border-style: dashed;">

                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Deskripsi Produk</h6>
                        <p class="text-muted lh-base" style="font-size: 15px;">
                            {!! nl2br(e($product->description)) !!}
                        </p>
                    </div>

                    <div class="row g-2 text-muted small">
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <i class="bi bi-box me-2 text-biru-steel"></i> Berat: {{ $product->weight }} gr
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <i class="bi bi-tag me-2 text-biru-steel"></i> SKU: {{ $product->id + 1000 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const images = @json($product->images->pluck('image_path')->map(function($path) {
        return asset('storage/' . $path);
    })->toArray());

    let currentIndex = 0;

    function updateMainImage() {
        const mainImg = document.getElementById('main-image');
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = images[currentIndex];
            mainImg.style.opacity = '1';
        }, 300);

        document.querySelectorAll('.thumbnail-img').forEach((thumb, i) => {
            thumb.classList.toggle('active', i === currentIndex);
        });
    }

    function changeMainImage(direction) {
        if (images.length <= 1) return;
        currentIndex = (currentIndex + direction + images.length) % images.length;
        updateMainImage();
    }

    function setMainImage(index) {
        currentIndex = index;
        updateMainImage();
    }

    function updateQuantity(value) {
        document.getElementById('quantity-display').value = value;
        document.getElementById('quantity-cart').value = value;
        document.getElementById('quantity-direct').value = value;
    }

    function incrementQty() {
        const display = document.getElementById('quantity-display');
        const max = parseInt(display.max);
        if (parseInt(display.value) < max) {
            updateQuantity(parseInt(display.value) + 1);
        }
    }

    function decrementQty() {
        const display = document.getElementById('quantity-display');
        if (parseInt(display.value) > 1) {
            updateQuantity(parseInt(display.value) - 1);
        }
    }
</script>
@endpush
@endsection
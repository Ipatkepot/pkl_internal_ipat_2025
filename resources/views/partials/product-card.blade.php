{{-- ================================================
     FILE: resources/views/partials/product-card.blade.php
     STYLE: Modern Premium Marketplace (2026 Edition)
     ================================================ --}}

<style>
    :root {
        --primary-blue: #3B6181;
        --secondary-blue: #5a8fb9;
        --soft-bg: #f8fafc;
        --text-dark: #2d3436;
        --text-muted: #636e72;
        --discount-red: #ff4d4f;
    }

    .tkp-card {
        border: 1px solid #f0f0f0;
        border-radius: 16px; /* Lebih rounded agar terlihat modern */
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .tkp-card:hover {
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        transform: translateY(-6px);
        border-color: var(--primary-blue);
    }

    /* Image Interaction */
    .tkp-img-wrapper {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: var(--soft-bg);
    }

    .tkp-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .tkp-card:hover .tkp-img-wrapper img {
        transform: scale(1.08); /* Efek zoom halus */
    }

    /* Wishlist Glassmorphism */
    .tkp-wishlist {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .tkp-wishlist:hover {
        background: #fff;
        color: #ef144a;
        box-shadow: 0 4px 12px rgba(239, 20, 74, 0.2);
    }

    /* Content Styling */
    .tkp-content {
        padding: 12px 14px 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .tkp-title {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
        line-height: 1.4;
        transition: color 0.2s;
    }

    .tkp-card:hover .tkp-title {
        color: var(--primary-blue);
    }

    .tkp-price {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    /* Floating Badge Diskon */
    .tkp-discount-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 4px 0;
    }

    .tkp-badge-disc {
        background: linear-gradient(135deg, #ff7675, #ef144a);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 6px;
    }

    .tkp-old-price {
        font-size: 11px;
        color: var(--text-muted);
        text-decoration: line-through;
    }

    /* Lokasi & Rating */
    .tkp-shop-info {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: var(--text-muted);
        margin-top: auto; /* Mendorong info toko ke bawah */
        padding-top: 8px;
    }

    .tkp-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .bi-star-fill {
        color: #ffc400;
    }

    /* Action Button: More Pop! */
    .btn-add-cart {
        background: var(--primary-blue);
        color: #fff;
        border: none;
        font-weight: 700;
        font-size: 13px;
        width: 100%;
        border-radius: 10px;
        padding: 8px;
        margin-top: 12px;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(59, 97, 129, 0.2);
    }

    .btn-add-cart:hover {
        background: var(--secondary-blue);
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(59, 97, 129, 0.3);
    }

    .btn-add-cart:disabled {
        background: #e0e0e0;
        color: #a0a0a0;
        box-shadow: none;
        transform: none;
    }
</style>

<div class="tkp-card">
    {{-- IMAGE AREA --}}
    <div class="tkp-img-wrapper">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        </a>

        @auth
            <button type="button" 
                    onclick="toggleWishlist({{ $product->id }})" 
                    class="tkp-wishlist">
                <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @endauth
    </div>

    {{-- CONTENT AREA --}}
    <div class="tkp-content">
        <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none">
            <h3 class="tkp-title">{{ $product->name }}</h3>
        </a>

        <div class="tkp-price">{{ $product->formatted_price }}</div>

        @if($product->has_discount)
            <div class="tkp-discount-wrap">
                <span class="tkp-badge-disc">{{ $product->discount_percentage }}%</span>
                <span class="tkp-old-price">{{ $product->formatted_original_price }}</span>
            </div>
        @else
            {{-- Spacer agar tinggi kartu tetap konsisten meski tanpa diskon --}}
            <div style="height: 23px;"></div>
        @endif

        <div class="tkp-shop-info">
            <i class="bi bi-geo-alt-fill text-secondary"></i>
            <span>{{ $product->category->name }}</span>
        </div>

        <div class="tkp-rating">
            <div class="d-flex align-items-center">
                <i class="bi bi-star-fill me-1"></i>
                <span class="fw-bold text-dark">4.8</span>
            </div>
            <span class="mx-1">|</span>
            <span>Terjual 100+</span>
        </div>

        {{-- ACTION BUTTON --}}
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">

            <button type="submit" class="btn-add-cart" @if($product->stock == 0) disabled @endif>
                @if($product->stock == 0)
                    Habis
                @else
                    <i class="bi bi-cart-plus me-1"></i> + Keranjang
                @endif
            </button>
        </form>
    </div>
</div>
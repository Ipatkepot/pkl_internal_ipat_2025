{{-- ================================================
     FILE: resources/views/partials/product-card.blade.php
     STYLE: Tokopedia Clean Aesthetic
     ================================================ --}}

     <style>
    .tkp-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        position: relative;
    }

    .tkp-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .tkp-img-wrapper {
        position: relative;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        border-radius: 10px 10px 0 0;
    }

    .tkp-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Wishlist Button Overlay */
    .tkp-wishlist {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 10;
        background: rgba(255,255,255,0.8);
        backdrop-filter: blur(4px);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .tkp-wishlist:hover {
        background: #fff;
        transform: scale(1.1);
        color: #ef144a; /* Warna hati saat hover tetap merah agar intuitif */
    }

    .tkp-content {
        padding: 8px 10px 12px;
    }

    .tkp-title {
        font-size: 14px;
        color: #31353b;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
        line-height: 1.4;
    }

    .tkp-price {
        font-size: 16px;
        font-weight: 800;
        color: #31353b;
        margin-bottom: 4px;
    }

    /* Discount & Badges */
    .tkp-discount-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 6px;
    }

    .tkp-badge-disc {
        /* Diubah menjadi biru muda transparan agar senada dengan biru steel */
        background: #e8eff5; 
        color: #3B6181;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 4px;
        border-radius: 4px;
    }

    .tkp-old-price {
        font-size: 11px;
        color: #9fa6b0;
        text-decoration: line-through;
    }

    /* Shop Info & Rating */
    .tkp-shop-info {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #6d7588;
        margin-bottom: 4px;
    }

    .tkp-rating {
        display: flex;
        align-items: center;
        gap: 2px;
        font-size: 12px;
        color: #6d7588;
    }

    .bi-star-fill {
        color: #ffc400;
        font-size: 11px;
    }

    /* Button Buy - Diubah ke Biru Steel (#3B6181) */
    .btn-add-cart {
        border: 1px solid #3B6181;
        color: #3B6181;
        background: #fff;
        font-weight: 700;
        font-size: 13px;
        width: 100%;
        border-radius: 8px;
        padding: 6px;
        transition: 0.2s;
    }

    .btn-add-cart:hover {
        background: #3B6181;
        color: #fff;
    }

    .btn-add-cart:disabled {
        border-color: #e5e7eb;
        color: #9fa6b0;
        background: #f3f4f5;
    }
</style>

<div class="tkp-card h-100 d-flex flex-column">
    
    {{-- IMAGE AREA --}}
    <div class="tkp-img-wrapper">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        </a>

        @auth
            <button type="button" 
                    onclick="toggleWishlist({{ $product->id }})" 
                    class="tkp-wishlist shadow-sm">
                <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }}"></i>
            </button>
        @endauth
    </div>

    {{-- CONTENT AREA --}}
    <div class="tkp-content d-flex flex-column flex-grow-1">
        
        <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none">
            <div class="tkp-title">{{ $product->name }}</div>
        </a>

        <div class="tkp-price">{{ $product->formatted_price }}</div>

        @if($product->has_discount)
            <div class="tkp-discount-wrap">
                <span class="tkp-badge-disc">{{ $product->discount_percentage }}%</span>
                <span class="tkp-old-price">{{ $product->formatted_original_price }}</span>
            </div>
        @endif

        <div class="tkp-shop-info mt-1">
            <i class="bi bi-patch-check-fill text-info" style="font-size: 14px;"></i>
            <span>{{ $product->category->name }}</span>
        </div>

        <div class="tkp-rating">
            <i class="bi bi-star-fill"></i>
            <span>4.8 | Terjual 100+</span>
        </div>

        {{-- ACTION BUTTON --}}
        <div class="mt-3">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">

                <button type="submit" class="btn-add-cart" @if($product->stock == 0) disabled @endif>
                    {{ $product->stock == 0 ? 'Habis' : '+ Keranjang' }}
                </button>
            </form>
        </div>
    </div>
</div>
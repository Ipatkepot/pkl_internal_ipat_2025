<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 20px;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .img-wrapper {
        overflow: hidden;
        border-radius: 20px 20px 0 0;
        position: relative;
    }
    .product-card img {
        transition: transform 0.5s ease;
    }
    .product-card:hover img {
        transform: scale(1.1);
    }
    .wishlist-btn {
        z-index: 10;
        backdrop-filter: blur(5px);
        background: rgba(255, 255, 255, 0.8) !important;
        border: none !important;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    .wishlist-btn:hover {
        background: #fff !important;
        transform: scale(1.15);
    }
    .badge-discount {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 5;
        background: #ff3b30;
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.8rem;
    }
</style>

<div class="card product-card h-100 border-0 shadow-sm">
    {{-- Product Image Section --}}
    <div class="img-wrapper">
        @if($product->has_discount)
            <span class="badge-discount">-{{ $product->discount_percentage }}%</span>
        @endif

        {{-- Floating Wishlist Button --}}
        <button type="button" 
                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist({{ $product->id }})" 
                class="btn wishlist-btn position-absolute top-0 end-0 m-3 rounded-circle shadow-sm">
            <i id="wishlist-icon-{{ $product->id }}" 
               class="bi {{ Auth::check() && Auth::user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart text-dark' }} fs-5"></i>
        </button>

        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}"
                 class="card-img-top"
                 alt="{{ $product->name }}"
                 style="height: 240px; object-fit: cover;">
        </a>
    </div>

    {{-- Card Body --}}
    <div class="card-body d-flex flex-column p-4">
        <small class="text-uppercase tracking-widest text-muted fw-bold" style="font-size: 0.7rem;">
            {{ $product->category->name }}
        </small>

        <h5 class="card-title my-2">
            <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none text-dark fw-bold stretched-link">
                {{ Str::limit($product->name, 45) }}
            </a>
        </h5>

        <div class="mt-auto">
            @if($product->has_discount)
                <span class="text-muted text-decoration-line-through small">{{ $product->formatted_original_price }}</span>
            @endif
            <div class="d-flex align-items-center">
                <span class="fs-4 fw-black text-primary">{{ $product->formatted_price }}</span>
            </div>
        </div>

        {{-- Stock Indicator --}}
        <div class="mt-3">
            @if($product->stock <= 5 && $product->stock > 0)
                <div class="d-flex align-items-center text-warning small fw-bold">
                    <span class="spinner-grow spinner-grow-sm me-2" role="status"></span>
                    Hampir Habis (Sisa {{ $product->stock }})
                </div>
            @elseif($product->stock == 0)
                <div class="text-danger small fw-bold">
                    <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                </div>
            @else
                <div class="text-success small">
                    <i class="bi bi-check-circle-fill me-1"></i> Stok Tersedia
                </div>
            @endif
        </div>
    </div>

    {{-- Card Footer --}}
    <div class="card-footer bg-white border-0 p-4 pt-0" style="z-index: 5;">
        <form action="{{ route('cart.add') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            
            <button type="submit" 
                    class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center"
                    @if($product->stock == 0) disabled @endif>
                <i class="bi bi-bag-plus-fill me-2"></i>
                {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah Keranjang' }}
            </button>
        </form>
    </div>
</div>
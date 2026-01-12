@extends('layouts.app')

@section('title', $product->name)

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
    }

    :root {
        --steel-blue: #3B6181;
        --steel-blue-dark: #2d4a63;
        --steel-blue-light: rgba(59, 97, 129, 0.1);
    }

    /* Breadcrumb Styling */
    .breadcrumb-item a { color: #64748b; text-decoration: none; font-size: 13px; transition: 0.2s; }
    .breadcrumb-item a:hover { color: var(--steel-blue); }
    .breadcrumb-item.active { color: var(--steel-blue); font-weight: 700; font-size: 13px; }

    /* Product Gallery */
    .product-gallery-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: none;
    }

    .main-image-container {
        position: relative;
        height: 480px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    #main-image, #main-video {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: all 0.4s ease;
    }

    #main-video { display: none; width: 100%; height: 100%; border-radius: 15px; }

    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        color: var(--steel-blue);
        z-index: 10;
        transition: 0.3s;
    }
    .nav-arrow:hover { background: var(--steel-blue); color: white; }

    .thumbnail-scroll {
        display: flex;
        gap: 12px;
        padding: 0 20px 20px;
        justify-content: center;
        overflow-x: auto;
    }
    .thumbnail-img {
        width: 75px;
        height: 75px;
        border-radius: 14px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .thumbnail-img.active {
        border-color: var(--steel-blue);
        transform: scale(1.05);
        box-shadow: 0 5px 10px rgba(59, 97, 129, 0.2);
    }

    /* Info Section */
    .product-info-card {
        background: white;
        border-radius: 24px;
        padding: 35px;
        border: none;
    }

    .price-tag {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--steel-blue);
        letter-spacing: -1px;
    }

    /* Meta Info (Stok & Berat) */
    .meta-container {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    .meta-item {
        flex: 1;
        background: #fcfcfd;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .meta-icon {
        width: 42px;
        height: 42px;
        background: var(--steel-blue-light);
        color: var(--steel-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .meta-label { display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; }
    .meta-value { display: block; font-size: 15px; font-weight: 700; color: #1e293b; }

    /* Stock Progress Bar */
    .stock-progress { height: 6px; border-radius: 10px; background: #f1f5f9; margin-top: 6px; overflow: hidden; }
    .stock-bar { height: 100%; background: var(--steel-blue); transition: width 1s ease-in-out; }

    /* Quantity & Buttons */
    .qty-wrapper {
        background: #f1f5f9;
        border-radius: 14px;
        padding: 6px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 25px;
    }
    .qty-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        background: white;
        font-weight: bold;
        transition: 0.2s;
    }
    .qty-btn:hover { background: var(--steel-blue); color: white; }
    #qty-display {
        background: transparent; border: none; width: 60px; text-align: center; font-weight: 800;
    }

    .btn-buy {
        background: linear-gradient(135deg, var(--steel-blue) 0%, var(--steel-blue-dark) 100%);
        color: white; border: none; border-radius: 15px; padding: 16px; font-weight: 700; transition: 0.3s;
    }
    .btn-buy:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(59, 97, 129, 0.3); color: white; }

    .btn-wishlist {
        background: white; border: 2px solid #f1f5f9; border-radius: 15px; padding: 12px; transition: 0.3s;
    }
    .btn-wishlist:hover { border-color: #fda4af; color: #e11d48; background: #fff1f2; }
</style>

<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 25) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left: Media Gallery --}}
        <div class="col-lg-6">
            <div class="product-gallery-card shadow-sm">
                <div class="main-image-container">
                    @if($product->video_url)
                        <video id="main-video" controls controlsList="nodownload">
                            <source src="{{ asset('storage/' . $product->video_url) }}" type="video/mp4">
                        </video>
                    @endif

                    <img src="{{ asset('storage/' . $product->image) }}" id="main-image" alt="{{ $product->name }}">

                    @if($product->images->count() > 0 || $product->video_url)
                        <button class="nav-arrow" style="left: 15px;" onclick="changeMedia(-1)">‹</button>
                        <button class="nav-arrow" style="right: 15px;" onclick="changeMedia(1)">›</button>
                    @endif
                </div>

                <div class="thumbnail-scroll">
                    @if($product->video_url)
                        <div class="thumbnail-img d-flex align-items-center justify-content-center bg-dark text-white active" 
                             id="thumb-video" onclick="setVideo()">
                            <i class="bi bi-play-circle-fill fs-3"></i>
                        </div>
                    @endif

                    @foreach($product->images as $index => $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" 
                             class="thumbnail-img {{ (!$product->video_url && $loop->first) ? 'active' : '' }}" 
                             data-index="{{ $index }}"
                             onclick="setImage({{ $index }})">
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Product Info --}}
        <div class="col-lg-6">
            <div class="product-info-card shadow-sm">
                <h1 class="fw-bold mb-3 h2">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="price-tag">{{ $product->formatted_price }}</div>
                    @if($product->has_discount)
                        <span class="text-muted text-decoration-line-through">{{ $product->formatted_original_price }}</span>
                    @endif
                </div>

                {{-- Meta Info: Stok & Berat --}}
                <div class="meta-container">
                    <div class="meta-item">
                        <div class="meta-icon"><i class="bi bi-box-seam"></i></div>
                        <div>
                            <span class="meta-label">Berat</span>
                            <span class="meta-value">{{ $product->weight }} Gram</span>
                        </div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-icon"><i class="bi bi-archive"></i></div>
                        <div class="w-100">
                            <span class="meta-label">Stok Barang</span>
                            <span class="meta-value {{ $product->stock < 5 ? 'text-danger' : '' }}">
                                {{ $product->stock > 0 ? $product->stock . ' Unit' : 'Habis' }}
                            </span>
                            @if($product->stock > 0)
                            <div class="stock-progress">
                                @php $percent = min(($product->stock / 20) * 100, 100); @endphp
                                <div class="stock-bar" style="width: {{ $percent }}%"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="small fw-bold text-muted mb-2 d-block text-uppercase">Tentukan Jumlah</label>
                    <div class="qty-wrapper shadow-sm">
                        <button class="qty-btn" onclick="updateQty(-1)">-</button>
                        <input type="number" id="qty-display" value="1" readonly>
                        <button class="qty-btn" onclick="updateQty(1)">+</button>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="qty-cart" value="1">
                            <button class="btn btn-outline-biru-steel w-100 py-3 fw-bold" @disabled($product->stock <= 0)>
                                <i class="bi bi-cart-plus me-2"></i> Keranjang
                            </button>
                        </form>
                    </div>
                    <div class="col-sm-6">
                        <form action="{{ route('checkout.direct') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="qty-direct" value="1">
                            <button class="btn btn-buy w-100 py-3" @disabled($product->stock <= 0)>
                                Beli Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <button type="button" onclick="toggleWishlist({{ $product->id }})" 
                        class="btn btn-wishlist w-100 fw-bold wishlist-btn-{{ $product->id }}">
                    <i class="bi {{ auth()->user() && auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }} me-2"></i>
                    <span>{{ auth()->user() && auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}</span>
                </button>

                <hr class="my-5">
                <h6 class="fw-bold text-uppercase mb-3" style="font-size: 13px; letter-spacing: 1px;">Deskripsi Produk</h6>
                <div class="text-muted lh-lg" style="font-size: 14px;">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Data Images dari Backend
    const extraImages = @json($product->images->pluck('image_path')->map(fn($p) => asset('storage/'.$p)));
    const mainImgUrl = "{{ asset('storage/' . $product->image) }}";
    const allImages = [mainImgUrl, ...extraImages];
    
    let currentIndex = 0;
    let isVideo = {{ $product->video_url ? 'true' : 'false' }};
    const imgEl = document.getElementById('main-image');
    const vidEl = document.getElementById('main-video');

    function setVideo() {
        isVideo = true;
        imgEl.style.display = 'none';
        vidEl.style.display = 'block';
        updateThumbs('video');
    }

    function setImage(idx) {
        isVideo = false;
        currentIndex = idx;
        if(vidEl) { vidEl.pause(); vidEl.style.display = 'none'; }
        imgEl.style.display = 'block';
        imgEl.src = allImages[idx];
        updateThumbs(idx);
    }

    function updateThumbs(activeKey) {
        document.querySelectorAll('.thumbnail-img').forEach(t => {
            t.classList.remove('active');
            if(activeKey === 'video' && t.id === 'thumb-video') t.classList.add('active');
            if(t.dataset.index == activeKey) t.classList.add('active');
        });
    }

    function changeMedia(dir) {
        if(isVideo) {
            setImage(dir > 0 ? 0 : allImages.length - 1);
        } else {
            let next = currentIndex + dir;
            if(next >= allImages.length) {
                {{ $product->video_url ? 'setVideo()' : 'setImage(0)' }};
            } else if(next < 0) {
                {{ $product->video_url ? 'setVideo()' : 'setImage(allImages.length - 1)' }};
            } else {
                setImage(next);
            }
        }
    }

    function updateQty(val) {
        let input = document.getElementById('qty-display');
        let current = parseInt(input.value);
        let next = current + val;
        let max = {{ $product->stock }};
        
        if(next >= 1 && next <= max) {
            input.value = next;
            document.getElementById('qty-cart').value = next;
            document.getElementById('qty-direct').value = next;
        }
    }

    // Panggil video pertama kali jika ada
    window.onload = () => { if(isVideo) setVideo(); };
</script>
@endpush
@endsection
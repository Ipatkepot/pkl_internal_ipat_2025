@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* Global Styling */
    body { 
        background-color: #f1f5f9; /* Soft light blue/grey background */
        font-family: 'Plus Jakarta Sans', sans-serif; 
        color: #1e293b;
    }

    .catalog-container {
        padding-top: 3rem;
        padding-bottom: 5rem;
    }

    /* Modern Filter Card */
    .filter-box { 
        border: none;
        border-radius: 20px; 
        position: sticky; 
        top: 110px; 
        background: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        padding: 1.75rem;
    }

    .filter-title { 
        font-size: 1rem; 
        font-weight: 800; 
        color: #0f172a; 
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-section { 
        border-bottom: 1px solid #f1f5f9; 
        padding-bottom: 1.5rem; 
        margin-bottom: 1.5rem; 
    }

    /* Price Input Styling */
    .price-input-group .input-group-text { 
        background: #f8fafc; 
        border: 1px solid #e2e8f0;
        border-right: none;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        border-radius: 12px 0 0 12px;
    }
    
    .price-input-group .form-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: none;
        border-radius: 0 12px 12px 0;
        font-size: 13px;
        color: #1e293b;
    }

    .price-input-group .form-control:focus {
        background: #fff;
        box-shadow: none;
        border-color: #3B6181;
    }

    /* Product Grid & Items */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); 
        gap: 24px;
    }

    @media (min-width: 768px) { .product-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1200px) { .product-grid { grid-template-columns: repeat(4, 1fr); } }

    .product-item {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .product-item:hover {
        transform: translateY(-10px);
    }

    /* Custom Checkbox/Radio */
    .form-check-input {
        border: 2px solid #e2e8f0;
        cursor: pointer;
    }
    .form-check-input:checked { 
        background-color: #3B6181; 
        border-color: #3B6181; 
    }

    .form-check-label {
        font-weight: 500;
        color: #475569;
        font-size: 14px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .form-check-input:checked + .form-check-label { color: #3B6181; font-weight: 600; }

    /* Buttons */
    .btn-apply {
        background: #3B6181;
        background: linear-gradient(135deg, #3B6181 0%, #253d52 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 700;
        letter-spacing: 0.3px;
        transition: all 0.3s;
    }

    .btn-apply:hover {
        background: #253d52;
        box-shadow: 0 10px 15px -3px rgba(59, 97, 129, 0.4);
        color: white;
    }

    .btn-reset-link {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-reset-link:hover { background: #f1f5f9; color: #ef4444; }

    /* Catalog Header */
    .catalog-title { font-weight: 800; font-size: 28px; letter-spacing: -1px; }

    .sort-select {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        font-size: 14px;
        background-color: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid px-lg-5 catalog-container">
    <div class="row g-4">

        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="filter-box">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="filter-title m-0">
                        <i class="bi bi-sliders2-vertical text-primary"></i> Filter
                    </h6>
                    <a href="{{ route('catalog.index') }}" class="btn-reset-link">Reset</a>
                </div>

                <form action="{{ route('catalog.index') }}" method="GET">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    {{-- KATEGORI --}}
                    <div class="filter-section">
                        <div class="filter-title" style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Kategori</div>
                        <div class="category-scroll pe-2" style="max-height: 250px; overflow-y: auto;">
                            @foreach($categories as $cat)
                                <div class="form-check mb-2 p-0 d-flex align-items-center">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="category" 
                                           id="cat-{{ $cat->slug }}" value="{{ $cat->slug }}"
                                           {{ request('category') == $cat->slug ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label d-flex justify-content-between w-100 align-items-center" for="cat-{{ $cat->slug }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="badge rounded-pill bg-slate-100 text-muted border fw-normal" style="font-size: 10px;">{{ $cat->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- HARGA --}}
                    <div class="filter-section border-0 mb-2">
                        <div class="filter-title" style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Harga</div>
                        <div class="price-input-group">
                            <div class="input-group mb-2 shadow-sm rounded-3">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group mb-3 shadow-sm rounded-3">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-apply w-100 py-3 mt-2 shadow">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="col-lg-9">
            <div class="row align-items-end mb-4">
                <div class="col-md-7">
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb mb-0" style="font-size: 12px;">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Katalog</li>
                        </ol>
                    </nav>
                    <h4 class="catalog-title mb-1">
                        @if(request('category'))
                            {{ $categories->where('slug', request('category'))->first()->name ?? 'Katalog Produk' }}
                        @elseif(request('q'))
                            Pencarian: "{{ request('q') }}"
                        @else
                            Semua Produk
                        @endif
                    </h4>
                    <p class="text-muted small mb-0">Ditemukan <b>{{ $products->total() }}</b> produk berkualitas untukmu</p>
                </div>
                <div class="col-md-5 d-flex justify-content-md-end mt-3 mt-md-0">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small fw-medium">Urutkan:</span>
                        <form method="GET">
                            @foreach(request()->except('sort') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" class="form-select sort-select shadow-sm" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                    <div class="product-item">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-white rounded-5 shadow-sm mt-4">
                        <img src="https://illustrations.popsy.co/slate/shaking-hands.svg" width="200" class="mb-4">
                        <h5 class="fw-bold">Yah, produk tidak ditemukan</h5>
                        <p class="text-muted small px-5">Coba gunakan kata kunci lain atau hapus semua filter yang terpasang.</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-pill mt-2">Hapus Filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection
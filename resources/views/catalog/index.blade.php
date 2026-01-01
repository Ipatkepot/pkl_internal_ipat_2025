@extends('layouts.app')

@section('content')

<style>
    /* Global Style */
    body { 
        background-color: #ffffff; 
        font-family: 'Inter', -apple-system, sans-serif; 
        color: #31353b;
    }
    
    /* Sidebar Filter Sticky */
    .filter-box { 
        border: 1px solid #e5e7eb; 
        border-radius: 12px; 
        position: sticky; 
        top: 100px; 
        background: #fff;
    }
    
    .filter-title { 
        font-size: 14px; 
        font-weight: 700; 
        color: #31353b; 
        margin-bottom: 12px;
    }
    
    .filter-section { 
        border-bottom: 1px solid #efefef; 
        padding-bottom: 15px; 
        margin-bottom: 15px; 
    }
    
    /* Input Harga Compact */
    .price-input-group .input-group-text { 
        background: #f0f3f7; 
        font-size: 11px; 
        color: #6d7588; 
        font-weight: 600;
    }
    
    .price-input-group input:focus { 
        border-color: #3B6181;
        box-shadow: none;
    }

    /* Catalog Header */
    .catalog-title { 
        font-weight: 800; 
        font-size: 18px; 
    }
    
    .sort-select { 
        width: 160px; 
        font-size: 12px; 
        border-radius: 8px; 
        padding: 6px 12px;
    }

    /* Grid Produk Compact (5 Kolom Desktop) */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); 
        gap: 12px;
    }

    @media (min-width: 768px) {
        .product-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (min-width: 1200px) {
        .product-grid { grid-template-columns: repeat(5, 1fr); }
    }

    /* Checkbox & Radio Custom Biru Steel */
    .form-check-input:checked { 
        background-color: #3B6181; 
        border-color: #3B6181; 
    }
    
    .form-check-label { 
        font-size: 13px; 
        cursor: pointer; 
    }

    /* Pagination Mini Style */
    .custom-pagination .pagination {
        gap: 4px;
    }

    .custom-pagination .page-link {
        padding: 4px 10px;
        font-size: 12px;
        color: #6d7588;
        border-radius: 6px !important;
        border: 1px solid #e5e7eb;
    }

    .custom-pagination .page-item.active .page-link {
        background-color: #3B6181;
        border-color: #3B6181;
        color: white;
        font-weight: bold;
    }

    .custom-pagination .page-link:hover {
        background-color: #f0f3f7;
        color: #3B6181;
    }

    .btn-reset {
        color: #3B6181;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: 0.2s;
    }

    .btn-reset:hover {
        color: #2d4a63;
    }
</style>

<div class="container-fluid px-lg-5 py-4">
    <div class="row g-4">

        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="p-3 filter-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="filter-title m-0">Filter</h6>
                    <a href="{{ route('catalog.index') }}" class="btn-reset">Reset</a>
                </div>

                <form action="{{ route('catalog.index') }}" method="GET">
                    {{-- Q Search Persistence --}}
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    {{-- KATEGORI --}}
                    <div class="filter-section">
                        <div class="filter-title">Kategori</div>
                        <div style="max-height: 200px; overflow-y: auto;">
                            @foreach($categories as $cat)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" 
                                           id="cat-{{ $cat->slug }}" value="{{ $cat->slug }}"
                                           {{ request('category') == $cat->slug ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label d-flex justify-content-between pe-2" for="cat-{{ $cat->slug }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-muted small">({{ $cat->products_count }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- RENTANG HARGA --}}
                    <div class="filter-section border-0 mb-0">
                        <div class="filter-title">Harga</div>
                        <div class="price-input-group mb-3">
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_price" class="form-control" placeholder="Harga Minimum" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Harga Maksimum" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 btn-sm fw-bold py-2 shadow-sm" style="background-color: #3B6181; color: white; border: none;">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="col-lg-9">
            {{-- HEADER KATALOG --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="catalog-title mb-1">
                        @if(request('category'))
                            {{ $categories->where('slug', request('category'))->first()->name ?? 'Katalog' }}
                        @elseif(request('q'))
                            Hasil pencarian: "{{ request('q') }}"
                        @else
                            Semua Produk
                        @endif
                    </h4>
                    <p class="text-muted small mb-0">Menampilkan <b>{{ $products->total() }}</b> produk terbaik</p>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small d-none d-md-block">Urutkan:</span>
                    <form method="GET">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm sort-select" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- GRID PRODUK --}}
            <div class="product-grid">
                @forelse($products as $product)
                    <div class="product-item">
                        <x-product-card :product="$product" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/arael/k_p_1.png" width="140" class="mb-3 opacity-50">
                        <h6 class="fw-bold">Wah, produk nggak ditemukan</h6>
                        <p class="text-muted small">Coba cari kata kunci lain atau hapus filter yang ada.</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-sm px-4 fw-bold" style="background-color: #3B6181; color: white; border:none;">Hapus Filter</a>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION MINI --}}
            <div class="mt-5 d-flex justify-content-center custom-pagination">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@endsection 
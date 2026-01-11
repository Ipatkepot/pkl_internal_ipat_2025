{{-- resources/views/admin/products/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0 fw-bold text-info">
                <i class="bi bi-eye me-1"></i> Detail Produk
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning text-white shadow-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- ================= MEDIA (IMAGES & VIDEO) ================= --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3">
                        <label class="fw-bold text-muted mb-2 small text-uppercase">Foto Utama</label>
                        {{-- Primary Image --}}
                        <img src="{{ asset('storage/'.$product->primaryImage?->image_path) }}"
                            class="img-fluid rounded mb-3 w-100 border" style="object-fit:contain; max-height:320px; background:#f8f9fa;">

                        {{-- Gallery --}}
                        <label class="fw-bold text-muted mb-2 small text-uppercase">Galeri Foto</label>
                        <div class="row g-2 mb-4">
                            @foreach($product->images as $image)
                            <div class="col-4">
                                <img src="{{ asset('storage/'.$image->image_path) }}" class="img-fluid rounded border shadow-sm"
                                    style="object-fit:cover; height:90px; width:100%">
                            </div>
                            @endforeach
                        </div>

                        {{-- Video Section --}}
                        <label class="fw-bold text-primary mb-2 small text-uppercase">
                            <i class="bi bi-play-circle-fill me-1"></i> Video Produk
                        </label>
                        @if($product->video_url)
                        <div class="rounded overflow-hidden border shadow-sm bg-dark">
                            <video class="w-100" controls controlsList="nodownload">
                                <source src="{{ asset('storage/'.$product->video_url) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        </div>
                        @else
                        <div class="alert alert-light border text-center py-4 mb-0">
                            <i class="bi bi-camera-video-off text-muted fs-3"></i>
                            <p class="small text-muted mb-0 mt-2">Tidak ada video untuk produk ini.</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ================= PRODUCT INFO ================= --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $product->name }}
                        </h4>

                        <p class="text-muted mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-tags me-1"></i> {{ $product->category->name }}
                            </span>
                        </p>

                        {{-- Price --}}
                        <div class="bg-light p-3 rounded-3 mb-4">
                            <label class="small text-muted d-block fw-bold">Harga Jual:</label>
                            <h3 class="text-primary fw-bold mb-0">
                                Rp {{ number_format($product->discount_price ?: $product->price, 0, ',', '.') }}
                                @if($product->discount_price && $product->discount_price < $product->price)
                                <span class="text-muted fs-6 text-decoration-line-through ms-2 fw-normal">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @endif
                            </h3>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4 d-flex gap-2">
                            <span class="badge rounded-pill px-3 py-2 bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                <i class="bi bi-{{ $product->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                {{ $product->is_active ? 'Aktif di Katalog' : 'Draft / Nonaktif' }}
                            </span>

                            @if($product->is_featured)
                            <span class="badge rounded-pill px-3 py-2 bg-warning text-dark border border-warning">
                                <i class="bi bi-star-fill me-1"></i> Produk Unggulan
                            </span>
                            @endif
                        </div>

                        <hr>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label class="fw-bold text-muted small text-uppercase mb-2 d-block">Deskripsi</label>
                            <div class="p-3 border rounded bg-light" style="white-space: pre-line;">
                                {{ $product->description ?: 'Tidak ada deskripsi.' }}
                            </div>
                        </div>

                        {{-- Meta Details --}}
                        <div class="row text-center g-3">
                            <div class="col-md-4">
                                <div class="p-2 border rounded shadow-xs">
                                    <small class="text-muted d-block">Stok</small>
                                    <strong class="fs-5">{{ $product->stock }}</strong>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-2 border rounded shadow-xs">
                                    <small class="text-muted d-block">Berat</small>
                                    <strong class="fs-5">{{ $product->weight }} <small>gr</small></strong>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="p-2 border rounded shadow-xs">
                                    <small class="text-muted d-block">Tgl Input</small>
                                    <strong class="fs-6">{{ $product->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
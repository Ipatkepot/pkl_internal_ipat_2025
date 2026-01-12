@extends('layouts.app')

@section('title', 'Riwayat Transaksi - GadgetMurah')

@section('content')
<style>
    :root {
        --biru-steel: #3B6181;
        --biru-steel-soft: #f0f4f8;
        --biru-steel-hover: #2d4a63;
    }

    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Header Styling */
    .page-header {
        background: linear-gradient(135deg, var(--biru-steel) 0%, #4a7a9e 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(59, 97, 129, 0.2);
    }

    /* Order Card Styling */
    .order-card {
        border: none;
        border-radius: 20px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.06);
    }

    .order-header {
        padding: 15px 25px;
        background-color: #fafbfc;
        border-bottom: 1px solid #f1f5f9;
        border-radius: 20px 20px 0 0;
    }

    /* Badge Modernization */
    .status-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-processing { background: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
    .status-shipped { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .status-delivered { background: var(--biru-steel-soft); color: var(--biru-steel); border: 1px solid #e2e8f0; }
    .status-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    /* Product Thumbnail */
    .product-thumb {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        object-fit: cover;
        border: 1px solid #f1f5f9;
    }

    .btn-detail {
        background-color: white;
        color: var(--biru-steel);
        border: 2px solid var(--biru-steel-soft);
        font-weight: 700;
        border-radius: 12px;
        padding: 10px 20px;
        transition: 0.3s;
    }

    .btn-detail:hover {
        background-color: var(--biru-steel);
        color: white;
        border-color: var(--biru-steel);
    }

    /* Pagination Styling */
    .pagination { gap: 5px; }
    .page-item .page-link {
        border-radius: 10px;
        color: var(--biru-steel);
        border: none;
        font-weight: 600;
        margin: 0 2px;
    }
    .page-item.active .page-link {
        background-color: var(--biru-steel);
        box-shadow: 0 4px 10px rgba(59, 97, 129, 0.3);
    }
</style>

<div class="container py-5">
    {{-- Header Section --}}
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h2 class="fw-800 mb-1" style="font-weight: 800; letter-spacing: -1px;">Pesanan Saya</h2>
            <p class="mb-0 opacity-75">Kelola dan pantau semua transaksi belanja Anda</p>
        </div>
        <div class="d-none d-md-block">
            <i class="bi bi-bag-check fs-1 opacity-50"></i>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">

            @if($orders->isEmpty())
                <div class="text-center py-5 bg-white rounded-5 shadow-sm border">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" width="120" class="mb-4 opacity-50">
                    <h4 class="fw-bold">Belum Ada Transaksi</h4>
                    <p class="text-muted mb-4">Sepertinya Anda belum melakukan pemesanan apapun.<br>Yuk, cari gadget impianmu sekarang!</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold" style="background: var(--biru-steel); border: none;">
                        Mulai Belanja
                    </a>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="card order-card mb-4 shadow-sm">
                        {{-- Top Info --}}
                        <div class="order-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white p-2 rounded-3 border shadow-sm">
                                    <i class="bi bi-shop text-biru-steel"></i>
                                </div>
                                <div>
                                    <span class="fw-800 d-block text-dark" style="font-size: 14px;">Belanja</span>
                                    <small class="text-muted">{{ $order->created_at->translatedFormat('d M Y') }}</small>
                                </div>
                                @php
                                    $statusSlug = strtolower($order->status);
                                    $statusLabel = [
                                        'pending' => 'Menunggu Pembayaran',
                                        'processing' => 'Diproses',
                                        'shipped' => 'Dikirim',
                                        'delivered' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span class="status-badge status-{{ $statusSlug }}">
                                    {{ $statusLabel[$statusSlug] ?? $order->status }}
                                </span>
                            </div>
                            <div class="text-md-end">
                                <small class="text-muted d-block" style="font-size: 11px;">NOMOR PESANAN</small>
                                <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4">
                            <div class="row align-items-center g-4">
                                {{-- Produk Thumbnail & Info --}}
                                <div class="col-lg-6 col-md-12">
                                    @if($order->items->count() > 0)
                                        @php $firstItem = $order->items->first(); @endphp
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $firstItem->product && $firstItem->product->image ? asset('storage/' . $firstItem->product->image) : 'https://placehold.co/80x80?text=Gadget' }}" 
                                                 class="product-thumb me-4 shadow-sm">
                                            <div class="overflow-hidden">
                                                <h6 class="fw-bold text-dark text-truncate mb-1" style="max-width: 300px;">
                                                    {{ $firstItem->product_name }}
                                                </h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $firstItem->quantity }} Barang x <span class="fw-bold text-dark">Rp {{ number_format($firstItem->price, 0, ',', '.') }}</span>
                                                </p>
                                                @if($order->items->count() > 1)
                                                    <div class="mt-2">
                                                        <span class="badge bg-light text-muted border-0 fw-normal py-1 px-2" style="font-size: 11px;">
                                                            +{{ $order->items->count() - 1 }} Produk Lainnya
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Total & Action --}}
                                <div class="col-lg-3 col-md-6 text-lg-center border-start-lg">
                                    <div class="ps-lg-4 border-start-md">
                                        <small class="text-muted d-block mb-1">Total Belanja</small>
                                        <h5 class="fw-800 text-biru-steel mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h5>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 text-md-end">
                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-detail">
                                            Detail Pesanan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Custom Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            @endif

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted small fw-bold hover-biru">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
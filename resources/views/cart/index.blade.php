@extends('layouts.app')

@section('title', 'Keranjang Belanja - GadgetMurah')

@section('content')
<style>
    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Typography & Header */
    .section-title { font-weight: 800; letter-spacing: -0.5px; color: #1e293b; }
    
    /* Glassmorphism Card */
    .cart-card { 
        border-radius: 24px; 
        border: 1px solid rgba(255, 255, 255, 0.8); 
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    /* Table Styling */
    .table thead th { 
        background-color: #f1f5f9; 
        font-size: 12px; 
        color: #64748b; 
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px;
        border: none;
    }

    .product-box { transition: all 0.3s ease; }
    .product-box:hover { transform: translateX(5px); }
    
    .product-name { 
        font-size: 16px; 
        font-weight: 700; 
        color: #334155; 
        line-height: 1.4;
    }

    /* Modern Quantity Controller */
    .qty-wrapper {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 12px;
        width: fit-content;
        padding: 4px;
        margin: 0 auto;
    }
    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: none;
        background: white;
        color: #3B6181;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .qty-btn:hover { background: #3B6181; color: white; }
    .qty-input-custom {
        width: 45px;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 800;
        color: #1e293b;
        outline: none;
    }

    /* Summary Card */
    .summary-card { 
        border-radius: 24px; 
        border: none; 
        background: #ffffff;
        position: sticky; 
        top: 110px;
    }

    .price-total {
        color: #3B6181;
        font-weight: 800;
        font-size: 1.5rem;
    }

    /* Custom Buttons */
    .btn-checkout { 
        background: linear-gradient(135deg, #3B6181 0%, #2d4a63 100%);
        border: none; 
        font-weight: 700; 
        padding: 16px;
        border-radius: 15px;
        transition: all 0.3s;
    }
    .btn-checkout:hover { 
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(59, 97, 129, 0.25);
    }

    .trash-btn {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #94a3b8;
        background: #fee2e2;
        color: #ef4444;
        transition: 0.3s;
    }
    .trash-btn:hover { background: #ef4444; color: white; transform: rotate(10deg); }

    /* Empty State */
    .empty-state-icon {
        background: #f1f5f9;
        width: 120px;
        height: 120px;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        font-size: 50px;
        color: #cbd5e1;
    }
</style>

<div class="container py-5">
    {{-- Header Section --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="section-title mb-1">Keranjang Belanja</h2>
            <p class="text-muted">Kelola item pilihanmu sebelum melakukan pembayaran.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge px-4 py-3 rounded-4 shadow-sm" style="background: white; color: #3B6181; border: 1px solid #e2e8f0;">
                <i class="bi bi-cart-check-fill me-2"></i> {{ $cart && $cart->items ? $cart->items->count() : 0 }} Produk Terpilih
            </span>
        </div>
    </div>

    @if($cart && $cart->items->count() > 0)
        <div class="row g-4">
            {{-- List Produk --}}
            <div class="col-lg-8">
                <div class="card cart-card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Detail Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end pe-4">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        @php
                                            $itemPrice = $item->product->price; 
                                            $itemSubtotal = $itemPrice * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center product-box">
                                                    <div class="position-relative">
                                                        <img src="{{ $item->product->image_url }}" 
                                                             class="rounded-4 shadow-sm border" width="100" height="100" 
                                                             style="object-fit: cover;">
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}" class="product-name text-decoration-none d-block mb-1">
                                                            {{ Str::limit($item->product->name, 40) }}
                                                        </a>
                                                        <p class="fw-bold text-primary mb-2" style="font-size: 14px;">
                                                            Rp {{ number_format($itemPrice, 0, ',', '.') }}
                                                        </p>
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none small fw-bold" onclick="return confirm('Hapus item ini?')">
                                                                <i class="bi bi-trash3 me-1"></i> Lepaskan Produk
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="qty-wrapper shadow-sm border">
                                                        <button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown(); this.form.submit()">
                                                            <i class="bi bi-dash-lg"></i>
                                                        </button>
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                               min="1" max="{{ $item->product->stock }}" 
                                                               class="qty-input-custom" onchange="this.form.submit()" readonly>
                                                        <button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp(); this.form.submit()">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="fw-extrabold text-dark fs-5">
                                                    Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="col-lg-4">
                <div class="card summary-card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                        
                        @php
                            $totalQuantity = $cart->items->sum('quantity');
                            $totalHarga = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
                        @endphp

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal ({{ $totalQuantity }} Barang)</span>
                            <span class="fw-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Biaya Layanan</span>
                            <span class="text-success fw-bold">GRATIS</span>
                        </div>
                        
                        <div class="bg-light p-3 rounded-4 my-4 border border-dashed">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Total Pembayaran</span>
                                <span class="price-total">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-checkout btn-lg w-100 mb-3 text-white shadow">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right-short ms-2"></i>
                        </a>
                        
                        <a href="{{ route('catalog.index') }}" class="btn btn-white w-100 py-2 fw-bold text-muted" style="font-size: 14px; border: 1px solid #e2e8f0; border-radius: 12px;">
                            <i class="bi bi-bag-plus me-2"></i>Eksplor Produk Lainnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-5">
            <div class="empty-state-icon shadow-sm">
                <i class="bi bi-cart-x"></i>
            </div>
            <h3 class="fw-bold">Keranjangmu masih kosong</h3>
            <p class="text-muted mb-4 px-5">Sepertinya kamu belum memilih gadget impianmu. <br>Yuk, cek koleksi terbaru kami!</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-checkout px-5 py-3 text-white">
                Mulai Belanja Sekarang
            </a>
        </div>
    @endif
</div>
@endsection
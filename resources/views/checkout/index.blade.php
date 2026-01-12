@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - GadgetMurah')

@section('content')
<style>
    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Header & Progress Indicator */
    .checkout-title { font-weight: 800; letter-spacing: -0.5px; color: #1e293b; }
    
    /* Card Styling */
    .checkout-card { 
        border-radius: 24px; 
        border: 1px solid rgba(255, 255, 255, 0.8); 
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    /* Form Design */
    .form-label { font-size: 12px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .form-control, .form-select { 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        padding: 12px 16px; 
        font-size: 14px; 
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .form-control:focus { 
        background-color: #fff;
        border-color: #3B6181; 
        box-shadow: 0 0 0 4px rgba(59, 97, 129, 0.1); 
    }

    /* Order Summary List */
    .order-summary-list {
        max-height: 320px;
        overflow-y: auto;
        padding-right: 8px;
    }
    .product-mini-card {
        padding: 12px;
        border-radius: 16px;
        background: #f1f5f9;
        margin-bottom: 12px;
        border: 1px solid transparent;
        transition: 0.2s;
    }
    .product-mini-card:hover { border-color: #3B6181; background: #fff; }

    /* Payment Info Box */
    .payment-method-box {
        background: linear-gradient(135deg, #3B6181 0%, #2d4a63 100%);
        color: white;
        border-radius: 16px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }
    .payment-method-box::after {
        content: ''; position: absolute; top: -20px; right: -20px;
        width: 80px; height: 80px; background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* Sticky Summary */
    .sticky-summary { position: sticky; top: 110px; }

    /* Custom Button */
    .btn-pay { 
        background: linear-gradient(135deg, #3B6181 0%, #2d4a63 100%);
        border: none; 
        font-weight: 700; 
        padding: 16px;
        border-radius: 15px;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(59, 97, 129, 0.2);
    }
    .btn-pay:hover { 
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(59, 97, 129, 0.3);
    }

    .total-amount { color: #3B6181; font-weight: 800; font-size: 1.4rem; }
</style>

<div class="container py-5">
    {{-- Breadcrumb/Header --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="checkout-title mb-2">Checkout</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Keranjang</a></li>
                    <li class="breadcrumb-item active fw-bold text-biru-steel" aria-current="page">Pengiriman & Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-4">
        {{-- SISI KIRI: DATA PENGIRIMAN --}}
        <div class="col-lg-7">
            <div class="card checkout-card border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Informasi Pengiriman</h5>
                    </div>

                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nama Penerima</label>
                                <input type="text" name="shipping_name" 
                                    class="form-control @error('shipping_name') is-invalid @enderror"
                                    value="{{ old('shipping_name', auth()->user()->name) }}" 
                                    placeholder="Nama Lengkap">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon (WhatsApp)</label>
                                <input type="text" name="shipping_phone" 
                                    class="form-control @error('shipping_phone') is-invalid @enderror"
                                    value="{{ old('shipping_phone') }}" 
                                    placeholder="081234567xxx">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control" 
                                    value="{{ old('postal_code') }}" placeholder="12345">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="shipping_address" rows="3"
                                    class="form-control @error('shipping_address') is-invalid @enderror" 
                                    placeholder="Contoh: Jl. Anggrek No. 12, RT 01/RW 02, Kec. Sukajadi">{{ old('shipping_address') }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-control" 
                                    placeholder="Warna cadangan atau instruksi kurir">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="payment-method-box shadow-sm border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold mb-1"><i class="bi bi-shield-check me-2"></i>Pembayaran Aman</h6>
                        <p class="mb-0 small opacity-75">Tersedia VA, E-Wallet, & Kartu via Midtrans</p>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-credit-card-2-front"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: RINGKASAN PESANAN --}}
        <div class="col-lg-5">
            <div class="card checkout-card border-0 sticky-summary">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Detail Pesanan</h5>
                    
                    <div class="order-summary-list mb-4">
                        @foreach($cartItems as $item)
                            <div class="product-mini-card">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->product?->image_url }}" 
                                         class="rounded-3 me-3 border bg-white" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="fw-bold text-dark text-truncate mb-0 small">
                                            {{ $item->product?->name }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <small class="text-muted">{{ $item->quantity }} x</small>
                                            <span class="fw-bold text-biru-steel small">
                                                Rp {{ number_format(($item->product?->price ?? 0) * $item->quantity, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Harga ({{ $cartItems->sum('quantity') }} Item)</span>
                            <span class="small fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Biaya Pengiriman</span>
                            <span class="small fw-bold text-success">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="my-4 py-3 border-top border-bottom border-dashed text-center" style="border-top-style: dashed !important; border-bottom-style: dashed !important;">
                            <span class="text-muted d-block small mb-1">Total Tagihan</span>
                            <span class="total-amount">Rp {{ number_format($subtotal + $shippingCost, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" form="checkout-form" class="btn btn-pay btn-lg w-100 text-white">
                            Selesaikan & Bayar <i class="bi bi-shield-lock ms-2"></i>
                        </button>

                        <div class="mt-4 text-center">
                            <p class="text-muted" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i> Dengan menekan tombol, Anda menyetujui syarat & ketentuan yang berlaku di GadgetMurah.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
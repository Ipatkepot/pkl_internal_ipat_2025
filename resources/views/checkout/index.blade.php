@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<style>
    /* Global Background & Font */
    body { background-color: #f0f3f7; font-family: 'Inter', -apple-system, sans-serif; }
    
    /* Card Styles */
    .checkout-card { border-radius: 12px; border: none; }
    .product-img-mini { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    
    /* Form Styles */
    .form-label { font-size: 13px; font-weight: 700; color: #6d7588; }
    .form-control { border-radius: 8px; border: 1px solid #e5e7eb; padding: 10px 12px; font-size: 14px; }
    
    /* Focus State: Biru Steel */
    .form-control:focus { 
        box-shadow: 0 0 0 0.2rem rgba(59, 97, 129, 0.1); 
        border-color: #3B6181; 
    }
    
    /* Text & Price Colors */
    .summary-item { font-size: 14px; color: #31353b; }
    .total-price { font-size: 18px; color: #3B6181; font-weight: 800; }
    .text-biru-steel { color: #3B6181 !important; }
    
    /* Payment Button: Biru Steel */
    .btn-pay { 
        background-color: #3B6181; 
        border: none; 
        font-weight: 700; 
        padding: 15px; 
        border-radius: 12px; 
        transition: 0.3s;
        color: white;
    }
    .btn-pay:hover { 
        background-color: #2d4a63; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 97, 129, 0.2);
        color: white;
    }

    /* Custom Scrollbar for Summary */
    .order-summary-list::-webkit-scrollbar { width: 6px; }
    .order-summary-list::-webkit-scrollbar-track { background: #f1f1f1; }
    .order-summary-list::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

    .sticky-summary {
        position: sticky;
        top: 20px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h4 class="fw-bold mb-4 text-dark">Checkout</h4>

            {{-- ALERT PESAN ERROR/SUKSES --}}
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                {{-- FORM DATA PENGIRIMAN (KOLOM KIRI) --}}
                <div class="col-lg-7">
                    <div class="card checkout-card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4 d-flex align-items-center">
                                <i class="bi bi-geo-alt me-2 text-biru-steel"></i> Alamat Pengiriman
                            </h6>
                            
                            {{-- FORM ACTION --}}
                            <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                                @csrf

                                <div class="mb-3">
                                    <label for="shipping_name" class="form-label">Nama Penerima</label>
                                    <input type="text" name="shipping_name" id="shipping_name"
                                        class="form-control @error('shipping_name') is-invalid @enderror"
                                        value="{{ old('shipping_name', auth()->user()->name) }}" 
                                        placeholder="Contoh: Budi Santoso" required>
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-7 mb-3">
                                        <label for="shipping_phone" class="form-label">Nomor Telepon</label>
                                        <input type="text" name="shipping_phone" id="shipping_phone"
                                            class="form-control @error('shipping_phone') is-invalid @enderror"
                                            value="{{ old('shipping_phone') }}" 
                                            placeholder="Contoh: 0812xxx" required>
                                        @error('shipping_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="postal_code" class="form-label">Kode Pos</label>
                                        <input type="text" name="postal_code" id="postal_code" 
                                            class="form-control" value="{{ old('postal_code') }}" 
                                            placeholder="12345">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Alamat Lengkap</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3"
                                        class="form-control @error('shipping_address') is-invalid @enderror" 
                                        placeholder="Nama jalan, nomor rumah, RT/RW, Kecamatan" 
                                        required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="form-label">Catatan untuk Penjual (Opsional)</label>
                                    <textarea name="notes" id="notes" rows="2" class="form-control" 
                                        placeholder="Warna cadangan, posisi rumah, dll">{{ old('notes') }}</textarea>
                                </div>

                                <div class="p-3 bg-light rounded-3 mb-1 border-start border-4 border-biru-steel">
                                    <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                                    <span class="fw-bold text-biru-steel">
                                        <i class="bi bi-wallet2 me-2"></i> Midtrans (Virtual Account, E-Wallet, Kartu)
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- RINGKASAN PESANAN (KOLOM KANAN) --}}
                <div class="col-lg-5">
                    <div class="card checkout-card shadow-sm sticky-summary border-0">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-4 d-flex align-items-center">
                                <i class="bi bi-bag-check me-2 text-biru-steel"></i> Ringkasan Pesanan
                            </h6>
                            
                            <div class="order-summary-list mb-4" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                                @foreach($cartItems as $item)
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $item->product?->image_url }}" 
                                             class="product-img-mini me-3 border" 
                                             onerror="this.src='https://placehold.co/50x50?text=No+Img'">
                                        <div class="flex-grow-1">
                                            <div class="summary-item fw-bold text-truncate" style="max-width: 150px;">
                                                {{ $item->product?->name ?? 'Produk Tidak Tersedia' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $item->quantity }} x Rp {{ number_format($item->product?->price ?? 0, 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <div class="summary-item fw-bold text-dark text-nowrap">
                                            Rp {{ number_format(($item->product?->price ?? 0) * $item->quantity, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="my-3" style="border-style: dashed;">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Harga ({{ $cartItems->sum('quantity') }} barang)</span>
                                <span class="small fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Ongkos Kirim</span>
                                <span class="small fw-bold">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <span class="fw-bold fs-5 text-dark">Total Tagihan</span>
                                <span class="total-price">Rp {{ number_format($subtotal + $shippingCost, 0, ',', '.') }}</span>
                            </div>

                            {{-- TOMBOL SUBMIT (Terhubung ke ID form di kolom kiri) --}}
                            <button type="submit" form="checkout-form" class="btn btn-pay w-100 shadow-sm d-flex justify-content-center align-items-center mt-4">
                                <span>Bayar Sekarang</span>
                                <i class="bi bi-arrow-right-short fs-4 ms-1"></i>
                            </button>

                            <div class="mt-3 text-center">
                                <a href="{{ route('cart.index') }}" class="text-muted small text-decoration-none">
                                    <i class="bi bi-chevron-left"></i> Kembali ke Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
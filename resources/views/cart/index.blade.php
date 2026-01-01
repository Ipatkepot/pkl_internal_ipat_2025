@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<style>
    body { background-color: #f0f3f7; }
    .cart-card { border-radius: 12px; border: none; }
    .table thead th { 
        background-color: #f8f9fa; 
        font-size: 13px; 
        color: #6d7588; 
        border-bottom: 1px solid #eee;
    }
    .product-name { 
        font-size: 14px; 
        font-weight: 700; 
        color: #31353b; 
        transition: color 0.2s;
    }
    /* Warna Hover diubah ke Biru Steel */
    .product-name:hover { color: #3B6181; }

    .quantity-input { 
        max-width: 70px; 
        border-radius: 8px; 
        text-align: center; 
        font-weight: 600;
    }

    .summary-card { 
        border-radius: 12px; 
        border: none; 
        position: sticky; 
        top: 20px;
    }

    /* Tombol Checkout diubah ke Biru Steel */
    .btn-checkout { 
        background-color: #3B6181; 
        border: none; 
        font-weight: 700; 
        padding: 12px;
        border-radius: 10px;
        color: white;
    }
    .btn-checkout:hover { 
        background-color: #2d4a63; 
        color: white;
    }

    /* Warna teks sukses (Diskon) diubah ke Biru Steel */
    .text-success-custom {
        color: #3B6181 !important;
    }

    .trash-icon { color: #9fa6b0; cursor: pointer; transition: 0.2s; }
    .trash-icon:hover { color: #ff5c84; }

    /* Fokus Input Jumlah */
    .quantity-input:focus {
        border-color: #3B6181;
        box-shadow: 0 0 0 0.2rem rgba(59, 97, 129, 0.15);
    }
</style>

<div class="container py-5">
    <h4 class="fw-bold mb-4">Keranjang Belanja</h4>

    @if($cart && $cart->items->count())
        <div class="row g-4">
            {{-- DAFTAR ITEM --}}
            <div class="col-lg-8">
                <div class="card cart-card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        @php
                                            $itemPrice = $item->product->price; 
                                            $itemSubtotal = $itemPrice * $item->quantity;
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center py-2">
                                                    <img src="{{ $item->product->image_url }}" 
                                                         class="rounded-3 me-3" width="70" height="70" 
                                                         style="object-fit: cover; border: 1px solid #eee;">
                                                    <div>
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}" class="product-name text-decoration-none d-block mb-1">
                                                            {{ Str::limit($item->product->name, 50) }}
                                                        </a>
                                                        <span class="text-muted small">Stok: {{ $item->product->stock }}</span>
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-1">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn p-0 border-0 small text-danger trash-icon" style="font-size: 12px;" onclick="return confirm('Hapus barang ini?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold">
                                                Rp {{ number_format($itemPrice, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex justify-content-center">
                                                    @csrf @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                           min="1" max="{{ $item->product->stock }}" 
                                                           class="form-control form-control-sm quantity-input shadow-sm"
                                                           onchange="this.form.submit()">
                                                </form>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark">
                                                Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="col-lg-4">
                <div class="card summary-card shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Belanja</h6>
                        
                        @php
                            $totalQuantity = $cart->items->sum('quantity');
                            $totalHarga = $cart->items->sum(function($item) {
                                return $item->product->price * $item->quantity;
                            });
                        @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga ({{ $totalQuantity }} barang)</span>
                            <span class="text-dark">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-success-custom">- Rp 0</span>
                        </div>
                        <hr class="my-3" style="border-style: dashed;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Total Harga</span>
                            <span class="fw-bold fs-5 text-dark">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        {{-- Tombol Beli Utama --}}
                        <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100 mb-2">
                            Beli ({{ $totalQuantity }})
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 border-0 fw-bold small">
                            Kembali Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- TAMPILAN KERANJANG KOSONG --}}
        <div class="card cart-card shadow-sm py-5">
            <div class="card-body text-center">
                <img src="images/logo2.png" width="100" class="mb-4 opacity-75">
                <h5 class="fw-bold">Wah, keranjang belanjamu kosong</h5>
                <p class="text-muted">Yuk, isi dengan barang-barang impianmu!</p>
                {{-- Tombol Mulai Belanja diubah ke Biru Steel --}}
                <a href="{{ route('catalog.index') }}" class="btn px-5 fw-bold" style="background-color: #3B6181; color: white; border:none; border-radius: 10px;">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
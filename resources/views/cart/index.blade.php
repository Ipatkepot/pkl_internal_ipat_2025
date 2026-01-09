@extends('layouts.app')

@section('title', 'GadgetMurah - Cart')

@section('content')
<style>
    body { background-color: #f0f3f7; font-family: 'Plus Jakarta Sans', sans-serif; }
    .cart-card { border-radius: 20px; border: none; overflow: hidden; }
    .table thead th { 
        background-color: #f8fafc; 
        font-size: 13px; 
        color: #64748b; 
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .product-name { 
        font-size: 15px; 
        font-weight: 700; 
        color: #1a1d23; 
        transition: color 0.2s;
    }
    .product-name:hover { color: #3B6181; }

    .quantity-input { 
        max-width: 70px; 
        border-radius: 10px; 
        text-align: center; 
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    .summary-card { 
        border-radius: 20px; 
        border: none; 
        position: sticky; 
        top: 100px; /* Jaga jarak dari navbar */
    }

    .btn-checkout { 
        background-color: #3B6181; 
        border: none; 
        font-weight: 700; 
        padding: 14px;
        border-radius: 12px;
        color: white;
        transition: all 0.3s;
    }
    .btn-checkout:hover { 
        background-color: #2d4a63; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 97, 129, 0.3);
        color: white;
    }

    .trash-icon-btn {
        color: #94a3b8;
        transition: 0.2s;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }
    .trash-icon-btn:hover { color: #ef4444; }

    .alert-custom {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h4 class="fw-extrabold mb-0">Keranjang Belanja</h4>
        <span class="badge ms-3 px-3 py-2 rounded-pill" style="background: #e8eff5; color: #3B6181;">
            {{ $cart && $cart->items ? $cart->items->count() : 0 }} Produk
        </span>
    </div>

    {{-- NOTIFIKASI SUKSES/ERROR (Hanya muncul jika ada session) --}}
    @if(session('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- LOGIKA UTAMA: CEK APAKAH ADA ITEM --}}
    @if($cart && $cart->items->count() > 0)
        <div class="row g-4">
            {{-- DAFTAR ITEM --}}
            <div class="col-lg-8">
                <div class="card cart-card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Produk</th>
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
                                                <div class="d-flex align-items-center py-3">
                                                    <img src="{{ $item->product->image_url }}" 
                                                         class="rounded-4 me-3 shadow-sm" width="85" height="85" 
                                                         style="object-fit: cover; border: 1px solid #f1f5f9;">
                                                    <div>
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}" class="product-name text-decoration-none d-block mb-1">
                                                            {{ Str::limit($item->product->name, 45) }}
                                                        </a>
                                                        <p class="text-muted small mb-2">Stok Tersedia: <span class="fw-bold">{{ $item->product->stock }}</span></p>
                                                        
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn p-0 trash-icon-btn" onclick="return confirm('Hapus barang ini dari keranjang?')">
                                                                <i class="bi bi-trash3 me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold text-secondary">
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
                                            <td class="text-end pe-4 fw-bold text-dark fs-6">
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

            {{-- RINGKASAN BELANJA --}}
            <div class="col-lg-4">
                <div class="card summary-card shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-4">Ringkasan Belanja</h6>
                        
                        @php
                            $totalQuantity = $cart->items->sum('quantity');
                            $totalHarga = $cart->items->sum(function($item) {
                                return $item->product->price * $item->quantity;
                            });
                        @endphp

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Harga ({{ $totalQuantity }} barang)</span>
                            <span class="fw-semibold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-success fw-bold">- Rp 0</span>
                        </div>
                        
                        <hr class="my-4" style="border-top: 2px dashed #f1f5f9;">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Total Harga</span>
                            <span class="fw-extrabold fs-4" style="color: #3B6181;">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-checkout w-100 mb-3 shadow-sm">
                            Beli Sekarang ({{ $totalQuantity }})
                        </a>
                        
                        <a href="{{ route('catalog.index') }}" class="btn btn-light w-100 py-2 fw-bold text-muted rounded-3" style="font-size: 14px;">
                            <i class="bi bi-arrow-left me-2"></i>Tambah Barang Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- TAMPILAN JIKA KERANJANG BENAR-BENAR KOSONG --}}
        <div class="card cart-card shadow-sm py-5 border-0">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <img src="{{ asset('images/empty-cart.png') }}" width="180" class="opacity-50" onerror="this.src='https://cdn-icons-png.flaticon.com/512/11329/11329060.png'">
                </div>
                <h5 class="fw-bold mt-3">Wah, keranjang belanjamu kosong</h5>
                <p class="text-muted mb-4">Yuk, cari barang impianmu dan penuhi keranjang ini!</p>
                <a href="{{ route('catalog.index') }}" class="btn px-5 py-3 fw-bold shadow-sm" style="background-color: #3B6181; color: white; border-radius: 12px;">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
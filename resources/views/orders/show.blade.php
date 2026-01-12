@extends('layouts.app')

@section('title', 'Pesanan #' . $order->order_number . ' - GadgetMurah')

@section('content')
<style>
    :root {
        --biru-steel: #3B6181;
        --biru-steel-soft: #f0f4f8;
        --biru-steel-hover: #2d4a63;
        --success-glow: rgba(34, 197, 94, 0.15);
    }

    body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Glassmorphism Effect */
    .order-card { 
        border-radius: 30px; 
        border: 1px solid rgba(255, 255, 255, 0.8); 
        background: #ffffff;
        box-shadow: 0 20px 50px rgba(59, 97, 129, 0.05);
        overflow: hidden;
    }

    /* Modern Progress Tracker */
    .status-tracker { display: flex; justify-content: space-between; position: relative; margin: 30px 0; }
    .status-step { text-align: center; position: relative; z-index: 2; flex: 1; }
    
    .status-icon { 
        width: 60px; height: 60px; border-radius: 20px; 
        background: #f1f5f9; color: #94a3b8; 
        display: inline-flex; align-items: center; justify-content: center; 
        margin-bottom: 12px; transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); 
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .status-step.active .status-icon { 
        background: var(--biru-steel); 
        color: white; 
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 12px 25px rgba(59, 97, 129, 0.3); 
    }

    .status-step.completed .status-icon {
        background: #22c55e;
        color: white;
    }
    
    .status-text { font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; }
    .status-step.active .status-text { color: var(--biru-steel); }
    
    .line-tracker { position: absolute; top: 30px; left: 10%; right: 10%; height: 6px; background: #f1f5f9; z-index: 1; border-radius: 20px; }
    .line-fill { height: 100%; background: linear-gradient(90deg, var(--biru-steel) 0%, #60a5fa 100%); transition: 1.5s cubic-bezier(0.65, 0, 0.35, 1); border-radius: 20px; box-shadow: 0 0 15px rgba(96, 165, 250, 0.4); }
    
    @php
        $progress = 0;
        if($order->status == 'pending') $progress = 5;
        elseif($order->status == 'processing') $progress = 35;
        elseif($order->status == 'shipped') $progress = 70;
        elseif($order->status == 'delivered') $progress = 100;
    @endphp
    .line-fill { width: {{ $progress }}%; }

    /* Product Section */
    .product-row { transition: 0.3s; border-radius: 16px; margin-bottom: 10px; }
    .product-row:hover { background-color: #f8fafc; }
    .product-img { width: 85px; height: 85px; object-fit: cover; border-radius: 20px; border: 2px solid #fff; box-shadow: 0 8px 20px rgba(0,0,0,0.06); }

    /* Summary Card */
    .summary-box { background: #fafbfc; border-radius: 24px; padding: 30px; border: 1px solid #f1f5f9; }
    .price-highlight { font-size: 1.75rem; font-weight: 900; color: var(--biru-steel); letter-spacing: -1px; }

    /* Payment Button */
    .btn-pay-now {
        background: linear-gradient(135deg, var(--biru-steel) 0%, #1e293b 100%);
        color: white; border: none; padding: 20px; border-radius: 20px;
        font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        transition: 0.4s; box-shadow: 0 15px 35px rgba(30, 41, 59, 0.2);
    }
    .btn-pay-now:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(30, 41, 59, 0.3); color: white; }

    .nav-pill-custom {
        background: white; padding: 8px 20px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        font-weight: 700; color: #64748b; text-decoration: none; transition: 0.3s;
    }
    .nav-pill-custom:hover { color: var(--biru-steel); background: var(--biru-steel-soft); }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">

            {{-- Breadcrumb & Title --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                <div>
                    <span class="badge bg-biru-soft text-biru-steel px-3 py-2 rounded-pill mb-2 fw-bold">Detail Transaksi</span>
                    <h2 class="fw-900 mb-0" style="font-weight: 900; letter-spacing: -1.5px; font-size: 2.5rem;">#{{ $order->order_number }}</h2>
                </div>
                <a href="{{ route('orders.index') }}" class="nav-pill-custom align-self-start">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i> Riwayat Pesanan
                </a>
            </div>

            <div class="card order-card">
                {{-- Status Section --}}
                <div class="card-body p-4 p-md-5 bg-white border-bottom shadow-sm">
                    @if($order->status == 'cancelled')
                        <div class="text-center py-4">
                            <div class="bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center rounded-4 mb-3" style="width: 90px; height: 90px;">
                                <i class="bi bi-x-circle-fill fs-1"></i>
                            </div>
                            <h3 class="fw-800">Pesanan Dibatalkan</h3>
                            <p class="text-muted mx-auto" style="max-width: 400px;">Maaf, pesanan Anda telah dibatalkan. Hubungi Customer Service jika Anda merasa ini adalah kesalahan.</p>
                        </div>
                    @else
                        <div class="status-tracker">
                            <div class="line-tracker"><div class="line-fill"></div></div>
                            
                            {{-- Step 1 --}}
                            <div class="status-step {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="status-icon"><i class="bi bi-wallet2 fs-4"></i></div>
                                <div class="status-text d-none d-md-block">Pembayaran</div>
                            </div>
                            
                            {{-- Step 2 --}}
                            <div class="status-step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="status-icon"><i class="bi bi-box-seam-fill fs-4"></i></div>
                                <div class="status-text d-none d-md-block">Diproses</div>
                            </div>
                            
                            {{-- Step 3 --}}
                            <div class="status-step {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="status-icon"><i class="bi bi-truck fs-4"></i></div>
                                <div class="status-text d-none d-md-block">Dikirim</div>
                            </div>
                            
                            {{-- Step 4 --}}
                            <div class="status-step {{ $order->status == 'delivered' ? 'active' : '' }}">
                                <div class="status-icon"><i class="bi bi-check-circle-fill fs-4"></i></div>
                                <div class="status-text d-none d-md-block">Selesai</div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Order Main Content --}}
                <div class="card-body p-4 p-md-5">
                    <div class="row g-5">
                        {{-- Left Column: Items --}}
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center mb-4">
                                <h5 class="fw-800 mb-0">Rincian Belanja</h5>
                                <span class="ms-3 badge rounded-pill bg-light text-muted border px-3">{{ $order->items->count() }} Produk</span>
                            </div>
                            
                            <div class="item-list">
                                @foreach($order->items as $item)
                                <div class="product-row p-3 d-flex align-items-center">
                                    <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100?text=Gadget' }}" 
                                         class="product-img me-4 shadow-sm">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $item->product_name }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted small">{{ $item->quantity }}x</span>
                                            <span class="fw-bold text-biru-steel">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <span class="fw-900 text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Payment Action for Pending --}}
                            @if(isset($snapToken) && $order->status === 'pending')
                            <div class="mt-5 p-4 rounded-5 text-center" style="background: linear-gradient(135deg, #fffcf6 0%, #fff5e6 100%); border: 2px dashed #ffe8cc;">
                                <div class="mb-3">
                                    <i class="bi bi-clock-history text-warning fs-1"></i>
                                </div>
                                <h5 class="fw-bold">Selesaikan Pembayaran</h5>
                                <p class="text-muted small mb-4 px-lg-5">Klik tombol di bawah untuk membuka gerbang pembayaran aman Midtrans.</p>
                                <button id="pay-button" class="btn btn-pay-now w-100">
                                    Bayar Sekarang <i class="bi bi-shield-lock-fill ms-2"></i>
                                </button>
                            </div>
                            @endif
                        </div>

                        {{-- Right Column: Details & Summary --}}
                        <div class="col-lg-5">
                            {{-- Shipping Address --}}
                            <div class="summary-box mb-4 shadow-sm">
                                <h6 class="fw-800 mb-3 d-flex justify-content-between">
                                    <span>Alamat Pengiriman</span>
                                    <i class="bi bi-geo-alt-fill text-biru-steel"></i>
                                </h6>
                                <div class="bg-white p-3 rounded-4 border">
                                    <p class="mb-1 fw-bold text-dark">{{ $order->shipping_name }}</p>
                                    <p class="mb-2 text-muted small"><i class="bi bi-phone me-1"></i> {{ $order->shipping_phone }}</p>
                                    <p class="mb-0 text-muted small lh-base" style="font-size: 13px;">{{ $order->shipping_address }}</p>
                                </div>
                            </div>

                            {{-- Payment Summary --}}
                            <div class="summary-box shadow-sm" style="background: white; border: 2px solid #f1f5f9;">
                                <h6 class="fw-800 mb-4 text-uppercase letter-spacing-1">Ringkasan Tagihan</h6>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Subtotal Produk</span>
                                    <span class="fw-bold">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-4 pb-4 border-bottom border-dashed">
                                    <span class="text-muted">Ongkos Kirim</span>
                                    <span class="fw-bold text-success">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold text-dark d-block">Total Pembayaran</span>
                                        <small class="text-muted" style="font-size: 10px;">Sudah termasuk PPN</small>
                                    </div>
                                    <h3 class="price-highlight mb-0">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
                                </div>
                            </div>

                            <div class="mt-4 px-3">
                                <div class="d-flex align-items-center justify-content-center gap-2 text-muted small fw-bold">
                                    <i class="bi bi-calendar3"></i>
                                    Waktu Pesan: {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($snapToken))
    @push('scripts')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script type="text/javascript">
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function() {
                    payButton.disabled = true;
                    payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function(result) { window.location.href = '{{ route("orders.index") }}?status=success'; },
                        onPending: function(result) { window.location.href = '{{ route("orders.show", $order) }}'; },
                        onError: function(result) { 
                            alert('Terjadi kesalahan pembayaran.'); 
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang <i class="bi bi-shield-lock-fill ms-2"></i>';
                        },
                        onClose: function() { 
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang <i class="bi bi-shield-lock-fill ms-2"></i>';
                        }
                    });
                });
            }
        </script>
    @endpush
@endif
@endsection
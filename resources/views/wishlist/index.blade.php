{{-- resources/views/wishlist/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1 class="h3 fw-bold mb-0">
            <i class="bi bi-heart-fill text-danger me-3"></i>
            Wishlist Saya
        </h1>
        <span class="text-muted fs-5">{{ $products->total() }} produk</span>
    </div>

    @if($products->count())
        {{-- Tombol Hapus Terpilih --}}
        <div class="mb-4 d-none" id="bulkDeleteSection">
            <button type="button" onclick="bulkRemoveFromWishlist()" class="btn btn-danger rounded-pill px-4 shadow-sm">
                <i class="bi bi-trash me-2"></i>
                Hapus yang Dipilih (<span id="selectedCount">0</span>)
            </button>
            <button type="button" onclick="deselectAll()" class="btn btn-outline-secondary rounded-pill ms-3">
                Batal
            </button>
        </div>

        <form id="wishlistForm">
            @csrf
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift transition-all">
                            {{-- Checkbox Hapus (kiri atas) --}}
                            <div class="position-absolute top-0 start-0 m-3 z-3">
                                <input type="checkbox"
                                       name="product_ids[]"
                                       value="{{ $product->id }}"
                                       class="form-check-input wishlist-checkbox rounded shadow-sm"
                                       style="width: 22px; height: 22px; cursor: pointer;">
                            </div>

                            {{-- Tombol Hapus Tunggal: HATI PATAH 💔 --}}
                            <button type="button"
                                    onclick="removeSingleFromWishlist({{ $product->id }})"
                                    class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle z-3 shadow-sm remove-heart-btn"
                                    style="width: 42px; height: 42px;"
                                    title="Hapus dari wishlist">
                                <i class="bi bi-heartbreak fs-4 text-muted"></i>
                            </button>

                            {{-- Gambar Produk --}}
                            <a href="{{ route('catalog.show', $product->slug) }}" class="text-decoration-none">
                                <div class="ratio ratio-1x1 bg-light">
                                    <img src="{{ $product->image_url }}"
                                         class="card-img-top object-fit-cover"
                                         alt="{{ $product->name }}"
                                         loading="lazy">
                                </div>
                            </a>

                            {{-- Card Body --}}
                            <div class="card-body d-flex flex-column p-4">
                                <h6 class="card-title mb-3">
                                    <a href="{{ route('catalog.show', $product->slug) }}"
                                       class="text-dark text-decoration-none stretched-link line-clamp-2 fw-semibold">
                                        {{ $product->name }}
                                    </a>
                                </h6>

                                <div class="mt-auto">
                                    <div class="d-flex align-items-end gap-2 mb-3">
                                        <div class="fw-bold text-primary fs-4">
                                            {{ $product->formatted_price }}
                                        </div>
                                        @if($product->has_discount)
                                            <del class="text-muted small mb-1">{{ $product->formatted_original_price }}</del>
                                        @endif
                                    </div>

                                    @if($product->stock == 0)
                                        <span class="badge bg-danger rounded-pill mb-3">Stok Habis</span>
                                    @elseif($product->stock <= 5)
                                        <small class="text-warning d-block mb-3">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            Stok tinggal {{ $product->stock }}
                                        </small>
                                    @endif

                                    {{-- Tombol Lihat Detail & Tambah ke Keranjang --}}
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('catalog.show', $product->slug) }}"
                                           class="btn btn-outline-primary rounded-pill py-3 fw-medium shadow-sm border-2">
                                            <i class="bi bi-eye me-2"></i>
                                            Lihat Detail
                                        </a>

                                        <button type="button"
                                                onclick="addToCart({{ $product->id }}, 1)"
                                                class="btn btn-primary rounded-pill py-3 fw-medium shadow-lg"
                                                @if($product->stock == 0) disabled @endif>
                                            <i class="bi bi-cart-plus me-2"></i>
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5 my-5">
            <div class="mb-5">
                <i class="bi bi-heart text-secondary opacity-25" style="font-size: 8rem;"></i>
            </div>
            <h3 class="h4 fw-bold text-dark mb-3">Wishlist Kamu Masih Kosong</h3>
            <p class="text-muted mb-5 fs-5 max-w-500 mx-auto">
                Temukan produk impianmu dan simpan di sini agar tidak lupa saat ingin membeli nanti.
            </p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg fw-medium">
                <i class="bi bi-search me-3"></i>
                Jelajahi Produk Sekarang
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .hover-lift { transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .hover-lift:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .max-w-500 { max-width: 500px; }
    .card-img-top { transition: transform 0.5s ease; }
    .card:hover .card-img-top { transform: scale(1.08); }

    /* Tombol Hati Patah 💔 */
    .remove-heart-btn {
        transition: all 0.3s ease;
    }
    .remove-heart-btn:hover {
        background-color: #fee2e2 !important;
        transform: scale(1.15) rotate(5deg);
    }
    .remove-heart-btn:hover i {
        color: #dc3545 !important;
    }

    /* Checkbox */
    .wishlist-checkbox {
        transform: scale(1.3);
        border: 2px solid #ddd;
    }
    .wishlist-checkbox:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    // Update UI bulk delete
    document.querySelectorAll('.wishlist-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteUI);
    });

    function updateBulkDeleteUI() {
        const checked = document.querySelectorAll('.wishlist-checkbox:checked');
        const count = checked.length;
        const section = document.getElementById('bulkDeleteSection');
        const counter = document.getElementById('selectedCount');

        if (count > 0) {
            section.classList.remove('d-none');
            counter.textContent = count;
        } else {
            section.classList.add('d-none');
        }
    }

    // Hapus satu
    function removeSingleFromWishlist(productId) {
        if (!confirm('Yakin ingin menghapus produk ini dari wishlist? 💔')) return;

        fetch(`/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus');
            }
        });
    }

    // Hapus banyak
    function bulkRemoveFromWishlist() {
        if (!confirm('Hapus semua produk yang dipilih dari wishlist?')) return;

        const form = document.getElementById('wishlistForm');
        const formData = new FormData(form);

        fetch('/wishlist/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus');
            }
        });
    }

    function deselectAll() {
        document.querySelectorAll('.wishlist-checkbox').forEach(cb => cb.checked = false);
        updateBulkDeleteUI();
    }

    // Tambah ke Keranjang
    function addToCart(productId, quantity = 1) {
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success('Produk berhasil ditambahkan ke keranjang!');
                // Update badge keranjang di navbar (opsional)
                document.querySelector('.badge-cart').textContent = data.cart_count || '';
            } else {
                alert(data.message || 'Gagal menambahkan ke keranjang');
            }
        })
        .catch(() => alert('Terjadi kesalahan jaringan'));
    }
</script>
@endpush
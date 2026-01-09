@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">Daftar Produk</h2>
            <p class="text-muted small mb-0">Kelola stok dan informasi produk toko Anda.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary px-4 py-2 shadow-sm rounded-3">
            <i class="bi bi-plus-lg me-2"></i> Tambah Produk Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" 
                               placeholder="Cari nama produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select border-1">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-light border w-100 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Produk</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Kategori</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Harga</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Stok</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->primaryImage?->image_url ?? asset('img/no-image.png') }}" 
                                     class="rounded-3 object-fit-cover border" 
                                     style="width: 48px; height: 48px;">
                                <div class="ms-3">
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <div class="text-muted x-small">ID: #PROD-{{ $product->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-secondary">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center">
                            @if($product->stock <= 5)
                                <span class="badge bg-danger-subtle text-danger rounded-pill">Sisa {{ $product->stock }}</span>
                            @else
                                <span class="text-dark">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-circle-fill me-1 small"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                    <i class="bi bi-circle-fill me-1 small"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm rounded">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-white btn-sm border" title="Detail">
                                    <i class="bi bi-eye text-info"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-white btn-sm border" title="Edit">
                                    <i class="bi bi-pencil text-warning"></i>
                                </a>
                               <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                
                                <button type="submit" class="btn btn-white btn-sm border" title="Hapus">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="{{ asset('img/empty-state.svg') }}" alt="Empty" style="width: 120px;" class="mb-3 opacity-50">
                            <p class="text-muted">Belum ada produk yang ditambahkan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <p class="text-muted small mb-0">
            Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} produk
        </p>
        <div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    .table thead th { font-size: 0.7rem; letter-spacing: 0.05rem; }
    .btn-group .btn:hover { background-color: #f8f9fa; }
    .card { transition: transform 0.2s ease; }
    /* Menghilangkan border default Laravel pagination */
    .pagination { margin-bottom: 0; }
</style>
@endsection
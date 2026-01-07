{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Manajemen Kategori</h5>
                        <p class="text-muted small mb-0">Kelola kelompok produk untuk mempermudah pencarian.</p>
                    </div>
                    <button class="btn btn-primary px-3 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </button>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold">Detail Kategori</th>
                                    <th class="text-center py-3 fw-semibold">Jumlah Produk</th>
                                    <th class="text-center py-3 fw-semibold">Status</th>
                                    <th class="text-end pe-4 py-3 fw-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative me-3">
                                                    @if($category->image)
                                                        <img src="{{ url('storage/' . $category->image) }}" 
                                                             class="rounded-3 border object-fit-cover" 
                                                             width="50" height="50">
                                                    @else
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="bi bi-folder2-open text-muted h4 mb-0"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0">{{ $category->name }}</div>
                                                    <div class="text-muted x-small">
                                                        <i class="bi bi-link-45deg"></i> {{ $category->slug }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-primary border border-primary-subtle px-3 rounded-pill fw-medium">
                                                {{ $category->products_count }} Produk
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($category->is_active)
                                                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle-fill me-1 small"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-dash-circle-fill me-1 small"></i> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                                <button class="btn btn-white btn-sm border" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $category->id }}"
                                                        title="Edit Kategori">
                                                    <i class="bi bi-pencil-square text-warning"></i>
                                                </button>
                                                <button type="button" class="btn btn-white btn-sm border" 
                                                        onclick="confirmDelete('{{ $category->id }}')"
                                                        title="Hapus">
                                                    <i class="bi bi-trash3 text-danger"></i>
                                                </button>
                                            </div>
                                            {{-- Form Tersembunyi untuk Delete --}}
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="bi bi-collection text-muted display-1 opacity-25"></i>
                                            <p class="mt-3 text-muted">Belum ada kategori tersedia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($categories as $category)
    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="fw-bold">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Baru (Opsional)</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded-3 bg-light mb-2">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" width="50" height="50" class="rounded object-fit-cover shadow-sm">
                            @endif
                            <div class="small text-muted">Pilih file baru jika ingin mengganti gambar.</div>
                        </div>
                        <input type="file" name="image" class="form-control rounded-3">
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="switch{{ $category->id }}"
                               {{ $category->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="switch{{ $category->id }}">Status Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

{{-- CREATE MODAL --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold">Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="Contoh: Perabotan Rumah">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Gambar Cover</label>
                    <input type="file" name="image" class="form-control rounded-3 text-muted">
                    <div class="form-text small">Rekomendasi rasio 1:1 atau persegi.</div>
                </div>
                <div class="bg-light p-3 rounded-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="createSwitch" checked>
                        <label class="form-check-label fw-bold" for="createSwitch">Aktifkan Kategori</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .x-small { font-size: 0.75rem; }
    .object-fit-cover { object-fit: cover; }
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-secondary-subtle { background-color: #e2e3e5 !important; }
    .btn-white { background: #fff; border-color: #dee2e6; }
    .btn-white:hover { background: #f8f9fa; }
    .table thead th { border-top: none; }
</style>

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua produk terkait mungkin akan terdampak.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
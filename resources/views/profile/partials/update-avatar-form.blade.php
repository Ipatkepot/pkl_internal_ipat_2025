{{-- resources/views/profile/partials/update-avatar-form.blade.php --}}

<section>
    <header class="mb-4">
        <h5 class="fw-bold" style="color: var(--accent-steel)">
            <i class="bi bi-image me-2"></i>Visual Profil
        </h5>
        <p class="text-muted small">
            Sesuaikan identitas visual kamu dengan mengupload foto profil dan background.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- SOLUSI AMPUH: Hidden Input agar validasi Nama & Email tidak error --}}
        <input type="hidden" name="name" value="{{ $user->name }}">
        <input type="hidden" name="email" value="{{ $user->email }}">

        <div class="mb-4">
            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Background Profil</label>
            <div class="position-relative overflow-hidden rounded-4 border shadow-sm" style="height: 160px; background: #f8fafc;">
                <img id="banner-preview" 
                     src="{{ $user->banner ? asset('storage/' . $user->banner) : 'https://placehold.co/1200x400/3B6181/FFF?text=Tanpa+Background' }}" 
                     class="w-100 h-100 object-fit-cover">
                
                <div class="position-absolute bottom-0 end-0 m-2 d-flex gap-2">
                    @if ($user->banner)
                        <button type="button" onclick="deleteAsset('banner')" class="btn btn-danger btn-sm rounded-3 shadow">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                    <label for="banner-input" class="btn btn-light btn-sm rounded-3 shadow" style="cursor: pointer;">
                        <i class="bi bi-camera-fill me-1"></i> Ganti Banner
                    </label>
                </div>
                <input type="file" name="banner" id="banner-input" class="d-none" accept="image/*" onchange="previewFile(this, 'banner-preview')">
            </div>
            @error('banner') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Foto Profil</label>
            <div class="d-flex align-items-center gap-4">
                <div class="position-relative">
                    <img id="avatar-preview" src="{{ $user->avatar_url }}" 
                         class="rounded-circle border shadow-sm object-fit-cover" 
                         style="width: 100px; height: 100px; background: #fff;">
                    
                    @if ($user->avatar)
                        <button type="button" onclick="deleteAsset('avatar')" 
                                class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle border border-white"
                                style="width: 28px; height: 28px; padding: 0;">
                            &times;
                        </button>
                    @endif
                </div>

                <div class="flex-grow-1">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" 
                           class="form-control @error('avatar') is-invalid @enderror" 
                           onchange="previewFile(this, 'avatar-preview')">
                    <div class="form-text mt-1 italic" style="font-size: 0.75rem;">Format: JPG, PNG, WebP (Max 2MB).</div>
                    @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="pt-2 border-top text-end">
            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px; background: var(--accent-steel); border: none;">
                <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Visual
            </button>
        </div>
    </form>
</section>

{{-- Form Hidden untuk Delete --}}
<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="POST" class="d-none"> @csrf @method('DELETE') </form>
<form id="delete-banner-form" action="{{ route('profile.banner.destroy') }}" method="POST" class="d-none"> @csrf @method('DELETE') </form>

<script>
    function previewFile(input, previewId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function deleteAsset(type) {
        const msg = type === 'banner' ? 'Hapus background profil?' : 'Hapus foto profil?';
        if(confirm(msg)) {
            document.getElementById(`delete-${type}-form`).submit();
        }
    }
</script>
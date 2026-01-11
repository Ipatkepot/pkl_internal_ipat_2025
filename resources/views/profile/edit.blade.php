@extends('layouts.app')

@section('title', 'Pengaturan Profil - GadgetMurah')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.9);
        --glass-border: rgba(255, 255, 255, 0.6);
        --accent-steel: #3B6181; 
        --dark-steel: #2d4a63;
        --text-main: #1e293b;
    }

    body {
        background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
        background-attachment: fixed;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Back Button Styling */
    .btn-back-glass {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--glass-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        color: var(--accent-steel);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        margin-bottom: 25px;
    }

    .btn-back-glass:hover {
        background: var(--accent-steel);
        color: #fff;
        transform: translateX(-5px);
        box-shadow: 0 8px 20px rgba(59, 97, 129, 0.15);
    }

    .edit-container {
        max-width: 900px;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 28px;
        box-shadow: 0 10px 35px rgba(59, 97, 129, 0.05);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .card-header-custom {
        padding: 25px 30px 10px;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom h5 {
        font-weight: 800;
        color: var(--accent-steel);
        margin: 0;
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem;
    }

    .card-header-custom i {
        font-size: 1.4rem;
        color: var(--accent-steel);
    }

    .card-body-custom {
        padding: 10px 30px 30px;
    }

    /* Khusus Section Bahaya (Hapus Akun) */
    .danger-zone {
        border: 1px solid rgba(239, 68, 68, 0.2);
        background: rgba(255, 255, 255, 0.7);
    }

    .danger-header {
        color: #ef4444 !important;
    }

    .danger-header i {
        color: #ef4444 !important;
    }

    /* Mempercantik Input (Hanya jika partials menggunakan class standar bootstrap) */
    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus {
        border-color: var(--accent-steel);
        box-shadow: 0 0 0 3px rgba(59, 97, 129, 0.1);
    }
</style>

<div class="container py-5 edit-container">
    
    {{-- Tombol Kembali --}}
    <a href="javascript:history.back()" class="btn-back-glass">
        <i class="bi bi-arrow-left"></i> Kembali ke Profil
    </a>

    <div class="mb-5">
        <h2 class="fw-800" style="font-weight: 800; color: #1e293b; font-family: 'Outfit';">Pengaturan Akun</h2>
        <p class="text-muted">Kelola informasi profil, keamanan, dan preferensi akun Anda.</p>
    </div>

    {{-- 1. Update Foto Profil --}}
    <div class="glass-card mb-4">
        <div class="card-header-custom">
            <i class="bi bi-camera"></i>
            <h5>Foto Profil</h5>
        </div>
        <div class="card-body-custom">
            @include('profile.partials.update-avatar-form')
        </div>
    </div>

    {{-- 2. Informasi Profil --}}
    <div class="glass-card mb-4">
        <div class="card-header-custom">
            <i class="bi bi-person-lines-fill"></i>
            <h5>Informasi Identitas</h5>
        </div>
        <div class="card-body-custom">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- 3. Update Password --}}
    <div class="glass-card mb-4">
        <div class="card-header-custom">
            <i class="bi bi-shield-lock"></i>
            <h5>Keamanan Password</h5>
        </div>
        <div class="card-body-custom">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- 4. Hapus Akun (Danger Zone) --}}
    <div class="glass-card danger-zone">
        <div class="card-header-custom danger-header">
            <i class="bi bi-exclamation-octagon"></i>
            <h5>Zona Bahaya</h5>
        </div>
        <div class="card-body-custom">
            <p class="small text-muted mb-4">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
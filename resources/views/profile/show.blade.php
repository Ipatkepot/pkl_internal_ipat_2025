@extends('layouts.app')

@section('title', 'Profil Eksklusif - ' . ($user->name ?? 'User'))

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
    .back-nav {
        margin-bottom: 20px;
        display: inline-block;
    }

    .btn-back-glass {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(8px);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        color: var(--accent-steel);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-back-glass:hover {
        background: var(--accent-steel);
        color: #fff;
        transform: translateX(-5px);
        box-shadow: 0 4px 15px rgba(59, 97, 129, 0.2);
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(59, 97, 129, 0.05);
    }

    /* Banner Header */
    .profile-header-box {
        position: relative;
        background: #fff;
        border-radius: 30px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }

    .profile-banner {
        height: 220px;
        width: 100%;
        background: var(--accent-steel);
        position: relative;
        overflow: hidden;
    }

    .banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-info-row {
        padding: 0 40px 30px 40px;
        display: flex;
        align-items: flex-end;
        margin-top: -75px;
        position: relative;
        z-index: 2;
    }

    .avatar-container {
        position: relative;
        flex-shrink: 0;
    }

    .avatar-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 6px solid #fff;
        border-radius: 50%;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        background: #fff;
    }

    .profile-details {
        flex-grow: 1;
        padding-left: 25px;
        padding-bottom: 15px;
    }

    .profile-actions {
        padding-bottom: 20px;
    }

    /* Info Boxes */
    .info-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 2px;
    }

    .info-box-item {
        background: rgba(59, 97, 129, 0.04);
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 15px;
        border: 1px solid rgba(59, 97, 129, 0.05);
        transition: 0.3s;
    }

    .info-box-item:hover {
        background: rgba(59, 97, 129, 0.08);
        transform: translateX(5px);
    }

    /* Button Custom */
    .btn-edit-custom {
        background: var(--accent-steel);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(59, 97, 129, 0.2);
    }

    .btn-edit-custom:hover {
        background: var(--dark-steel);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 97, 129, 0.3);
    }

    /* Timeline Styling */
    .timeline-wrapper {
        padding-left: 10px;
    }

    .timeline-entry {
        position: relative;
        padding-left: 30px;
        padding-bottom: 25px;
        border-left: 2px solid #e2e8f0;
    }

    .timeline-entry::before {
        content: '';
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        background: #fff;
        border: 3px solid var(--accent-steel);
        border-radius: 50%;
    }

    .timeline-entry:last-child {
        border-left: 2px solid transparent;
        padding-bottom: 0;
    }

    @media (max-width: 768px) {
        .profile-info-row {
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: -60px;
            padding: 0 20px 20px 20px;
        }
        .profile-details {
            padding-left: 0;
            padding-top: 15px;
        }
        .avatar-img {
            width: 120px;
            height: 120px;
        }
    }
</style>

<div class="container py-4">
    
    {{-- Tombol Kembali --}}
    <div class="back-nav">
        <a href="javascript:history.back()" class="btn-back-glass">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Header Section --}}
    <div class="profile-header-box">
        <div class="profile-banner">
            @if(isset($user->banner))
                <img src="{{ asset('storage/' . $user->banner) }}" class="banner-img" alt="Banner">
            @else
                <div class="w-100 h-100" style="background: linear-gradient(135deg, #3B6181 0%, #2d4a63 100%);"></div>
            @endif
        </div>
        
        <div class="profile-info-row">
            <div class="avatar-container">
                @if(isset($user->avatar))
                    <img src="{{ $user->avatar_url }}" class="avatar-img" alt="{{ $user->name }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=200&background=3B6181&color=fff&bold=true" class="avatar-img" alt="Default Avatar">
                @endif
            </div>
            
            <div class="profile-details">
                <h2 class="fw-800 mb-1" style="font-weight: 800; color: #1e293b; font-family: 'Outfit';">{{ $user->name }}</h2>
                <p class="text-muted mb-0 small">
                    <i class="bi bi-calendar3 me-1"></i> Terdaftar sejak {{ $user->created_at->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="profile-actions">
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-edit-custom">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profil
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    {{-- Content Section --}}
    <div class="row g-4">
        {{-- Left Column: Info --}}
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="color: var(--accent-steel)">
                    <i class="bi bi-person-circle me-2"></i> Informasi Dasar
                </h5>
                
                <div class="info-box-item">
                    <span class="info-label">Email</span>
                    <div class="fw-600">{{ $user->email }}</div>
                </div>

                <div class="info-box-item">
                    <span class="info-label">Nomor Telepon</span>
                    <div class="fw-600 {{ $user->phone ? 'text-primary' : 'text-muted italic small' }}">
                        {{ $user->phone ?? 'Belum ada nomor telepon' }}
                    </div>
                </div>

                <div class="info-box-item">
                    <span class="info-label">Alamat Domisili</span>
                    <div class="small {{ $user->address ? 'text-dark' : 'text-muted italic' }}">
                        {{ $user->address ?? 'Alamat belum diatur.' }}
                    </div>
                </div>

                @if($user->role === 'admin')
                <div class="info-box-item border-warning" style="background: rgba(255, 193, 7, 0.05);">
                    <span class="info-label text-warning">Status Akun</span>
                    <div class="fw-bold text-warning"><i class="bi bi-patch-check-fill me-1"></i> Administrator</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Activity --}}
        <div class="col-lg-7">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="color: var(--accent-steel)">
                    <i class="bi bi-clock-history me-2"></i> Log Aktivitas
                </h5>

                <div class="timeline-wrapper">
                    {{-- Simulasi Pesanan --}}
                    @if(isset($user->orders) && $user->orders->count() > 0)
                        @foreach($user->orders->take(3) as $order)
                        <div class="timeline-entry">
                            <div class="fw-bold">Pemesanan #{{ $order->id }}</div>
                            <div class="text-muted small">Status: <span class="badge bg-light text-dark border">{{ ucfirst($order->status) }}</span> • {{ $order->created_at->diffForHumans() }}</div>
                        </div>
                        @endforeach
                    @endif

                    {{-- Info Update Profil --}}
                    @if($user->updated_at != $user->created_at)
                        <div class="timeline-entry">
                            <div class="fw-bold">Pembaruan Profil Visual</div>
                            <div class="text-muted small">Berhasil memperbarui informasi pada {{ $user->updated_at->translatedFormat('d M Y, H:i') }}</div>
                        </div>
                    @endif

                    {{-- Registration Info --}}
                    <div class="timeline-entry">
                        <div class="fw-bold text-primary">Bergabung dengan Komunitas</div>
                        <div class="text-muted small">Akun resmi diverifikasi pada {{ $user->created_at->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
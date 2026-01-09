@extends('layouts.app')

@section('title', 'GadgetMurah - Profil ' . ($user->name ?? 'User'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* Header Background */
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 160px;
        border-radius: 20px 20px 0 0;
    }

    /* Container Kartu Utama */
    .card-profile {
        border: 1px solid rgba(102, 126, 234, 0.2); /* Border kartu tipis berwarna biru muda */
        border-radius: 20px;
        overflow: hidden;
        background-color: #ffffff;
    }

    /* CUSTOM BORDER PROFILE IMAGE */
    .profile-img-wrapper {
        margin-top: -80px;
        position: relative;
        z-index: 2;
        display: inline-block;
    }

    .profile-img-container {
        padding: 6px; /* Jarak untuk border luar */
        background: linear-gradient(135deg, #667eea, #764ba2); /* Border gradasi luar */
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .profile-img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 4px solid #fff; /* Border putih dalam */
        border-radius: 50%;
        background-color: #fff;
    }

    /* Styling Teks dan Badge */
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #95a5a6;
    }
    .info-value {
        font-size: 1rem;
        color: #2c3e50;
    }

    .btn-edit {
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        transition: all 0.3s;
    }
    .btn-edit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(118, 75, 162, 0.3);
        color: white;
    }

    /* Custom Row Border */
    .info-row {
        border-bottom: 1px dashed #eee;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }
    .info-row:last-child {
        border-bottom: none;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            @if(isset($user))
                <div class="card card-profile shadow-lg">
                    <div class="profile-header"></div>
                    
                    <div class="card-body p-4 pt-0">
                        <div class="text-center mb-4">
                            {{-- Wrapper Border Custom --}}
                            <div class="profile-img-wrapper">
                                <div class="profile-img-container">
                                    @if(!empty($user->avatar))
                                        <img src="{{ $user->avatar_url }}" class="profile-img" alt="{{ $user->name }}">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&size=150&background=667eea&color=fff&font-size=0.35" 
                                             class="profile-img" alt="Default Avatar">
                                    @endif
                                </div>
                            </div>
                            
                            <h2 class="mt-3 fw-bold mb-1" style="color: #2d3436;">{{ $user->name }}</h2>
                            <div class="mb-4">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(102, 126, 234, 0.1); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.2);">
                                    <i class="bi bi-patch-check-fill me-1"></i> Member Premium
                                </span>
                            </div>

                            @auth
                                @if(auth()->id() === $user->id)
                                    <a href="{{ route('profile.edit') }}" class="btn btn-edit">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Profil Saya
                                    </a>
                                @endif
                            @endauth
                        </div>

                        {{-- Section Data --}}
                        <div class="card border-0 bg-light rounded-4 p-4 mt-2">
                            <div class="row">
                                {{-- Email --}}
                                <div class="col-md-6 info-row">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-3 shadow-sm me-3 text-primary">
                                            <i class="bi bi-envelope fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Alamat Email</div>
                                            <div class="info-value fw-bold">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bergabung --}}
                                <div class="col-md-6 info-row">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-3 shadow-sm me-3 text-primary">
                                            <i class="bi bi-calendar-check fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Member Sejak</div>
                                            <div class="info-value fw-bold">
                                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Role Admin --}}
                                @if($user->is_admin)
                                <div class="col-md-6 info-row">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-3 shadow-sm me-3 text-success">
                                            <i class="bi bi-shield-lock fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="info-label">Hak Akses</div>
                                            <div class="info-value text-success fw-bold">Administrator</div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger rounded-4 shadow-sm text-center">
                    <i class="bi bi-x-circle fs-1 d-block mb-2"></i>
                    Ups! Data user tidak ditemukan.
                </div>
            @endif

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="btn btn-link text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<style>
    :root {
        --biru-steel: #3B6181;
        --biru-steel-hover: #2d4a63;
    }

    body {
        background-color: #f4f7f6;
    }

    .register-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus {
        border-color: var(--biru-steel);
        box-shadow: 0 0 0 0.25 mil rem rgba(59, 97, 129, 0.25);
    }

    .btn-biru-steel {
        background-color: var(--biru-steel);
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .btn-biru-steel:hover {
        background-color: var(--biru-steel-hover);
        color: white;
        transform: translateY(-1px);
    }

    .btn-google {
        border: 1.5px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        color: #555;
        transition: 0.3s;
    }

    .btn-google:hover {
        background-color: #f8f9fa;
        border-color: #bbb;
    }

    .divider-text {
        position: relative;
        text-align: center;
        margin: 25px 0;
    }

    .divider-text::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
        z-index: 1;
    }

    .divider-text span {
        position: relative;
        background: #fff;
        padding: 0 15px;
        color: #888;
        font-size: 0.9rem;
        z-index: 2;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card register-card p-4">
                <div class="card-body">
                    {{-- Logo/Title --}}
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Daftar Akun</h3>
                        <p class="text-muted">Bergabunglah dengan kami dan mulai belanja produk impian Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-bold text-muted">Nama Lengkap</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required placeholder="Masukkan nama Anda" autofocus>
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-muted">Alamat Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required placeholder="contoh@email.com">
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label small fw-bold text-muted">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required placeholder="Minimal 8 karakter">
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label small fw-bold text-muted">Konfirmasi Password</label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required placeholder="Ulangi password">
                        </div>

                        {{-- Register Button --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-biru-steel shadow-sm">
                                Daftar Sekarang
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div class="divider-text">
                            <span>atau daftar dengan</span>
                        </div>

                        {{-- Google Button --}}
                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-google d-flex align-items-center justify-content-center gap-2">
                                <svg width="20" height="20" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Google
                            </a>
                        </div>

                        {{-- Footer Text --}}
                        <p class="text-center mb-0 small text-muted">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-biru-steel fw-bold text-decoration-none">Login di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
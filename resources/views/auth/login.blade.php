@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')

@section('content')
<style>
    :root {
        --biru-steel: #3B6181;
        --biru-steel-hover: #2d4a63;
    }

    body {
        background-color: #f4f7f6;
    }

    .login-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        background: #fff;
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus {
        border-color: var(--biru-steel);
        box-shadow: 0 0 0 0.25rem rgba(59, 97, 129, 0.1);
    }

    .btn-biru-steel {
        background-color: var(--biru-steel);
        color: white;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
        transition: 0.3s;
        border: none;
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
        background: white;
        text-decoration: none;
    }

    .btn-google:hover {
        background-color: #f8f9fa;
        border-color: #bbb;
        color: #333;
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
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">
                <div class="card-body">
                    {{-- Header --}}
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Selamat Datang 👋</h3>
                        <p class="text-muted small">Silakan masuk untuk melanjutkan belanja</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-bold text-muted">Alamat Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-2">
                            <label for="password" class="form-label small fw-bold text-muted">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required placeholder="Masukkan password">
                            @error('password')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Remember & Forgot --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-bold text-biru-steel">Lupa Password?</a>
                            @endif
                        </div>

                        {{-- Login Button --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-biru-steel shadow-sm">
                                Masuk
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div class="divider-text">
                            <span>atau masuk dengan</span>
                        </div>

                        {{-- Google Login --}}
                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.google') }}" class="btn btn-google d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-google"></i>
                                Login Dengan Google
                            </a>
                        </div>

                        <div class="d-grid mb-4">
                            <a href="{{ route('auth.github') }}" class="btn btn-google d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-github"></i>
                                Login Dengan GitHub
                            </a>
                        </div>

                        {{-- Footer --}}
                        <p class="text-center mb-0 small text-muted">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-biru-steel fw-bold text-decoration-none">Daftar Sekarang</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
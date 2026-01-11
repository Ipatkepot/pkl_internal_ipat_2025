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
                        <
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
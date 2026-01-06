@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 fw-bold">Pengaturan Profil</h2>
            {{-- 1. Avatar --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white fw-bold border-0 pt-3">Foto Profil</div>
                <div class="card-body">
                    @include('profile.partials.update-avatar-form')
                </div>
            </div>

            {{-- 2. Information --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white fw-bold border-0 pt-3">Informasi Profil</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 3. Password --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white fw-bold border-0 pt-3">Update Password</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 4. Delete Account --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold border-0">Hapus Akun</div>
                <div class="card-body border border-danger rounded-bottom">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
{{-- ================================================
     FILE: resources/views/partials/navbar.blade.php
     FUNGSI: Navbar Style Modern + Biru Steel Theme (Fixed Str Class)
     ================================================ --}}

<nav class="navbar navbar-expand-lg bg-white sticky-top tokopedia-navbar py-2 border-bottom">
    <div class="container-fluid px-lg-5 px-3 align-items-center">

        {{-- BRAND --}}
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}" style="color: #3B6181;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="36" height="36">
            <span class="fs-4 d-none d-sm-inline" style="letter-spacing: -1px;">Gadget Murah</span>
        </a>

{{-- KATEGORI DROPDOWN --}}
<div class="dropdown ms-2 d-none d-lg-block">
    <button class="btn btn-category d-flex align-items-center gap-2 shadow-none" 
            type="button" id="dropdownCategory" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-grid-fill"></i>
        <span>Kategori</span>
    </button>
    <ul class="dropdown-menu category-mega-menu border-0 shadow-lg mt-3" aria-labelledby="dropdownCategory">
        <li><h6 class="dropdown-header text-uppercase ls-1 px-4">Kategori Populer</h6></li>
        
        {{-- Item: Earphone --}}
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('catalog.index', ['category' => 'Earphone']) }}">
                <div class="icon-box"><i class="bi bi-headphones"></i></div>
                <div>
                    <span class="d-block fw-bold mb-0">Earphone</span>
                    <small class="text-muted">TWS, Wired, & Headphone</small>
                </div>
            </a>
        </li>

        {{-- Item: Case HP --}}
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('catalog.index', ['category' => 'Case-hp']) }}">
                <div class="icon-box"><i class="bi bi-phone-vibrate"></i></div>
                <div>
                    <span class="d-block fw-bold mb-0">Case HP</span>
                    <small class="text-muted">Silikon & Hardcase Premium</small>
                </div>
            </a>
        </li>

        {{-- Item: Power --}}
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('catalog.index', ['category' => 'charger']) }}">
                <div class="icon-box"><i class="bi bi-battery-charging"></i></div>
                <div>
                    <span class="d-block fw-bold mb-0">Power & Charging</span>
                    <small class="text-muted">Charger, Kabel, & Powerbank</small>
                </div>
            </a>
        </li>

        {{-- Item: Flash Disk --}}
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('catalog.index', ['category' => 'flashdisk']) }}">
                <div class="icon-box"><i class="bi bi-usb-drive"></i></div>
                <div>
                    <span class="d-block fw-bold mb-0">Penyimpanan</span>
                    <small class="text-muted">Flashdisk & Memory Card</small>
                </div>
            </a>
        </li>

        <li><hr class="dropdown-divider mx-3"></li>
        
        {{-- Footer Link --}}
        <li>
            <a class="dropdown-item text-center py-2 fw-bold all-cat-link" href="{{ route('catalog.index') }}">
                Lihat Semua Kategori <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </li>
    </ul>
</div>

        {{-- NAVBAR CONTENT --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- SEARCH BAR --}}
            <form class="mx-lg-4 my-3 my-lg-0 flex-grow-1" action="{{ route('catalog.index') }}" method="GET">
                <div class="input-group search-box" style="border-radius: 8px; overflow: hidden;">
                    <span class="input-group-text bg-white border-end-0 pe-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0 py-2 shadow-none" 
                           placeholder="Cari barang impianmu di sini..." value="{{ request('q') }}">
                </div>
            </form>

            {{-- RIGHT MENU --}}
            <ul class="navbar-nav align-items-center gap-3 ms-lg-auto">
                
                @auth
                    {{-- WISHLIST --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-2" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart fs-5 text-dark"></i>
                            @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                            @if($wishlistCount > 0)
                                <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 10px; border: 2px solid white;">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- KERANJANG --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-2" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5 text-dark"></i>
                            @php 
                                $cartCount = auth()->user()->cart ? auth()->user()->cart->items()->count() : 0; 
                            @endphp
                            @if($cartCount > 0)
                                <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 10px; border: 2px solid white;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <div class="vr d-none d-lg-block mx-1" style="height: 24px; opacity: 0.1;"></div>

                    {{-- USER PROFILE DROPDOWN --}}
                     {{-- USER PROFILE DROPDOWN --}}
<div class="dropdown">
    <a class="nav-link d-flex align-items-center gap-2 py-0 shadow-none dropdown-toggle" 
       data-bs-toggle="dropdown" href="#" role="button">
        <div class="profile-avatar-wrapper">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3B6181&color=fff' }}" 
                 class="rounded-circle border profile-img-nav" width="36" height="36">
            <span class="status-indicator"></span>
        </div>
        <div class="d-none d-xl-block">
            <p class="mb-0 fw-bold text-dark lh-1" style="font-size: 13px;">{{ \Illuminate\Support\Str::words(auth()->user()->name, 1, '') }}</p>
            <small class="text-muted" style="font-size: 11px;">Member Silver</small>
        </div>
    </a>
    
    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 profile-dropdown-custom">
        {{-- Header Card User --}}
        <div class="user-card-header px-4 py-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3B6181&color=fff' }}" 
                     class="rounded-circle" width="45" height="45">
                <div class="overflow-hidden">
                    <h6 class="mb-0 fw-bold text-truncate">{{ auth()->user()->name }}</h6>
                    <p class="mb-0 text-muted text-truncate small">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <div class="dropdown-body p-2">
            @if(auth()->user()->role === 'admin' || auth()->user()->is_admin)
            <li>
                <a class="dropdown-item py-2 rounded-3 admin-link" href="{{ url('/admin/dashboard') }}">
                    <div class="icon-circle bg-primary-soft text-primary"><i class="bi bi-speedometer2"></i></div>
                    <span class="fw-bold">Admin Panel</span>
                </a>
            </li>
            <li><hr class="dropdown-divider opacity-50"></li>
            @endif

            <p class="dropdown-label text-uppercase fw-bold mt-2">Aktivitas Saya</p>
            <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ route('profile.show' , auth()->id()) }}">
                    <div class="icon-circle"><i class="bi bi-person"></i></div>
                    <span>Profil Saya</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ route('orders.index') }}">
                    <div class="icon-circle"><i class="bi bi-bag-check"></i></div>
                    <span>Pesanan</span>
                    <span class="badge rounded-pill bg-light text-dark border ms-auto small">2</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2 rounded-3" href="{{ route('wishlist.index') }}">
                    <div class="icon-circle"><i class="bi bi-heart"></i></div>
                    <span>Wishlist Favorit</span>
                </a>
            </li>
            
            <li><hr class="dropdown-divider opacity-50"></li>
            
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item text-danger py-2 rounded-3 fw-medium w-100 text-start logout-btn">
                        <div class="icon-circle bg-danger-soft"><i class="bi bi-box-arrow-right"></i></div>
                        <span>Keluar Sekarang</span>
                    </button>
                </form>
            </li>
        </div>
    </ul>
</div>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-primary fw-bold px-4 btn-sm" href="{{ route('login') }}" 
                           style="border-radius: 8px; border-color: #3B6181; color: #3B6181;">
                            Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary fw-bold px-4 btn-sm" href="{{ route('register') }}" 
                           style="border-radius: 8px; background-color: #3B6181; border: none;">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Tombol Utama Kategori */
.btn-category {
    background-color: transparent;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 18px;
    font-weight: 600;
    color: #2d3748;
    transition: all 0.2s ease;
}

.btn-category:hover {
    background-color: #f8fafc;
    border-color: #3B6181;
    color: #3B6181;
}

.btn-category i {
    color: #3B6181;
    font-size: 1.1rem;
}

/* Container Dropdown (Mega Menu) */
.category-mega-menu {
    min-width: 340px;
    border-radius: 20px !important;
    padding: 15px 0 !important;
    animation: categorySlide 0.3s ease-out;
}

@keyframes categorySlide {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Item Dropdown */
.category-mega-menu .dropdown-item {
    display: flex;
    gap: 15px;
    padding: 12px 25px;
    transition: all 0.2s;
}

.category-mega-menu .dropdown-item:hover {
    background-color: #f1f5f9;
}

/* Kotak Ikon */
.icon-box {
    width: 44px;
    height: 44px;
    background: #eef2f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3B6181;
    font-size: 1.3rem;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.dropdown-item:hover .icon-box {
    background: #3B6181;
    color: white;
    transform: scale(1.1) rotate(5deg);
}

/* Header & Typography */
.ls-1 {
    letter-spacing: 1px;
    font-size: 11px;
    color: #94a3b8;
    margin-bottom: 8px;
}

.all-cat-link {
    color: #3B6181 !important;
    font-size: 13px;
}

.all-cat-link:hover {
    background: transparent !important;
    text-decoration: underline !important;
}
    /* Styling Global Dropdown Item */
    .dropdown-item {
        transition: all 0.2s ease;
        font-size: 14px;
        color: #31353b;
    }

    .dropdown-item:hover {
        background-color: #f0f3f7;
        color: #3B6181;
    }

    .text-admin-link {
        color: #3B6181 !important;
    }
    
    .dropdown-item.text-admin-link:hover {
        background-color: #e8eff5 !important;
    }

    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
    }

    /* Menghilangkan panah dropdown default */
    .dropdown-toggle::after {
        display: none !important;
    }

    .dropdown-menu {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        animation: dropdownFadeIn 0.2s ease-out;
    }

    @keyframes dropdownFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Search Box Focus */
    .search-box {
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }
    .search-box:focus-within {
        border-color: #3B6181;
        box-shadow: 0 0 0 0.2rem rgba(59, 97, 129, 0.1);
    }

    .category-btn:hover {
        background-color: #f8f9fa;
        border-color: #3B6181 !important;
    }
    /* Container Utama Dropdown */
.profile-dropdown-custom {
    min-width: 280px;
    border-radius: 18px !important;
    padding: 0 !important;
    overflow: hidden;
    animation: profileSlide 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes profileSlide {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header Profil */
.user-card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
    border-bottom: 1px solid #edf2f7;
}

/* Label Grup Menu */
.dropdown-label {
    font-size: 10px;
    color: #94a3b8;
    letter-spacing: 1px;
    padding: 5px 15px;
}

/* Item Dropdown Modern */
.profile-dropdown-custom .dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 15px;
    color: #475569;
    transition: all 0.2s;
}

.profile-dropdown-custom .dropdown-item:hover {
    background-color: #f1f5f9;
    color: #3B6181;
    transform: translateX(5px);
}

/* Lingkaran Ikon */
.icon-circle {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: 0.3s;
}

.dropdown-item:hover .icon-circle {
    background: #3B6181;
    color: white;
}

/* Link Admin & Logout Spesial */
.admin-link { color: #3B6181 !important; }
.bg-primary-soft { background-color: rgba(59, 97, 129, 0.1); }

.logout-btn:hover .icon-circle {
    background: #dc3545 !important;
}

/* Avatar Wrapper & Status */
.profile-avatar-wrapper {
    position: relative;
    padding: 2px;
}
.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: #22c55e;
    border: 2px solid white;
    border-radius: 50%;
}
</style>
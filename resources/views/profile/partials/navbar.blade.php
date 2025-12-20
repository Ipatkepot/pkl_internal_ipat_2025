<style>
    /* Premium Glassmorphism & Variables */
    :root {
        --nav-height: 75px;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --primary-gradient: linear-gradient(135deg, #0d6efd, #0043a8);
    }

    .navbar-custom {
        height: var(--nav-height);
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, background 0.3s ease;
    }

    /* Navbar Auto-Hide on Scroll */
    .navbar-hidden {
        transform: translateY(-100%);
    }

    /* Floating Search Bar */
    .search-container {
        position: relative;
        width: 100%;
        max-width: 450px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-container:focus-within {
        max-width: 550px;
    }

    .search-input {
        border-radius: 12px !important;
        padding: 0.6rem 1rem 0.6rem 3rem !important;
        border: 1px solid #eee !important;
        background: #f1f3f5 !important;
        transition: all 0.3s;
    }

    .search-input:focus {
        background: #fff !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05) !important;
    }

    .search-icon-inside {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        color: #6c757d;
    }

    /* Icon Interactions */
    .nav-icon-btn {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #444;
        transition: all 0.2s;
        position: relative;
        text-decoration: none;
    }

    .nav-icon-btn:hover {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        transform: translateY(-2px);
    }

    .premium-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border: 2px solid #fff;
    }

    /* User Profile Glow */
    .user-profile-btn {
        border: 2px solid transparent;
        transition: all 0.3s;
    }
    
    .user-profile-btn:hover {
        border-color: #0d6efd;
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.2);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top" id="mainNavbar">
    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center me-4" href="{{ route('home') }}">
            <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--primary-gradient) !important;">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <span class="fw-bolder fs-4 mb-0 tracking-tighter">TOKO<span class="warna">PRO</span></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list fs-1"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- Search Bar with Icon Inside --}}
            <form class="mx-lg-auto my-3 my-lg-0 search-container" action="{{ route('catalog.index') }}" method="GET">
                <i class="bi bi-search search-icon-inside"></i>
                <input type="text" name="q" class="form-control search-input shadow-none" 
                       placeholder="Cari produk impianmu..." value="{{ request('q') }}">
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                {{-- Explore --}}
                <li class="nav-item">
                    <a class="nav-link px-3 fw-bold" href="{{ route('catalog.index') }}">Jelajah</a>
                </li>

                @auth
                    {{-- Wishlist --}}
                    <li class="nav-item">
                        <a class="nav-icon-btn mx-1" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart fs-5"></i>
                            @if(auth()->user()->wishlists()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger premium-badge">
                                    {{ auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-icon-btn mx-1" href="{{ route('cart.index') }}">
                            <i class="bi bi-bag-check fs-5"></i>
                            @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary premium-badge">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- User Profile Dropdown --}}
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-1 user-profile-btn rounded-pill" 
                           href="#" id="profileDrop" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" width="32" height="32">
                            <i class="bi bi-chevron-down ms-2 fs-small" style="font-size: 0.7rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 animate slideIn" style="border-radius: 15px; min-width: 220px;">
                            <li class="p-3 border-bottom mb-2">
                                <p class="mb-0 fw-bold">{{ auth()->user()->name }}</p>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>
                            <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i> Pengaturan Akun</a></li>
                            <li><a class="dropdown-item rounded-3 py-2" href="{{ route('orders.index') }}"><i class="bi bi-truck me-2"></i> Lacak Pesanan</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item rounded-3 py-2 text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i> Dashboard Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item rounded-3 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary border-0 fw-bold px-4" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Script for Auto-Hide --}}
<script>
    let lastScroll = 0;
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        if (currentScroll <= 0) {
            navbar.classList.remove('navbar-hidden');
            return;
        }
        if (currentScroll > lastScroll && !navbar.classList.contains('navbar-hidden')) {
            // Scroll Down
            navbar.classList.add('navbar-hidden');
        } else if (currentScroll < lastScroll && navbar.classList.contains('navbar-hidden')) {
            // Scroll Up
            navbar.classList.remove('navbar-hidden');
        }
        lastScroll = currentScroll;
    });
</script>
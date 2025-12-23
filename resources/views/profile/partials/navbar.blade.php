<style>
    :root {
        --nav-height: 75px;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --primary-color: #0d6efd;
        --text-dark: #212529;
    }

    .navbar-custom {
        height: var(--nav-height);
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        z-index: 1030;
    }

    .navbar-hidden {
        transform: translateY(-100%);
    }

    /* Logo */
    .logo-img {
        height: 48px;
        width: auto;
        object-fit: contain;
    }

    .logo-text {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .logo-accent {
        color: var(--primary-color);
    }

    /* Search Bar */
    .search-form {
        max-width: 500px;
        width: 100%;
        position: relative;
    }

    .search-input {
        padding: 0.75rem 1rem 0.75rem 3rem;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .search-input:focus {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
        font-size: 1.1rem;
    }

    /* Icon Buttons */
    .nav-icon-btn {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        transition: all 0.25s ease;
        position: relative;
    }

    .nav-icon-btn:hover {
        background: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
        transform: translateY(-3px);
    }

    .badge-counter {
        font-size: 0.65rem;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
    }

    /* Profile Dropdown */
    .profile-avatar {
        width: 38px;
        height: 38px;
        object-fit: cover;
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .profile-btn:hover .profile-avatar {
        border-color: var(--primary-color);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.25);
    }

    .dropdown-menu-custom {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        min-width: 240px;
        padding: 0.75rem 0;
        margin-top: 12px;
    }

    .dropdown-item-custom {
        border-radius: 10px;
        padding: 0.65rem 1.25rem;
        margin: 0 0.5rem;
        transition: all 0.2s;
    }

    .dropdown-item-custom:hover {
        background: rgba(13, 110, 253, 0.08);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top shadow-sm" id="mainNavbar">
    <div class="container">
        {{-- Logo & Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-3 me-5" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="GadgetMurah Logo" class="logo-img">
            <span class="logo-text">Gadget<span class="logo-accent">Murah</span></span>
        </a>

        {{-- Toggler untuk Mobile --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list fs-3"></i>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- Search Form (Centered) --}}
            <form class="mx-auto my-3 my-lg-0 search-form" action="{{ route('catalog.index') }}" method="GET">
                <i class="bi bi-search search-icon"></i>
                <input type="text"
                       name="q"
                       class="form-control search-input"
                       placeholder="Cari gadget impianmu..."
                       value="{{ request('q') }}"
                       autocomplete="off">
            </form>

            {{-- Right Menu --}}
            <ul class="navbar-nav align-items-center gap-2">
                {{-- Explore Link --}}
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link fw-semibold px-3" href="{{ route('catalog.index') }}">Jelajah</a>
                </li>

                @auth
                    {{-- Wishlist --}}
                      <li class="nav-item position-relative">
                        <a class="nav-link icon-link" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart fs-4"></i>
                            @if(auth()->user()->wishlistProducts()->count() > 0)
                                <span class="badge-modern badge-wishlist">{{ auth()->user()->wishlistProducts()->count() }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="nav-icon-btn">
                            <i class="bi bi-bag fs-5"></i>
                            @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary badge-counter">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Profile Dropdown --}}
                    <li class="nav-item dropdown ms-3">
                        <a class="dropdown-toggle d-flex align-items-center gap-2 profile-btn" 
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Profile" class="rounded-circle profile-avatar">
                            <i class="bi bi-chevron-down fs-6"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                            <li class="px-4 py-3 border-bottom">
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>

                            <li><a class="dropdown-item dropdown-item-custom" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i> Pengaturan Akun
                            </a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="{{ route('orders.index') }}">
                                <i class="bi bi-truck me-2"></i> Pesanan Saya
                            </a></li>

                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider mx-3"></li>
                                <li><a class="dropdown-item dropdown-item-custom text-primary fw-semibold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-shield-lock me-2"></i> Dashboard Admin
                                </a></li>
                            @endif

                            <li><hr class="dropdown-divider mx-3"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 fw-semibold">Masuk</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Auto Hide Navbar on Scroll --}}
<script>
    let lastScroll = 0;
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;           

        if (currentScroll <= 0) {
            navbar.classList.remove('navbar-hidden');
            return;
        }

        if (currentScroll > lastScroll) {
            navbar.classList.add('navbar-hidden');
        } else {
            navbar.classList.remove('navbar-hidden');
        }

        lastScroll = currentScroll;
    });
</script>
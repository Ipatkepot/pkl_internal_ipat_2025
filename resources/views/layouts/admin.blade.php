<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #3B6181;    /* Biru Steel Utama sesuai permintaan */
            --accent-blue: #3b82f6;
            --surface: #ffffff;
            --body-bg: #f8fafc;
            --border: rgba(0, 0, 0, 0.05);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* --- Sidebar: Steel Blue Theme --- */
        .sidebar {
            width: 280px;
            background: var(--primary-color);
            display: flex;
            flex-direction: column;
            padding: 32px 16px;
            position: sticky;
            top: 0;
            height: 100vh;
            color: rgba(255, 255, 255, 0.9); /* Teks putih transparan untuk sidebar */
            box-shadow: 4px 0 24px rgba(0,0,0,0.05);
        }

        .brand {
            padding: 0 16px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
            color: #fff;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: white;
        }

        /* --- Nav Items --- */
        .nav-section { margin-bottom: 28px; }
        
        .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5); /* Label agak redup agar hirarki jelas */
            letter-spacing: 1px;
            padding: 0 16px 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }

        .nav-link i { 
            font-size: 1.2rem; 
            margin-right: 12px; 
            color: rgba(255, 255, 255, 0.5); 
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .nav-link.active {
            background: #fff;
            color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .nav-link.active i { color: var(--primary-color); }

        .pending-badge {
            margin-left: auto;
            background: #ff4d4d;
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        /* --- Content Area --- */
        .wrapper {
            flex-grow: 1;
            padding: 48px 60px;
            overflow-y: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 48px;
        }

        .page-title h1 {
            font-weight: 800;
            font-size: 2.2rem;
            margin: 0;
            letter-spacing: -1px;
            color: var(--text-main);
        }

        /* --- The "Elite" Card --- */
        .main-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        /* --- Profile Footer Sidebar --- */
        .profile-footer {
            margin-top: auto;
            background: rgba(0, 0, 0, 0.15); /* Lebih gelap dari sidebar */
            border-radius: 20px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-footer img {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .logout-btn {
            background: rgba(255, 77, 77, 0.2);
            color: #ff9999;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            transition: 0.2s;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #ff4d4d;
            color: white;
        }

        .btn-live {
            background: var(--primary-color);
            color: #fff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-live:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        /* Styling Baru untuk Profile Link */
.profile-link {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-grow: 1;
    min-width: 0;
    text-decoration: none; /* Hilangkan garis bawah */
    padding: 4px;
    border-radius: 12px;
    transition: background 0.2s ease;
}

.profile-link:hover {
    background: rgba(255, 255, 255, 0.1); /* Efek highlight saat di-hover */
}

.user-info {
    flex-grow: 1;
    min-width: 0;
}

.user-name {
    font-size: 0.8rem;
    font-weight: 800;
    color: #fff;
}

.user-role {
    font-size: 0.65rem;
    color: rgba(255, 255, 255, 0.5);
    font-weight: 600;
}

/* Pastikan Profile Footer tidak berubah layoutnya */
.profile-footer {
    margin-top: auto;
    background: rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    padding: 10px; /* Sedikit diperkecil agar pas dengan hover effect */
    display: flex;
    align-items: center;
    gap: 8px;
}
    </style>
</head>

<body>
@include('partials.admin-loader')
    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-pc-display"></i>
            </div>
            <span>Admin Gadget</span>
        </div>

        <nav class="nav-section">
            <div class="nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </nav>

        <nav class="nav-section">
            <div class="nav-label">Inventory</div>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Products
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Categories
            </a>
        </nav>

        <nav class="nav-section">
            <div class="nav-label">Management</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i> Orders
                @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                @if ($pendingCount > 0)
                    <span class="pending-badge">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Reports
            </a>
        </nav>

        <div class="profile-footer">
    <a href="{{ route('profile.show', auth()->id()) }}" class="profile-link">
        <img src="{{ auth()->user()->avatar_url }}" alt="User">
        <div class="user-info">
            <div class="user-name text-truncate">{{ auth()->user()->name }}</div>
            <div class="user-role">System Admin</div>
        </div>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="m-0">
        @csrf
        <button type="submit" class="logout-btn" title="Logout">
            <i class="bi bi-power"></i>
        </button>
    </form>
</div>
    </aside>

    {{-- Main --}}
    <main class="wrapper">
        <header>
            <div class="page-title">
                <h1>@yield('page-title', 'System Overview')</h1>
                <p style="color: var(--text-muted); margin-top: 5px;">Monitor and manage your business performance.</p>
            </div>

            <a href="{{ route('home') }}" class="btn-live" target="_blank">
                <i class="bi bi-globe2"></i> View Site
            </a>
        </header>

        <div class="mb-4">
            @include('partials.flash-messages')
        </div>

        <div class="main-card">
            @yield('content')
        </div>

        <footer style="margin-top: 48px; display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">
            <div>&copy; 2026 Admin Gadget</div>
            <div class="d-flex gap-3">
                <span class="text-success">&bull; All Systems Live</span>
            </div>
        </footer>
    </main>

    @stack('scripts')
</body>
</html>
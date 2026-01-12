{{-- ================================================
     FILE: resources/views/layouts/app.blade.php
     FUNGSI: Master layout untuk halaman customer/publik
     ================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF Token untuk AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'Toko Online') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Toko online terpercaya dengan produk berkualitas')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    {{-- CSS Library --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Google Fonts: Plus Jakarta Sans (Modern & Clean) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1d23;
        }
        /* Custom scrollbar agar lebih premium */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #3B6181; border-radius: 10px; }
    </style>

    @stack('styles')
</head>

<body>
    {{-- 1. LOADER COMPONENT (Muncul pertama kali saat body render) --}}
    @include('partials.admin-loader')

    {{-- ============================================
         NAVBAR
         ============================================ --}}
    @include('partials.navbar')

    {{-- ============================================
         FLASH MESSAGES
         ============================================ --}}
    <div class="container mt-4">
        @include('partials.flash-messages')
    </div>

    {{-- ============================================
         MAIN CONTENT
         ============================================ --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- ============================================
         FOOTER
         ============================================ --}}
    @include('partials.footer')

    {{-- ============================================
         JAVASCRIPT
         ============================================ --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS (Animate on Scroll)
        AOS.init({
            once: true,
            duration: 800
        });

        /**
         * Fungsi Global untuk Toggle Wishlist (AJAX)
         */
        async function toggleWishlist(productId) {
            @guest
                window.location.href = "{{ route('login') }}";
                return;
            @endguest

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;

                const response = await fetch("{{ route('wishlist.index') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                if (response.status === 401) {
                    window.location.href = "{{ route('login') }}";
                    return;
                }

                const data = await response.json();

                if (data.status === 'added' || data.added) {
                    updateWishlistIcon(productId, true);
                } else {
                    updateWishlistIcon(productId, false);
                }

                const badge = document.getElementById("wishlist-count");
                if (badge && data.count !== undefined) {
                    badge.innerText = data.count;
                    badge.style.display = data.count > 0 ? "inline-block" : "none";
                }

            } catch (error) {
                console.error("Wishlist Error:", error);
            }
        }

        function updateWishlistIcon(productId, isAdded) {
            const icons = document.querySelectorAll('#wishlist-icon-' + productId + ', .wishlist-btn-' + productId + ' i');
            
            icons.forEach(icon => {
                if (isAdded) {
                    icon.className = 'bi bi-heart-fill text-danger';
                } else {
                    icon.className = 'bi bi-heart text-dark';
                }
            });
        }
    </script>

    {{-- Stack untuk JS tambahan per halaman --}}
    @stack('scripts')
</body>
</html>
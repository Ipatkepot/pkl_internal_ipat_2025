<footer class="bg-white pt-5 pb-4 mt-5" style="border-top: 1px solid #edf2f7;">
    <div class="container">
        <div class="row g-4">
            {{-- Brand & About --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #3B6181; letter-spacing: -0.5px;">
                <img src="{{ asset('images/logo.png') }}" alt="Gadget Murahh Logo" width="36" height="36">
                    Gadget Murah
                </h5>
                <p class="text-muted small pe-lg-5" style="line-height: 1.8; color: #64748b !important;">
                    Gadget Murah adalah platform belanja online terpercaya yang menghadirkan teknologi terbaru ke genggaman Anda. 
                    Kami berkomitmen memberikan pengalaman belanja yang transparan, aman, dan berorientasi pada kepuasan pelanggan.
                </p>

                <div class="d-flex gap-2 mt-4">
                    <a href="#" class="footer-social-wrapper"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social-wrapper"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social-wrapper"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-social-wrapper"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Menu Navigasi --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold mb-4 text-dark" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Jelajahi</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3"><a href="{{ route('catalog.index') }}" class="footer-link">Katalog Produk</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Promo Spesial</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Tentang Kami</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Karir</a></li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold mb-4 text-dark" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Dukungan</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3"><a href="#" class="footer-link">Pusat Bantuan</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Kebijakan Garansi</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Lacak Pengiriman</a></li>
                    <li class="mb-3"><a href="#" class="footer-link">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-4 text-dark" style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Hubungi Kami</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3 d-flex align-items-start gap-3">
                        <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <span class="text-muted">Jl. Rancamanyar No. 24, Kec. Baleendah, Bandung, Jawa Barat</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center gap-3">
                        <div class="contact-icon"><i class="bi bi-whatsapp"></i></div>
                        <span class="text-muted">0821-9572-1271</span>
                    </li>
                    <li class="mb-3 d-flex align-items-center gap-3">
                        <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                        <span class="text-muted">ifatfatahillah3@gmail.com</span>
                    </li>
                </ul>
                <div class="mt-4 p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                    <h6 class="fw-bold" style="font-size: 12px; color: #3B6181; margin-bottom: 8px;">KEAMANAN TRANSAKSI</h6>
                    <div class="d-flex gap-3 text-muted">
                        <i class="bi bi-shield-lock-fill fs-5" title="SSL Encrypted"></i>
                        <i class="bi bi-patch-check-fill fs-5" title="Verified Merchant"></i>
                        <i class="bi bi-safe2-fill fs-5" title="Secure Payment"></i>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5" style="opacity: 0.05;">

        {{-- Bottom Footer --}}
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted small mb-0">
                    &copy; {{ date('Y') }} <span class="fw-bold" style="color: #3B6181;">Gadget Murahh</span>. Built with ❤️ for Gadget Lovers.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="d-flex justify-content-center justify-content-md-end gap-3 align-items-center">
                    <span class="small text-muted fw-medium">Metode Pembayaran:</span>
                    <div class="payment-icon"><i class="bi bi-credit-card"></i></div>
                    <div class="payment-icon"><i class="bi bi-qr-code-scan"></i></div>
                    <div class="payment-icon"><i class="bi bi-bank"></i></div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Link Styling */
    .footer-link {
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        font-weight: 400;
    }

    .footer-link:hover {
        color: #3B6181;
        padding-left: 8px;
    }

    /* Social Media Circle Icons */
    .footer-social-wrapper {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-social-wrapper:hover {
        background: #3B6181;
        color: #ffffff !important;
        transform: translateY(-3px);
    }

    /* Contact Icon Styling */
    .contact-icon {
        color: #3B6181;
        font-size: 16px;
        flex-shrink: 0;
    }

    /* Payment Icon Styling */
    .payment-icon {
        color: #94a3b8;
        font-size: 20px;
        transition: color 0.3s ease;
    }
    
    .payment-icon:hover {
        color: #3B6181;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .footer-link:hover {
            padding-left: 0;
        }
    }
</style>
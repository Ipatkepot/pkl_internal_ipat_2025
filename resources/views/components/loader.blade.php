{{-- resources/views/partials/loader.blade.php --}}
<div id="laravel-loader" class="loader-overlay">
    <div class="loader-content">
        <div class="logo-wrapper">
            <div class="liquid-ring"></div>
            <div class="liquid-ring delay-ring"></div>
            <div class="logo-box">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="smooth-logo">
            </div>
        </div>
        
        <div class="loader-info">
            <h2 class="brand-text">GADGET<span>MURAH</span></h2>
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
            <div class="status-wrap">
                <span class="status-msg" id="status-text">Inisialisasi sistem</span>
                <span class="percentage" id="load-perc">0%</span>
            </div>
        </div>
    </div>
</div>

<style>
    .loader-overlay {
        position: fixed;
        inset: 0;
        background: #ffffff;
        z-index: 99999999;
        display: flex;
        justify-content: center;
        align-items: center;
        /* Transisi keluar yang sangat lembut */
        transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), 
                    transform 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                    filter 0.6s ease;
    }

    .logo-wrapper {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto 30px;
    }

    /* Efek Ring yang berputar lembut */
    .liquid-ring {
        position: absolute;
        inset: -8px;
        border: 2px solid transparent;
        border-top-color: #3b82f6;
        border-left-color: #3b82f6;
        border-radius: 38% 62% 63% 37% / 41% 44% 56% 59%;
        animation: liquidRotate 3s linear infinite;
        opacity: 0.4;
    }

    .delay-ring {
        animation-duration: 2s;
        animation-direction: reverse;
        border-top-color: #8b5cf6;
        opacity: 0.2;
    }

    .logo-box {
        width: 100%;
        height: 100%;
        background: #fff;
        border-radius: 28px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.05);
        animation: softFloat 3s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    .smooth-logo { width: 55px; height: 55px; object-fit: contain; }

    .brand-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 1.6rem;
        letter-spacing: -0.5px;
        text-align: center;
        color: #0f172a;
        margin-bottom: 20px;
    }

    .brand-text span { color: #3b82f6; font-weight: 400; }

    .progress-container {
        width: 220px;
        height: 5px;
        background: #f1f5f9;
        border-radius: 10px;
        margin: 0 auto;
        overflow: hidden;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        /* Kunci kehalusan: durasi transisi sama dengan interval update JS */
        transition: width 0.4s cubic-bezier(0.1, 0.7, 1.0, 0.1);
    }

    .status-wrap {
        display: flex;
        justify-content: space-between;
        width: 220px;
        margin: 10px auto 0;
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        letter-spacing: 0.5px;
    }

    /* Keyframes */
    @keyframes liquidRotate {
        0% { transform: rotate(0deg) scale(1); border-radius: 38% 62% 63% 37%; }
        50% { transform: rotate(180deg) scale(1.05); border-radius: 50%; }
        100% { transform: rotate(360deg) scale(1); border-radius: 38% 62% 63% 37%; }
    }

    @keyframes softFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Exit Style */
    .loader-hidden {
        opacity: 0;
        filter: blur(15px);
        transform: scale(1.1);
        pointer-events: none;
    }
</style>

<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("laravel-loader");
        const bar = document.querySelector(".progress-bar");
        const perc = document.getElementById("load-perc");
        const statusText = document.getElementById("status-text");
        
        let width = 0;
        const messages = ["Menyiapkan aset", "Mengoptimalkan UI", "Hampir siap..."];
        
        const updateLoader = () => {
            if (width >= 100) {
                width = 100;
                bar.style.width = "100%";
                perc.innerText = "100%";
                
                setTimeout(() => {
                    loader.classList.add("loader-hidden");
                    setTimeout(() => loader.remove(), 600);
                }, 300);
                return;
            }

            // Lonjakan yang kecil dan konstan agar transisi CSS bisa bekerja maksimal
            width += Math.random() * 15 + 5; 
            if(width > 100) width = 100;

            bar.style.width = width + "%";
            perc.innerText = Math.floor(width) + "%";

            // Update pesan status berdasarkan progress
            if(width > 40 && width < 80) statusText.innerText = messages[1];
            if(width >= 80) statusText.innerText = messages[2];

            // Waktu acak untuk simulasi beban kerja asli yang smooth
            setTimeout(updateLoader, Math.random() * 200 + 100);
        };

        // Mulai loading
        setTimeout(updateLoader, 100);
    });
</script>
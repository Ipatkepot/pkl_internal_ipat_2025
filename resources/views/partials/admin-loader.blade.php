<div id="admin-loader" class="yt-loader">
    <div class="yt-progress-container">
        <div class="yt-progress-bar">
            <div class="yt-shimmer"></div>
        </div>
    </div>
    <div class="yt-spinner">
        <div class="spinner-icon"></div>
    </div>
</div>

<style>
    :root {
        --loader-color: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        --loader-height: 4px;
    }

    .yt-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999999;
        pointer-events: none;
        transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .yt-progress-container {
        width: 100%;
        height: var(--loader-height);
        background: rgba(255, 255, 255, 0.05);
        overflow: hidden;
    }

    .yt-progress-bar {
        width: 0%;
        height: 100%;
        background: var(--loader-color);
        position: relative;
        /* Kunci Smoothness: Cubic-bezier khusus untuk gerakan "magnet" */
        transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.5);
    }

    /* Shimmer yang mengalir lembut */
    .yt-shimmer {
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(
            90deg, 
            transparent, 
            rgba(255, 255, 255, 0.3), 
            transparent
        );
        animation: shimmer-flow 2s infinite ease-in-out;
    }

    /* Spinner halus di pojok */
    .yt-spinner {
        position: fixed;
        top: 12px;
        right: 12px;
        display: block;
        transition: opacity 0.4s ease;
    }

    .spinner-icon {
        width: 14px;
        height: 14px;
        border: 2px solid transparent;
        border-top-color: #3b82f6;
        border-left-color: #3b82f6;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes shimmer-flow {
        0% { transform: translateX(-150%); }
        100% { transform: translateX(400%); }
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Finish State */
    .yt-loader.finished {
        opacity: 0;
    }
    
    .yt-loader.finished .yt-progress-bar {
        transition: width 0.3s ease-out; /* Melesat saat selesai */
    }
</style>

<script>
    (function() {
        const loader = document.getElementById('admin-loader');
        const bar = loader.querySelector('.yt-progress-bar');
        
        let progress = 0;
        
        // 1. Start: Langsung ke posisi awal yang meyakinkan
        setTimeout(() => {
            progress = 25;
            bar.style.width = progress + '%';
        }, 30);

        // 2. Incremental Smooth: Gerakan melambat saat mendekati 100 (Asymptotic)
        const smoothMovement = setInterval(() => {
            if (progress < 92) {
                // Menambah jarak sedikit demi sedikit (semakin tinggi progress, semakin kecil tambahannya)
                let diff = (95 - progress) * 0.035; 
                progress += diff;
                bar.style.width = progress + '%';
            }
        }, 150);

        // 3. Finish: Saat window load selesai sempurna
        window.addEventListener('load', () => {
            clearInterval(smoothMovement);
            progress = 100;
            bar.style.width = '100%';
            
            setTimeout(() => {
                loader.classList.add('finished');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 400);
        });
    })();
</script>
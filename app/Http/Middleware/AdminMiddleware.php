<?php
// ========================================
// FILE: app/Http/Middleware/AdminMiddleware.php
// FUNGSI: Membatasi akses hanya untuk user dengan role 'admin'
// ========================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan import untuk konsistensi
use Symfony\Component\HttpFoundation\Response;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Method ini dipanggil SETIAP KALI ada request yang melewati middleware ini.
     *
     * @param Request $request  Request dari user
     * @param Closure $next     Fungsi untuk melanjutkan ke proses berikutnya
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ================================================
        // STEP 1: Cek apakah user sudah login
        // ================================================
        if (!Auth::check()) {
            // Auth::check() = return true jika sudah login, false jika belum
            // !Auth::check() = NOT login = belum login

            // Tambahkan logging untuk debug
            logger()->info('AdminMiddleware: User not authenticated, redirecting to login.');

            return redirect()->route('login');
            // ↑ Redirect ke halaman login
        }

        // ================================================
        // STEP 2: Cek apakah user adalah admin
        // ================================================
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            // Auth::user()         = Ambil data user yang login
            // $user->role          = Ambil nilai kolom 'role'
            // !== 'admin'          = Bukan admin
            // Tambahkan pengecekan null untuk safety

            // Tambahkan logging untuk debug
            logger()->warning('AdminMiddleware: Access denied for user ID ' . ($user ? $user->id : 'null') . ' with role ' . ($user ? $user->role : 'null'));

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            // ↑ abort(403) = Tampilkan halaman error 403 (Forbidden)
            // Artinya: "Kamu dilarang masuk ke sini!"
        }

        // ================================================
        // STEP 3: Jika lolos semua pengecekan, lanjutkan request
        // ================================================
        return $next($request);
        // ↑ $next($request) = Lanjutkan ke controller tujuan
    }
}
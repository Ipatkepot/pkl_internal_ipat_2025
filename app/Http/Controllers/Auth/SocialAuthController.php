<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user       = User::findOrCreateFromProvider('google', $googleUser);

            Auth::login($user);

            return redirect()->intended('/dashboard')
                ->with('success', 'Login berhasil menggunakan Google');

        } catch (Exception $e) {
            return redirect('/login')
                ->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    // GitHub
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $user       = User::findOrCreateFromProvider('github', $githubUser);

            Auth::login($user);

            return redirect()->intended('/dashboard')
                ->with('success', 'Login berhasil menggunakan GitHub');

        } catch (Exception $e) {
            return redirect('/login')
                ->with('error', 'Gagal login dengan GitHub: ' . $e->getMessage());
        }
    }
}

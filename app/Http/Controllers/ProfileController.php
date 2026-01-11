<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil publik user.
     */
    public function show(User $user): View
    {
        return view('profile.show', compact('user'));
    }

    /**
     * Menampilkan form edit profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update Informasi Profil, Avatar, dan Banner (Gabungan).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Ambil data yang tervalidasi (Hanya data yang ada di form yang diproses)
        $user->fill($request->validated());

        // 2. Handle Upload Avatar
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarName = 'avatar-' . $user->id . '-' . time() . '.' . $request->file('avatar')->extension();
            $user->avatar = $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
        }

        // 3. Handle Upload Banner
        if ($request->hasFile('banner')) {
            // Hapus banner lama jika ada
            if ($user->banner && Storage::disk('public')->exists($user->banner)) {
                Storage::disk('public')->delete($user->banner);
            }

            $bannerName = 'banner-' . $user->id . '-' . time() . '.' . $request->file('banner')->extension();
            $user->banner = $request->file('banner')->storeAs('banners', $bannerName, 'public');
        }

        // 4. Reset Verifikasi Email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profil dan visual berhasil diperbarui!');
    }

    /**
     * Method Khusus Update Banner (Jika form dipisah).
     */
    public function updateBanner(Request $request): RedirectResponse
    {
        $request->validate([
            'banner' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $user = $request->user();

        if ($request->hasFile('banner')) {
            if ($user->banner && Storage::disk('public')->exists($user->banner)) {
                Storage::disk('public')->delete($user->banner);
            }

            $filename = 'banner-' . $user->id . '-' . time() . '.' . $request->file('banner')->extension();
            $path = $request->file('banner')->storeAs('banners', $filename, 'public');

            $user->update(['banner' => $path]);
        }

        return back()->with('success', 'Background berhasil diperbarui!');
    }

    /**
     * Hapus Avatar.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Hapus Banner (Reset ke default).
     */
    public function destroyBanner(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->banner && Storage::disk('public')->exists($user->banner)) {
            Storage::disk('public')->delete($user->banner);
            $user->update(['banner' => null]);
        }

        return back()->with('success', 'Background berhasil dikembalikan ke default.');
    }

    /**
     * Update Password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Hapus Akun secara Permanen.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        // Bersihkan semua file di storage saat akun dihapus
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->banner) {
            Storage::disk('public')->delete($user->banner);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
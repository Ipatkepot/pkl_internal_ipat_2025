<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
// Untuk Accessor versi baru

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User memiliki satu keranjang aktif.
     */
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * User memiliki banyak pesanan.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke Wishlist (Many-to-Many ke Product)
     */
    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    // ==================== HELPER METHODS ====================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function hasInWishlist(Product $product): bool
    {
        return $this->wishlists()->where('product_id', $product->id)->exists();
    }

    // ==================== ACCESSORS (STANDAR TERBARU) ====================

    /**
     * Accessor untuk URL Avatar.
     * Panggil di Blade dengan: {{ auth()->user()->avatar_url }}
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            // 1. Upload Lokal
            if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
                return asset('storage/' . $this->avatar);
            }

            // 2. URL Google (Socialite)
            if (str_starts_with($this->avatar ?? '', 'http')) {
                return $this->avatar;
            }

            // 3. Gravatar Fallback
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
        });
    }

    /**
     * Accessor untuk Inisial Nama.
     * Panggil di Blade dengan: {{ auth()->user()->initials }}
     */
    protected function initials(): Attribute
    {
        return Attribute::get(function () {
            $words    = explode(' ', $this->name);
            $initials = '';
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
            return substr($initials, 0, 2);
        });
    }
}

<?php
namespace App\Models;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product; // Ditambahkan agar relasi cart() tidak error
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (mass assignable).
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
     * Atribut yang disembunyikan untuk serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // --- ACCESSORS ---

    /**
     * Accessor untuk mendapatkan URL Avatar yang valid.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && ! str_starts_with($this->avatar, 'http')) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=200";
    }

    /**
     * Accessor untuk mendapatkan inisial nama (maksimal 2 huruf).
     */
    public function getInitialsAttribute(): string
    {
        $words    = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    // --- RELATIONS & HELPERS ---

    /**
     * Relasi Many-to-Many ke Product (Wishlist).
     * Pastikan panggil $user->wishlists() di Controller/View.
     */
    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    /**
     * Helper untuk mengecek apakah produk ada di wishlist user.
     */
    public function hasInWishlist(Product $product)
    {
        return $this->wishlists()->where('product_id', $product->id)->exists();
    }

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi One-to-Many ke Order.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi One-to-One ke Cart.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}

<?php
namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Pastikan ini ada
use Illuminate\Notifications\Notifiable;
// Pastikan ini ada

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Accessor untuk URL Avatar
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

    // Accessor untuk Inisial
    public function getInitialsAttribute(): string
    {
        $words    = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return substr($initials, 0, 2);
    }

    // Relasi & Helper Wishlist
    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function hasInWishlist(Product $product)
    {
        return $this->wishlists()->where('product_id', $product->id)->exists();
    }

    // Check Admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Relasi Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

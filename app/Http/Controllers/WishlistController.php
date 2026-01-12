<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Menampilkan daftar wishlist user
     */
    public function index()
    {
        $user = Auth::user();

        $products = $user->wishlists()
            ->with(['category', 'images'])            // eager loading relasi
            ->orderBy('wishlists.created_at', 'desc') // urutkan dari yang terbaru ditambahkan
            ->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    /**
     * Menambah / menghapus produk dari wishlist (toggle)
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();

        // Cek apakah produk sudah ada di wishlist
        if ($user->wishlist()->where('product_id', $product->id)->exists()) {
            // Jika sudah ada → hapus
            $user->wishlist()->detach($product->id);

            $status  = 'removed';
            $message = 'Produk berhasil dihapus dari wishlist';
        } else {
            // Jika belum ada → tambahkan
            $user->wishlist()->attach($product->id);

            $status  = 'added';
            $message = 'Produk berhasil ditambahkan ke wishlist';
        }

        return response()->json([
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'count'   => $user->wishlist()->count(),
        ]);
    }

    /**
     * (Opsional) Versi alternatif toggle dengan method helper di model
     * Lebih bersih jika Anda ingin gunakan helper method
     */
    public function toggleAlternative(Product $product)
    {
        $user = Auth::user();

        if ($user->hasInWishlist($product)) {
            $user->removeFromWishlist($product);
            $status  = 'removed';
            $message = 'Produk dihapus dari wishlist';
        } else {
            $user->addToWishlist($product);
            $status  = 'added';
            $message = 'Produk ditambahkan ke wishlist';
        }

        return response()->json([
            'success' => true,
            'status'  => $status,
            'message' => $message,
            'count'   => $user->wishlist()->count(),
        ]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware('auth'); // Semua method di controller ini butuh login
    }

    /**
     * Halaman checkout normal (dari keranjang)
     */
    public function index()
    {
        $cart = $this->cartService->getCart();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $cart->loadMissing('items.product');
        $cartItems = $cart->items;

        $subtotal     = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        $shippingCost = 10000;
        $total        = $subtotal + $shippingCost;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    /**
     * Checkout Langsung (Buy Now) dari halaman detail produk
     */
    public function directCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        // Ambil produk dulu (karena addProduct butuh instance Product)
        $product = Product::findOrFail($request->product_id);

        $quantity = $request->quantity;

        try {
            // Gunakan method yang BENAR: addProduct()
            $this->cartService->addProduct($product, $quantity);

            return redirect()->route('checkout.index')
                ->with('success', 'Produk langsung masuk ke checkout! Silakan lengkapi pesanan.');

        } catch (\Exception $e) {
            // Tangkap error dari validasi stok di CartService
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Proses pembuatan order dari form checkout
     */
    public function store(Request $request, OrderService $orderService)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'notes'            => 'nullable|string|max:1000',
        ]);

        try {
            $user = Auth::user();
            $cart = $this->cartService->getCart();

            if (! $cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang kosong. Tidak dapat memproses pesanan.');
            }

            $cart->loadMissing('items.product');

            $orderData = [
                'name'    => $request->shipping_name,
                'phone'   => $request->shipping_phone,
                'address' => $request->shipping_address,
                'notes'   => $request->notes ?? null,
            ];

            $order = $orderService->createOrder($user, $orderData);

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Terima kasih telah berbelanja.');

        } catch (\Exception $e) {
            Log::error('Checkout Store Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }
}

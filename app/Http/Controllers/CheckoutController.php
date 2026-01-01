<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CheckoutController extends Controller
{
    /**
     * Halaman checkout
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan keranjang tidak kosong
        if (!$user || !$user->cart || $user->cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $cartItems = $user->cart->items;

        // PERBAIKAN: Gunakan kurung agar harga dikali quantity dulu baru dijumlahkan
        $subtotal = $cartItems->sum(function($item) {
            $price = $item->product?->price ?? 0;
            return $price * $item->quantity;
        });

        $shippingCost = 10000; // Contoh ongkir statis

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'shippingCost'
        ));
    }

    /**
     * Proses checkout → simpan order
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->cart || $user->cart->items->isEmpty()) {
            return back()->with('error', 'Keranjang kosong');
        }

        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'notes'            => 'nullable|string|max:255',
        ]);

        $cartItems = $user->cart->items;

        // PERBAIKAN: Hitung subtotal dengan benar
        $subtotal = $cartItems->sum(function($item) {
            return ($item->product?->price ?? 0) * $item->quantity;
        });

        $shippingCost = 10000;
        $totalAmount = $subtotal + $shippingCost;

        try {
            return DB::transaction(function () use ($user, $request, $cartItems, $shippingCost, $totalAmount) {
                // 1. Buat data Order
                $order = Order::create([
                    'user_id'          => $user->id,
                    'order_number'     => 'ORD-' . strtoupper(uniqid()),
                    'total_amount'     => $totalAmount,
                    'shipping_cost'    => $shippingCost,
                    'status'           => 'pending',
                    'shipping_name'    => $request->shipping_name,
                    'shipping_phone'   => $request->shipping_phone,
                    'shipping_address' => $request->shipping_address,
                    'notes'            => $request->notes,
                ]);

                // 2. Simpan setiap item ke order_items
                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->name,
                        'price'        => $item->product->price,
                        'quantity'     => $item->quantity,
                        'subtotal'     => $item->product->price * $item->quantity,
                    ]);
                }

                // 3. Kosongkan keranjang setelah order dibuat
                $user->cart->items()->delete();

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibuat!');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
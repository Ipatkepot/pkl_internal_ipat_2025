<?php
namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Menampilkan detail satu order milik user yang login
     */
    public function show(Order $order)
    {
        // Authorization: pastikan order milik user yang sedang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Ini bukan order Anda.');
        }

        // Eager load relasi items beserta product (untuk tampilkan nama, harga snapshot, dll)
        $order->load(['items.product']);

        // Opsional: jika ingin tampilkan total yang sudah dihitung ulang (untuk verifikasi)
        // $order->calculated_total = $order->items->sum('subtotal');

        return view('orders.show', compact('order'));
    }

    /**
     * Opsional: Daftar semua order user (history)
     */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items') // cukup items saja jika tidak butuh detail product
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}

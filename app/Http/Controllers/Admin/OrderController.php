<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan
     */
    public function index(Request $request): View
    {
        $orders = Order::with(['user', 'items.product'])
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString(); // agar filter & search tetap saat pindah halaman

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan
     */
    public function show(Order $order): View
    {
        // Load semua relasi yang dibutuhkan
        $order->load([
            'user',
            'items.product',
            'payment',
            'shipping',
            'address', // jika ada relasi alamat
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan via form (POST)
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // Opsional: kirim notifikasi ke user, update stok, dll

        return back()->with('success', 'Status pesanan berhasil diupdate menjadi "' . ucfirst($request->status) . '"');
    }

    /**
     * (Opsional) Update resi pengiriman
     */
    public function updateTracking(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'courier'         => 'nullable|string|max:50',
        ]);

        // Asumsi kamu punya kolom tracking_number & courier di tabel orders atau shipping
        $order->update([
            'tracking_number' => $request->tracking_number,
            'courier'         => $request->courier,
        ]);

        return back()->with('success', 'Nomor resi berhasil disimpan!');
    }
}
    
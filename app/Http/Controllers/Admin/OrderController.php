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
                // Gunakan order_number karena di model kamu kolomnya adalah order_number
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan
     */
    public function show(Order $order): View
    {
        // PERBAIKAN: Hapus 'shipping' dan 'address' karena relasi tersebut tidak ada di model Order
        // Data pengiriman sudah otomatis terbawa karena ada di tabel orders itu sendiri
        $order->load([
            'user',
            'items.product',
            'payment',
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

        return back()->with('success', 'Status pesanan berhasil diupdate menjadi "' . ucfirst($request->status) . '"');
    }

    /**
     * Update resi pengiriman
     */
    public function updateTracking(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'courier'         => 'nullable|string|max:50',
        ]);

        // Pastikan kolom ini ada di migration kamu, jika tidak ada silakan tambahkan via migration
        $order->update([
            'tracking_number' => $request->tracking_number,
            'courier'         => $request->courier,
        ]);

        return back()->with('success', 'Nomor resi berhasil disimpan!');
    }
}

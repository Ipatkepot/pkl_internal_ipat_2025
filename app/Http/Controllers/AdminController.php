<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Tampilkan Dashboard Admin
     */
    public function dashboard()
    {
        // 1. STATS CARDS (Revenue, Pending, Low Stock, Total Products)
        $stats = [
            'total_revenue'  => Order::whereIn('status', ['completed', 'delivered'])->sum('total_amount'),
            'total_orders'   => Order::count(),
            'total_products' => Product::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'low_stock'      => Product::where('stock', '<=', 10)->count(),
        ];

        // 2. RECENT ORDERS (Dengan relasi user)
        $recentOrders = Order::with('user')->latest()->take(8)->get();
        
        // Tambahkan nomor order virtual jika kolom order_number tidak ada di DB
        $recentOrders->each(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
            }
        });

        // 3. TOP SELLING PRODUCTS
        // Blade kamu memanggil $product->image_url dan $product->sold
        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['completed', 'delivered'])
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->take(6) // Blade kamu pakai col-md-2 (6 produk muat satu baris)
            ->get();

        // 4. REVENUE CHART (7 Hari Terakhir)
        // Blade kamu menggunakan pluck('date') dan pluck('total')
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereIn('status', ['completed', 'delivered'])
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get(); // Menghasilkan Collection yang punya fungsi pluck()

        // 5. KIRIM DATA KE VIEW
        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'topProducts', 
            'revenueChart'
        ));
    }
}
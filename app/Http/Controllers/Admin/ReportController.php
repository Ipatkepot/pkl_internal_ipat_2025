<?php

// app/Http/Controllers/Admin/ReportController.php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan di browser.
     * Fitur:
     * 1. Filter Rentang Tanggal (Date Range)
     * 2. Summary Statistik (Total Order & Omset)
     * 3. Grafik/Analitik Penjualan per Kategori
     * 4. Tabel Detail Transaksi dengan Pagination
     */
    public function sales(Request $request)
    {
        // Default: Bulan ini (dari tanggal 1 sampai hari ini)
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo   = $request->date_to ?? Carbon::now()->toDateString();

        // Konversi ke Carbon untuk filter akurat
        $start = Carbon::parse($dateFrom)->startOfDay();
        $end   = Carbon::parse($dateTo)->endOfDay();

        // 1. Query Tabel Detail (dengan pagination)
        $orders = Order::with(['items', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->latest()
            ->paginate(20)
            ->appends($request->query()); // Biar filter tanggal tetap saat ganti halaman

        // 2. Query Summary (total order & revenue di periode)
        $summary = Order::whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as total_revenue')
            ->first();

        // Safe access kalau tidak ada data
        $totalOrders  = $summary->total_orders ?? 0;
        $totalRevenue = $summary->total_revenue ?? 0;

        // 3. Query Analitik: Penjualan per Kategori
        $byCategory = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'), // Lebih akurat: quantity x price
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->orderByDesc('total_sales')
            ->limit(10) // Opsional: batasi 10 kategori teratas biar gak overload
            ->get();

        return view('admin.reports.sales', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'byCategory',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Handle export ke Excel
     */
    public function exportSales(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'date_from' => 'required|date',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        $fromFormatted = Carbon::parse($dateFrom)->format('d-m-Y');
        $toFormatted   = Carbon::parse($dateTo)->format('d-m-Y');

        $filename = "Laporan_Penjualan_{$fromFormatted}_sd_{$toFormatted}.xlsx";

        return Excel::download(
            new SalesReportExport($dateFrom, $dateTo),
            $filename
        );
    }
}

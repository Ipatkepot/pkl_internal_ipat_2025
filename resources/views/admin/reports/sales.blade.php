{{-- resources/views/admin/reports/sales.blade.php --}}

@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Laporan Penjualan</h2>
    </div>

    <!-- Form Filter & Export -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control" required>
                </div>
                <div class="col-md-6 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>

                    <!-- Export Button (pakai GET dengan parameter tanggal) -->
                    <a href="{{ route('admin.reports.export-sales', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                       class="btn btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Pendapatan -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Pendapatan</div>
                    <div class="h3 fw-bold text-dark mb-0">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                    <small class="text-muted">Periode {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Total Transaksi</div>
                    <div class="h3 fw-bold text-dark mb-0">
                        {{ number_format($totalOrders) }}
                    </div>
                    <small class="text-muted">Order berstatus paid</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales By Category -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Performa Kategori</h5>
                </div>
                <div class="card-body">
                    @if($byCategory->isEmpty())
                        <p class="text-muted text-center">Tidak ada data penjualan kategori pada periode ini.</p>
                    @else
                        @foreach($byCategory as $cat)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-medium">{{ $cat->category_name }}</span>
                                    <span class="fw-bold">Rp {{ number_format($cat->total_sales ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $totalRevenue > 0 ? ($cat->total_sales / $totalRevenue) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Rincian Transaksi</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none text-primary">
                                            #{{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $order->user->name ?? 'Guest' }}</div>
                                        <div class="small text-muted">{{ $order->user->email ?? '-' }}</div>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        Tidak ada data penjualan pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="card-footer bg-white border-top-0">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
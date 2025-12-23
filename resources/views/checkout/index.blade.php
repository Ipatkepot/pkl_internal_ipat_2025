{{-- resources/views/admin/orders/index.blade.php --}}

@extends('layouts.admin')  {{-- Ganti dari <x-app-layout> --}}

@section('title', 'Daftar Pesanan - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Daftar Pesanan</h2>
        <small class="text-muted">Total: {{ $orders->total() }} pesanan</small>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No. Invoice</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold">
                                        #{{ $order->invoice_number }}
                                    </a>
                                </td>
                                <td>
                                    {{ $order->user->name }}<br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d M Y') }}<br>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>{{ $order->items->count() }} produk</td>
                                <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_badge_color }}">
                                        {{ $order->status_text }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->payment)
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($orders->hasPages())
            <div class="card-footer bg-white">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
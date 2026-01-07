<?php
namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    public function query()
    {
        $start = Carbon::parse($this->dateFrom)->startOfDay();
        $end   = Carbon::parse($this->dateTo)->endOfDay();

        return Order::query()
            ->with(['user', 'items'])
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc');
    }

    public function headings(): array
    {
        return [
            'No. Order',
            'Tanggal Transaksi',
            'Nama Customer',
            'Email',
            'Jumlah Item',
            'Total Belanja (Rp)',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number,
            $order->created_at->format('d/m/Y H:i'),
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->items->sum('quantity'),
            $order->total_amount,
            ucfirst($order->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

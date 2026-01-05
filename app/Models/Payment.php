<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi (Mass Assignable).
     * Sesuaikan dengan tabel payments kamu.
     */
    protected $fillable = [
        'order_id',
        'transaction_id', // ID dari Midtrans/Gateway
        'payment_type',   // bank_transfer, gopay, dll
        'amount',
        'status',      // pending, success, settled, failure
        'payment_url', // Snap URL jika pakai Midtrans
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'amount'     => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi Balik ke Order
     * Payment ini dimiliki oleh satu Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

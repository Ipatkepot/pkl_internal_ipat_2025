<?php
namespace App\Http\Controllers;

use App\Events\OrderPaidEvent;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification Received', $payload);

        $rawOrderId        = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;

        // 1. Validasi Payload Dasar
        if (! $rawOrderId || ! $transactionStatus || ! $signatureKey) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Validasi Signature (Keamanan)
        $serverKey         = config('midtrans.server_key');
        $expectedSignature = hash("sha512", $rawOrderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', ['order_id' => $rawOrderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ==========================================================
        // 3. FIX: PECAH ORDER ID (Gabungin ORD + Kode Unik)
        // ==========================================================
        $parts = explode('-', $rawOrderId);
        // $parts[0] = ORD, $parts[1] = 695B232DBFBBD
        $orderNumber = $parts[0] . '-' . $parts[1];

        Log::info('Mencari Order Number di DB: ' . $orderNumber);

        // Cari Data Order berdasarkan order_number asli
        $order = Order::where('order_number', $orderNumber)->with('payment', 'items.product')->first();

        if (! $order) {
            Log::error('Midtrans Notification: Order Not Found', ['order_number' => $orderNumber]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 4. Idempotency (Jangan proses kalau sudah sukses/batal)
        if (in_array($order->status, ['processing', 'shipped', 'completed', 'cancelled'])) {
            Log::info("Order {$orderNumber} sudah pernah diproses sebelumnya.");
            return response()->json(['message' => 'Order already processed'], 200);
        }

        // 5. Update Payment Info (Log transaksi Midtrans)
        if ($order->payment) {
            $order->payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_type'            => $paymentType,
                'raw_response'            => json_encode($payload),
            ]);
        }

        // 6. Mapping Status
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $this->handlePending($order, 'Review fraud diperlukan');
                } else {
                    $this->handleSuccess($order);
                }
                break;

            case 'settlement':
                $this->handleSuccess($order);
                break;

            case 'pending':
                $this->handlePending($order, 'Menunggu pembayaran');
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $this->handleFailed($order, $transactionStatus);
                break;

            case 'refund':
            case 'partial_refund':
                $this->handleRefund($order);
                break;
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }

    protected function handleSuccess(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // Update Order ke Processing
            $order->update([
                'status'         => 'processing',
                'payment_status' => 'paid',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'status'  => 'success',
                    'paid_at' => now(),
                ]);
            }
            Log::info("Order {$order->order_number} BERHASIL diupdate ke PAID.");
        });

        // Trigger event jika ada
        if (class_exists(\App\Events\OrderPaidEvent::class)) {
            event(new OrderPaidEvent($order));
        }
    }

    protected function handlePending(Order $order, string $message): void
    {
        if ($order->payment) {
            $order->payment->update(['status' => 'pending']);
        }
    }

    protected function handleFailed(Order $order, string $reason): void
    {
        DB::transaction(function () use ($order, $reason) {
            $order->update([
                'status'         => 'cancelled',
                'payment_status' => 'failed',
            ]);

            if ($order->payment) {
                $order->payment->update(['status' => 'failed']);
            }

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        });

        Log::info("Order {$order->order_number} marked as failed. Reason: {$reason}");
    }

    protected function handleRefund(Order $order): void
    {
        if ($order->payment) {
            $order->payment->update(['status' => 'refunded']);
        }
    }
}

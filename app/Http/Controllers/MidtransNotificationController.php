<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPaidEvent; // Pastikan event ini dibuat

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification Received', $payload);

        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;

        // 1. Validasi Payload
        if (!$orderId || !$transactionStatus || !$signatureKey) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Validasi Signature (Keamanan)
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 3. Cari Data Order
        $order = Order::where('order_number', $orderId)->with('payment', 'items.product')->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 4. Idempotency (Cegah proses ulang jika status sudah final)
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            return response()->json(['message' => 'Order already processed'], 200);
        }

        // 5. Update Payment Info
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
                    $this->handlePending($order, 'Menunggu review fraud');
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
            $order->update(['status' => 'processing']);
            
            if ($order->payment) {
                $order->payment->update([
                    'status'  => 'success',
                    'paid_at' => now(),
                ]);
            }
        });

        // Trigger event untuk kirim email/notif
        event(new OrderPaidEvent($order));
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
            $order->update(['status' => 'cancelled']);

            if ($order->payment) {
                $order->payment->update(['status' => 'failed']);
            }

            // Restock Logic
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
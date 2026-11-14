<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Menerapkan hasil dari Midtrans (webhook atau Status API) ke pesanan.
     * Mengembalikan false bila nominal tidak cocok sehingga data ditolak.
     */
    public function apply(Order $order, array $payload): bool
    {
        // Nominal dari Midtrans harus sama dengan nominal di database.
        $amount = (int) round((float) ($payload['gross_amount'] ?? 0));

        if ($amount !== (int) $order->total_amount) {
            Log::warning('Nominal Midtrans tidak cocok dengan pesanan', [
                'order' => $order->order_code,
                'midtrans' => $amount,
                'database' => $order->total_amount,
            ]);

            return false;
        }

        $newStatus = $this->mapStatus($payload);

        DB::transaction(function () use ($order, $payload, $amount, $newStatus) {
            // Baris pesanan dikunci agar webhook dan pengecekan status tidak berebut.
            $locked = Order::whereKey($order->id)->lockForUpdate()->with('items')->firstOrFail();

            // Setiap notifikasi unik dicatat sebagai jejak audit; yang sama tidak dicatat dua kali.
            Payment::firstOrCreate(
                [
                    'order_id' => $locked->id,
                    'transaction_id' => $payload['transaction_id'] ?? null,
                    'transaction_status' => $payload['transaction_status'] ?? 'unknown',
                ],
                [
                    'payment_type' => $payload['payment_type'] ?? null,
                    'fraud_status' => $payload['fraud_status'] ?? null,
                    'gross_amount' => $amount,
                    'raw_notification' => $payload,
                    'transaction_time' => $payload['transaction_time'] ?? null,
                ]
            );

            // Notifikasi bisa datang tidak berurutan; status akhir tidak boleh ditimpa.
            if (in_array($locked->payment_status, ['paid', 'failed', 'expired'], true)) {
                return;
            }

            // Status tak dikenal (misalnya refund) hanya dicatat di tabel payments.
            if ($newStatus === null) {
                return;
            }

            if ($newStatus === 'paid') {
                $locked->markAsPaid();

                return;
            }

            $locked->markPaymentStatus($newStatus);

            if (in_array($newStatus, ['failed', 'expired'], true)) {
                $locked->update(['order_status' => 'cancelled']);

                // Stok yang dipesan saat checkout dikembalikan, satu kali saja
                // karena status akhir sudah dijaga di atas.
                foreach ($locked->items as $item) {
                    Product::withTrashed()->whereKey($item->product_id)
                        ->increment('stock', $item->quantity);
                }
            }
        });

        return true;
    }

    private function mapStatus(array $payload): ?string
    {
        $fraud = $payload['fraud_status'] ?? null;

        return match ($payload['transaction_status'] ?? '') {
            // capture + challenge menunggu keputusan pihak penilai risiko, jadi tetap pending.
            'capture' => $fraud === 'accept' ? 'paid' : 'pending',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel' => 'failed',
            'expire' => 'expired',
            default => null,
        };
    }
}
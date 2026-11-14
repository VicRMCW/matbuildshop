<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Throwable;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = (string) config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapToken(Order $order): string
    {
        $order->loadMissing('items', 'user');

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                // Nominal dari database, bukan dari browser.
                'gross_amount' => (int) $order->total_amount,
            ],
            // Midtrans menolak transaksi bila jumlah item_details tidak sama dengan gross_amount.
            'item_details' => $order->items->map(fn ($item) => [
                'id' => (string) $item->product_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => Str::limit($item->product_name, 50, ''),
            ])->values()->all(),
            'customer_details' => [
                'first_name' => Str::limit($order->recipient_name, 50, ''),
                'email' => $order->user->email,
                'phone' => $order->phone,
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Menanyakan status langsung ke Midtrans memakai Server Key.
     * Null berarti belum ada transaksi (pembeli belum memilih metode bayar) atau Midtrans tidak terjangkau.
     */
    public function fetchStatus(string $orderCode): ?array
    {
        try {
            return (array) Transaction::status($orderCode);
        } catch (Throwable $e) {
            // 404 wajar: transaksi baru dibuat Midtrans setelah pembeli memilih metode bayar.
            if ($e->getCode() !== 404) {
                report($e);
            }

            return null;
        }
    }

    public function isValidSignature(array $payload): bool
    {
        $expected = hash('sha512',
            ($payload['order_id'] ?? '')
            . ($payload['status_code'] ?? '')
            . ($payload['gross_amount'] ?? '')
            . config('midtrans.server_key')
        );

        return hash_equals($expected, (string) ($payload['signature_key'] ?? ''));
    }
}
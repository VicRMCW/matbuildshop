<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    public function show(
        Request $request,
        Order $order,
        MidtransService $midtrans,
        PaymentService $payments
    ): View {
        // Mencegah pelanggan membuka pesanan milik orang lain.
        abort_unless($order->user_id === $request->user()->id, 403);

        // Jaring pengaman bila webhook belum sampai (misalnya saat demo di localhost).
        if ($order->payment_status === 'pending') {
            $status = $midtrans->fetchStatus($order->order_code);

            if ($status) {
                $payments->apply($order, $status);
                $order->refresh();
            }
        }

        $snapToken = null;

        if ($order->payment_status === 'pending') {
            if (! $order->snap_token) {
                try {
                    $order->snap_token = $midtrans->createSnapToken($order);
                    $order->save();
                } catch (Throwable $e) {
                    report($e);
                    session()->now('error', 'Pembayaran belum dapat disiapkan. Muat ulang halaman ini.');
                }
            }

            $snapToken = $order->snap_token;
        }

        $order->load('items');

        return view('orders.show', compact('order', 'snapToken'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans, PaymentService $payments): JsonResponse
    {
        // Body mentah dibaca agar gross_amount tetap string persis seperti dikirim Midtrans.
        $payload = json_decode($request->getContent(), true);

        if (! is_array($payload) || ! $midtrans->isValidSignature($payload)) {
            Log::warning('Notifikasi Midtrans ditolak: signature tidak valid', ['ip' => $request->ip()]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_code', $payload['order_id'] ?? '')->first();

        if (! $order) {
            Log::info('Notifikasi Midtrans untuk pesanan yang tidak dikenal diabaikan', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Order not found, ignored']);
        }

        if (! $payments->apply($order, $payload)) {
            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        return response()->json(['message' => 'OK']);
    }
}
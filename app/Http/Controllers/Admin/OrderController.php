<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const PAYMENT_STATUSES = ['pending', 'paid', 'failed', 'expired'];
    private const ORDER_STATUSES = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

    // Alur pemrosesan hanya maju satu langkah; tidak ada jalan mundur atau melompat.
    private const NEXT_STATUS = [
        'pending' => 'processing',
        'processing' => 'shipped',
        'shipped' => 'completed',
    ];

    public function index(Request $request): View
    {
        $q = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $payment = $request->query('pembayaran');
        $process = $request->query('proses');

        $orders = Order::with('user')
            ->withCount('items')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('order_code', 'like', "%{$q}%")
                        ->orWhere('recipient_name', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
                });
            })
            // Nilai filter dicocokkan dengan daftar putih agar input asing diabaikan.
            ->when(
                in_array($payment, self::PAYMENT_STATUSES, true),
                fn ($query) => $query->where('payment_status', $payment)
            )
            ->when(
                in_array($process, self::ORDER_STATUSES, true),
                fn ($query) => $query->where('order_status', $process)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'q', 'payment', 'process'));
    }

    public function show(Order $order): View
    {
        $order->load('items', 'user', 'payments');

        // Pesanan hanya boleh diproses setelah pembayaran diterima.
        $nextStatus = $order->payment_status === 'paid'
            ? (self::NEXT_STATUS[$order->order_status] ?? null)
            : null;

        return view('admin.orders.show', compact('order', 'nextStatus'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['current_status' => ['required', 'string']]);

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Status pemrosesan hanya dapat diubah setelah pembayaran diterima.');
        }

        // Mencegah loncat dua langkah bila tombol ditekan dua kali atau halaman sudah usang.
        if ($order->order_status !== $request->input('current_status')) {
            return back()->with('error', 'Status pesanan sudah berubah. Muat ulang halaman ini.');
        }

        $next = self::NEXT_STATUS[$order->order_status] ?? null;

        if ($next === null) {
            return back()->with('error', 'Pesanan ini sudah berada di status akhir.');
        }

        // Hanya order_status yang diubah; payment_status tidak pernah disentuh dari sini.
        $order->update(['order_status' => $next]);

        return back()->with('success', "Status pesanan {$order->order_code} diubah menjadi {$order->order_status_label}.");
    }
}
@extends('layouts.admin', ['title' => 'Pesanan ' . $order->order_code . ' - Admin MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
    $lastPayment = $order->payments->sortByDesc('id')->first();
    $buttonLabels = [
        'processing' => 'Tandai diproses',
        'shipped' => 'Tandai dikirim',
        'completed' => 'Tandai selesai',
    ];
@endphp

<div class="page-header">
    <div>
        <h1>Pesanan {{ $order->order_code }}</h1>
        <p>Dibuat {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali ke daftar</a>
</div>

<div class="order-info-grid">
    <div class="card">
        <h2 class="card-title">Pelanggan</h2>
        <p class="order-line">{{ $order->user->name }}</p>
        <p class="order-line">{{ $order->user->email }}</p>
        @if ($order->user->phone)
            <p class="order-line">{{ $order->user->phone }}</p>
        @endif
    </div>

    <div class="card">
        <h2 class="card-title">Pengiriman</h2>
        <p class="order-line">{{ $order->recipient_name }}</p>
        <p class="order-line">{{ $order->phone }}</p>
        <p class="order-line">{{ $order->shipping_address }}</p>
    </div>

    <div class="card">
        <h2 class="card-title">Pembayaran</h2>
        <p class="order-line">
            <span class="order-badge order-badge--{{ $order->payment_status }}">{{ $order->payment_status_label }}</span>
        </p>
        <p class="order-line">Total: <strong>{{ $rupiah($order->total_amount) }}</strong></p>
        @if ($order->paid_at)
            <p class="order-line">Dibayar: {{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</p>
        @endif
        @if ($lastPayment && $lastPayment->payment_type)
            <p class="order-line">Metode: {{ \Illuminate\Support\Str::headline($lastPayment->payment_type) }}</p>
        @endif
        <p class="order-line">
            Status pembayaran diperbarui otomatis dari Midtrans dan tidak dapat diubah dari halaman ini.
        </p>
    </div>

    <div class="card">
        <h2 class="card-title">Pemrosesan</h2>
        <p class="order-line">
            <span class="order-badge order-badge--{{ $order->order_status }}">{{ $order->order_status_label }}</span>
        </p>

        <div class="status-action">
            @if ($nextStatus)
                <form method="POST" action="{{ route('admin.orders.status', ['order' => $order->order_code]) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="current_status" value="{{ $order->order_status }}">
                    <button type="submit" class="btn btn-primary">{{ $buttonLabels[$nextStatus] }}</button>
                </form>
            @elseif ($order->payment_status === 'paid')
                <p class="order-line">Pesanan sudah berada di status akhir.</p>
            @elseif ($order->payment_status === 'pending')
                <p class="order-line">Menunggu pembayaran diterima sebelum bisa diproses.</p>
            @else
                <p class="order-line">Pesanan dibatalkan karena pembayaran {{ strtolower($order->payment_status_label) }}.</p>
            @endif
        </div>
    </div>
</div>

<h2 class="card-title">Barang yang dipesan</h2>
<div class="order-table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $rupiah($item->price) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $rupiah($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>{{ $rupiah($order->total_amount) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<h2 class="card-title">Riwayat notifikasi pembayaran</h2>
<div class="order-table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Status Midtrans</th>
                <th>Metode</th>
                <th>ID transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->payments->sortBy('id') as $payment)
                <tr>
                    <td>{{ $payment->transaction_time ? \Carbon\Carbon::parse($payment->transaction_time)->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $payment->transaction_status }}</td>
                    <td>{{ $payment->payment_type ? \Illuminate\Support\Str::headline($payment->payment_type) : '-' }}</td>
                    <td>{{ $payment->transaction_id ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada notifikasi pembayaran dari Midtrans.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
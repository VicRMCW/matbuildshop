@extends('layouts.app', ['title' => 'Invoice ' . $order->order_code . ' - MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
    $lastPayment = $order->payments->sortByDesc('id')->first();
@endphp

<div class="shop-container">
    <div class="invoice-actions no-print">
        <button type="button" class="shop-btn shop-btn-primary" onclick="window.print()">Cetak invoice</button>
        <a href="{{ route('orders.show', ['order' => $order->order_code]) }}" class="shop-btn shop-btn-secondary">
            Kembali ke pesanan
        </a>
    </div>

    <article class="invoice">
        <div class="invoice-head">
            <div>
                <p class="invoice-brand">MatbuildShop</p>
                <p class="invoice-sub">Toko bangunan online</p>
            </div>
            <div class="invoice-meta">
                <p class="invoice-title">INVOICE</p>
                <p>{{ $order->order_code }}</p>
                <p>Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="invoice-parties">
            <div>
                <p class="invoice-label">Dikirim kepada</p>
                <p class="order-line"><strong>{{ $order->recipient_name }}</strong></p>
                <p class="order-line">{{ $order->phone }}</p>
                <p class="order-line">{{ $order->shipping_address }}</p>
            </div>
            <div>
                <p class="invoice-label">Status pembayaran</p>
                <p class="order-line">
                    <span class="order-badge order-badge--{{ $order->payment_status }}">
                        {{ $order->payment_status_label }}
                    </span>
                </p>
                @if ($order->payment_status === 'paid' && $order->paid_at)
                    <p class="order-line">Dibayar: {{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</p>
                @endif
                @if ($lastPayment && $lastPayment->payment_type)
                    <p class="order-line">Metode: {{ \Illuminate\Support\Str::headline($lastPayment->payment_type) }}</p>
                @endif
                <p class="order-line">Akun: {{ $order->user->name }} ({{ $order->user->email }})</p>
            </div>
        </div>

        <div class="order-table-wrap">
            <table class="order-table">
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
                        <td colspan="3">Total</td>
                        <td>{{ $rupiah($order->total_amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <p class="invoice-foot">
            Invoice ini dibuat otomatis oleh sistem MatbuildShop sebagai bukti pesanan.
        </p>
    </article>
</div>
@endsection
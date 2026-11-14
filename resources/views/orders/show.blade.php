@extends('layouts.app', ['title' => 'Pesanan ' . $order->order_code . ' - MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
@endphp

<div class="shop-container">
    <h1 class="cart-heading">Pesanan {{ $order->order_code }}</h1>

    <div class="order-badges">
        <span class="order-badge order-badge--{{ $order->payment_status }}">
            {{ $order->payment_status_label }}
        </span>
        <span class="order-badge order-badge--{{ $order->order_status }}">
            {{ $order->order_status_label }}
        </span>
    </div>

    <div class="order-info-grid">
        <div class="card">
            <h2 class="card-title">Penerima</h2>
            <p class="order-line">{{ $order->recipient_name }}</p>
            <p class="order-line">{{ $order->phone }}</p>
            <p class="order-line">{{ $order->shipping_address }}</p>
        </div>

        <div class="card">
            <h2 class="card-title">Informasi pesanan</h2>
            <p class="order-line">Tanggal: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p class="order-line">Total: <strong>{{ $rupiah($order->total_amount) }}</strong></p>
        </div>
    </div>

    <h2 class="card-title">Barang yang dipesan</h2>
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

    @if ($order->payment_status === 'pending')
        {{-- Tombol bayar Midtrans dipasang di sini pada Tahap 9. --}}
        <p class="order-payment-note">Pesanan ini menunggu pembayaran.</p>
    @endif

    <p><a href="{{ route('shop.home') }}" class="checkout-back">Kembali ke katalog</a></p>
</div>
@endsection
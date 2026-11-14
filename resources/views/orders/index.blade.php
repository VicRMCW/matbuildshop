@extends('layouts.app', ['title' => 'Pesanan Saya - MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
@endphp

<div class="shop-container">
    <h1 class="cart-heading">Pesanan Saya</h1>

    @if ($orders->isEmpty())
        <div class="shop-empty-state">
            <h2 class="shop-empty-title">Belum ada pesanan</h2>
            <p class="shop-empty-text">Pesanan yang kamu buat akan muncul di sini.</p>
            <a href="{{ route('shop.catalog') }}" class="shop-btn shop-btn-primary">Lihat katalog</a>
        </div>
    @else
        <div class="order-table-wrap">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Pemrosesan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', ['order' => $order->order_code]) }}">{{ $order->order_code }}</a>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td>{{ $rupiah($order->total_amount) }}</td>
                            <td>
                                <span class="order-badge order-badge--{{ $order->payment_status }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </td>
                            <td>
                                <span class="order-badge order-badge--{{ $order->order_status }}">
                                    {{ $order->order_status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', ['order' => $order->order_code]) }}"
                                   class="shop-btn shop-btn-sm shop-btn-secondary">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pakai pemanggilan pagination yang sama dengan halaman katalog (lihat shop/home.blade.php). --}}
        {{ $orders->links() }}
    @endif
</div>
@endsection
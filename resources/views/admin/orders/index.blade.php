@extends('layouts.admin', ['title' => 'Pesanan - Admin MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
@endphp

<div class="page-header">
    <div>
        <h1>Daftar Pesanan</h1>
        <p>Semua pesanan pelanggan, lengkap dengan status pembayaran dan pemrosesan.</p>
    </div>
</div>

<form method="GET" action="{{ route('admin.orders.index') }}" class="filter-bar">
    <div class="field">
        <label for="q">Cari</label>
        <input type="search" id="q" name="q" class="input" value="{{ $q }}" placeholder="Kode, penerima, atau pelanggan">
    </div>
    <div class="field">
        <label for="pembayaran">Pembayaran</label>
        <select id="pembayaran" name="pembayaran" class="input">
            <option value="">Semua</option>
            @foreach (['pending' => 'Menunggu pembayaran', 'paid' => 'Dibayar', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $value => $label)
                <option value="{{ $value }}" @selected($payment === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <label for="proses">Pemrosesan</label>
        <select id="proses" name="proses" class="input">
            <option value="">Semua</option>
            @foreach (['pending' => 'Menunggu diproses', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
                <option value="{{ $value }}" @selected($process === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Terapkan</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
</form>

<div class="order-table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Item</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Pemrosesan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="cell-nowrap">{{ $order->order_code }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td class="cell-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td class="cell-nowrap">{{ $rupiah($order->total_amount) }}</td>
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
                        <a href="{{ route('admin.orders.show', ['order' => $order->order_code]) }}"
                           class="btn btn-sm btn-secondary">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada pesanan yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection
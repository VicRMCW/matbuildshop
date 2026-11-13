@extends('layouts.admin', ['title' => 'Dashboard - Admin MatbuildShop'])

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Ringkasan Toko</h1>
    <p style="font-size: 0.9rem; color: #666;">Statistik operasional data material dan perkakas bangunan.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Produk Aktif</div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Kategori</div>
        <div class="stat-value">{{ $stats['total_categories'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pelanggan</div>
        <div class="stat-value">{{ $stats['total_customers'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Menunggu Pembayaran</div>
        <div class="stat-value accent">{{ $stats['orders_pending'] }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pesanan Terbayar</div>
        <div class="stat-value">{{ $stats['orders_paid'] }}</div>
    </div>
</div>
@endsection

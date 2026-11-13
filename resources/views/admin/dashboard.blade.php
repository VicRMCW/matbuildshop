@extends('layouts.admin', ['title' => 'Dashboard - Admin MatbuildShop'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Ringkasan Toko</h1>
        <p class="page-subtitle">Statistik operasional data material dan perkakas bangunan.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-text-muted); margin-bottom: 8px;">
            Total Produk Aktif
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-text); line-height: 1;">
            {{ $stats['total_products'] }}
        </div>
    </div>

    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-text-muted); margin-bottom: 8px;">
            Total Kategori
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-text); line-height: 1;">
            {{ $stats['total_categories'] }}
        </div>
    </div>

    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-text-muted); margin-bottom: 8px;">
            Total Pelanggan
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-text); line-height: 1;">
            {{ $stats['total_customers'] }}
        </div>
    </div>

    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-text-muted); margin-bottom: 8px;">
            Total Pesanan
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-text); line-height: 1;">
            {{ $stats['total_orders'] }}
        </div>
    </div>

    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-accent); margin-bottom: 8px;">
            Menunggu Pembayaran
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-accent); line-height: 1;">
            {{ $stats['orders_pending'] }}
        </div>
    </div>

    <div style="background: var(--adm-surface); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px;">
        <div style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: var(--adm-text-muted); margin-bottom: 8px;">
            Pesanan Terbayar
        </div>
        <div style="font-size: 32px; font-weight: 700; color: var(--adm-text); line-height: 1;">
            {{ $stats['orders_paid'] }}
        </div>
    </div>
</div>
@endsection

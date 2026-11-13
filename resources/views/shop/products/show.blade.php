@extends('layouts.app', ['title' => $product->name . ' - MatbuildShop'])

@section('content')
<div class="shop-container">
    {{-- Breadcrumb Navigasi: Katalog > Kategori > Nama Produk --}}
    <nav class="shop-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('shop.home') }}">Katalog</a>
        <span class="shop-breadcrumb-sep" aria-hidden="true">&rsaquo;</span>
        @if ($product->category)
            <a href="{{ route('shop.home', ['kategori' => $product->category->slug]) }}">
                {{ $product->category->name }}
            </a>
            <span class="shop-breadcrumb-sep" aria-hidden="true">&rsaquo;</span>
        @endif
        <span class="shop-breadcrumb-current" aria-current="page">{{ $product->name }}</span>
    </nav>

    {{-- Detail Produk 2 Kolom --}}
    <article class="product-detail-layout">
        <div class="product-detail-media">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Foto {{ $product->name }}" width="480" height="360" fetchpriority="high">
            @else
                <div class="product-card-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;" title="Foto produk tidak tersedia">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                        <circle cx="9" cy="9" r="2"/>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                    </svg>
                </div>
            @endif
        </div>

        <div class="product-detail-info">
            <h1 class="product-detail-title">{{ $product->name }}</h1>

            <div class="product-detail-category">
                Kategori:
                @if ($product->category)
                    <a href="{{ route('shop.home', ['kategori' => $product->category->slug]) }}" style="color: var(--shop-accent); text-decoration: none; font-weight: 500;">
                        {{ $product->category->name }}
                    </a>
                @else
                    <span>Tidak terkategori</span>
                @endif
            </div>

            <div class="product-detail-price">
                {{ $product->formatted_price }}
            </div>

            <div class="product-detail-stock-box {{ $product->stock === 0 ? 'out' : '' }}">
                @if ($product->stock > 0)
                    <span>Status Stok:</span>
                    <strong style="color: var(--shop-text);">Tersedia ({{ $product->stock }} unit)</strong>
                @else
                    <strong>Stok Habis</strong>
                @endif
            </div>

            <div class="product-detail-desc">
                <h2 style="font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--shop-text); margin-bottom: 8px;">
                    Deskripsi Produk
                </h2>
                <div style="color: #2C2C2C; line-height: 1.65; white-space: pre-line;">
                    {{ $product->description }}
                </div>
            </div>
        </div>
    </article>
</div>
@endsection

@extends('layouts.app', ['title' => 'Katalog Bahan & Alat Bangunan - MatbuildShop'])

@section('content')
<div class="shop-container">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 700; color: var(--shop-text); margin-bottom: 6px; letter-spacing: -0.01em;">
            Katalog Produk
        </h1>
        <p style="font-size: 16px; color: var(--shop-text-muted); margin: 0;">
            Kebutuhan semen, besi, cat, pipa, dan perkakas konstruksi.
        </p>
    </div>

    {{-- Form Pencarian Publik --}}
    <section class="shop-search-section" aria-label="Pencarian Produk">
        <form method="GET" action="{{ route('shop.catalog') }}" class="shop-search-form">
            @if(!empty($activeCategorySlug))
                <input type="hidden" name="kategori" value="{{ $activeCategorySlug }}">
            @endif

            <label for="search-input" class="sr-only" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;">
                Cari material atau alat bangunan
            </label>
            <input type="text" id="search-input" name="q" value="{{ $q }}" placeholder="Cari nama semen, besi, atau perkakas..." class="shop-search-input">
            <button type="submit" class="shop-btn shop-btn-primary">
                Cari
            </button>

            @if($q)
                <a href="{{ route('shop.catalog', array_filter(['kategori' => $activeCategorySlug])) }}" class="shop-btn shop-btn-secondary" style="padding: 0 16px;">
                    Reset
                </a>
            @endif
        </form>
    </section>

    {{-- Layout Samping: Filter Kategori & Grid Produk --}}
    <div class="shop-layout">
        {{-- Filter Kategori --}}
        <aside class="shop-sidebar" aria-label="Filter Kategori">
            <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--shop-text-muted); margin-bottom: 12px;">
                Kategori
            </div>
            <div class="category-chip-list">
                <a href="{{ route('shop.catalog', array_filter(['q' => $q])) }}" class="category-item {{ empty($activeCategorySlug) ? 'active' : '' }}">
                    <span>Semua kategori</span>
                </a>

                @foreach ($categories as $cat)
                    <a href="{{ route('shop.catalog', array_filter(['kategori' => $cat->slug, 'q' => $q])) }}" class="category-item {{ $activeCategorySlug === $cat->slug ? 'active' : '' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="category-count">({{ $cat->products_count }})</span>
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Area Hasil Produk --}}
        <section class="shop-main-content" aria-label="Daftar Produk">
            {{-- Ringkasan Hasil --}}
            <div style="font-size: 15px; color: var(--shop-text-muted); margin-bottom: 16px;">
                @if($q)
                    Menampilkan hasil untuk <strong>"{{ $q }}"</strong>
                    @if($activeCategorySlug)
                        pada kategori <strong>{{ $categories->firstWhere('slug', $activeCategorySlug)?->name }}</strong>
                    @endif
                    ({{ $products->total() }} produk ditemukan)
                @elseif($activeCategorySlug)
                    Kategori: <strong>{{ $categories->firstWhere('slug', $activeCategorySlug)?->name }}</strong>
                    ({{ $products->total() }} produk)
                @else
                    Semua Produk ({{ $products->total() }})
                @endif
            </div>

            @if ($products->count() > 0)
                <div class="product-grid">
                    @foreach ($products as $product)
                        @include('shop._card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination Menggunakan View Terpusat --}}
                <div style="margin-top: 24px;">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
            @else
                <div class="shop-empty-state">
                    <h2 class="shop-empty-title">Produk tidak ditemukan</h2>
                    <p class="shop-empty-text">Tidak ada produk yang sesuai dengan pencarian atau filter yang dipilih.</p>
                    <a href="{{ route('shop.catalog') }}" class="shop-btn shop-btn-secondary">
                        Reset pencarian
                    </a>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection

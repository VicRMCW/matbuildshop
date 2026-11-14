@extends('layouts.app', ['title' => 'MatbuildShop - Bahan dan Alat Bangunan'])

@section('content')
<div class="shop-container">
    {{-- 1. Hero Section --}}
    <section class="landing-hero" aria-labelledby="hero-heading">
        <h1 id="hero-heading" class="landing-hero-title">
            Bahan dan alat bangunan dalam satu toko
        </h1>
        <p class="landing-hero-desc">
            Cari semen, besi, cat, pipa, alat tangan, dan alat listrik, lalu pesan dan bayar dari sini.
        </p>

        <form method="GET" action="{{ route('shop.catalog') }}" class="landing-search-form" role="search">
            <label for="landing-search" class="visually-hidden">Cari produk</label>
            <input type="search" id="landing-search" name="q" maxlength="100"
                   placeholder="Cari produk, misalnya semen atau obeng"
                   class="landing-search-input">
            <button type="submit" class="shop-btn shop-btn-primary">
                Cari
            </button>
        </form>
    </section>

    {{-- 2. Kategori Section --}}
    <section class="landing-section" aria-labelledby="kategori-heading">
        <div class="landing-section-header">
            <h2 id="kategori-heading" class="landing-section-title">Kategori</h2>
        </div>

        <div class="category-grid">
            @foreach ($categories as $category)
                <a href="{{ route('shop.catalog', ['kategori' => $category->slug]) }}" class="category-card">
                    <div class="category-avatar">
                        @if ($category->cover_image)
                            <img src="{{ asset('storage/' . $category->cover_image) }}" alt="" class="category-avatar-img">
                        @else
                            {{-- Inisial huruf pertama ditampilkan bila belum ada gambar produk aktif di kategori ini --}}
                            <span class="category-avatar-initial" aria-hidden="true">
                                {{ mb_substr($category->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <span class="category-card-name">{{ $category->name }}</span>
                    <span class="category-card-count">{{ $category->products_count }} produk</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- 3. Produk Terbaru Section --}}
    <section class="landing-section" aria-labelledby="produk-terbaru-heading">
        <div class="landing-section-header">
            <h2 id="produk-terbaru-heading" class="landing-section-title">Produk terbaru</h2>
            <a href="{{ route('shop.catalog') }}" class="landing-section-link">
                Lihat semua produk
            </a>
        </div>

        @if ($latestProducts->count() > 0)
            <div class="landing-product-grid">
                @foreach ($latestProducts as $product)
                    @include('shop._card', ['product' => $product])
                @endforeach
            </div>
        @else
            <p style="color: var(--shop-text-muted); font-size: 16px; margin: 0;">Belum ada produk.</p>
        @endif
    </section>
</div>
@endsection

@extends('layouts.app', ['title' => 'MatbuildShop - Toko Bangunan Online'])

@section('content')
{{-- Gambar dikirim lewat variabel CSS agar path-nya memakai asset() Laravel --}}
<section class="landing-hero" style="--hero-image: url('{{ asset('images/hero-bangunan.png') }}');">
    <div class="shop-container">
        <div class="landing-hero-copy">
            <h1 class="landing-title">Bahan dan alat bangunan dalam satu toko</h1>
            <p class="landing-lead">
                Cari semen, besi, cat, pipa, alat tangan, dan alat listrik, lalu pesan dan bayar dari sini.
            </p>
            <form method="GET" action="{{ route('shop.catalog') }}" class="landing-search">
                <label for="landing-q" class="visually-hidden">Cari produk</label>
                <input type="search" id="landing-q" name="q" maxlength="100"
                       placeholder="Cari produk, misalnya semen atau obeng">
                <button type="submit" class="shop-btn shop-btn-primary">Cari</button>
            </form>
        </div>
    </div>
</section>

<div class="shop-container">

    <section class="landing-section" aria-labelledby="kategori-heading">
        <div class="landing-section-head">
            <h2 id="kategori-heading" class="landing-section-title">Kategori</h2>
        </div>

        <div class="category-grid">
            @foreach ($categories as $category)
                <a href="{{ route('shop.catalog', ['kategori' => $category->slug]) }}" class="category-tile">
                    @if ($category->cover_image)
                        {{-- alt kosong karena nama kategori sudah tertulis di bawah gambar --}}
                        <img src="{{ asset('storage/' . $category->cover_image) }}" alt=""
                             class="category-tile-img" loading="lazy">
                    @else
                        <span class="category-tile-img category-tile-img--empty" aria-hidden="true">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($category->name, 0, 1)) }}
                        </span>
                    @endif
                    <span class="category-tile-name">{{ $category->name }}</span>
                    <span class="category-tile-count">{{ $category->products_count }} produk</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="landing-section" aria-labelledby="terbaru-heading">
        <div class="landing-section-head">
            <h2 id="terbaru-heading" class="landing-section-title">Produk terbaru</h2>
            <a href="{{ route('shop.catalog') }}" class="landing-more">Lihat semua produk</a>
        </div>

        @if ($latestProducts->isEmpty())
            <p class="landing-empty">Belum ada produk.</p>
        @else
            <div class="landing-grid">
                @foreach ($latestProducts as $product)
                    @include('shop._card', ['product' => $product])
                @endforeach
            </div>
        @endif
    </section>

</div>
@endsection
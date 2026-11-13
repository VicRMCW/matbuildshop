@extends('layouts.app', ['title' => 'Katalog Toko - MatbuildShop'])

@section('content')
<div style="max-width: 1100px; margin: 0 auto;">
    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Katalog MatbuildShop</h1>
    <p style="color: #666; margin-bottom: 1.5rem;">Daftar material dan perkakas bangunan.</p>

    @if($q)
        <p style="font-size: 0.95rem; margin-bottom: 1rem;">Hasil pencarian: <strong>{{ $q }}</strong></p>
    @endif

    <div style="margin-bottom: 1.5rem;">
        <strong>Kategori:</strong>
        <a href="{{ route('shop.home') }}" style="margin-right: 0.75rem; {{ empty($activeCategorySlug) ? 'font-weight: bold; color: var(--accent);' : '' }}">Semua</a>
        @foreach($categories as $cat)
            <a href="{{ route('shop.home', array_merge(request()->query(), ['kategori' => $cat->slug])) }}"
               style="margin-right: 0.75rem; {{ $activeCategorySlug === $cat->slug ? 'font-weight: bold; color: var(--accent);' : '' }}">
                {{ $cat->name }} ({{ $cat->products_count }})
            </a>
        @endforeach
    </div>

    <ul style="list-style: none; padding: 0;">
        @forelse($products as $product)
            <li style="padding: 0.75rem; border-bottom: 1px solid #DDD;">
                <a href="{{ route('shop.products.show', $product) }}" style="font-size: 1.05rem; font-weight: 600; color: #1E1E1E; text-decoration: none;">
                    {{ $product->name }}
                </a>
                <div style="font-size: 0.9rem; color: #666;">
                    {{ $product->category?->name }} | {{ $product->formatted_price }} | Stok: {{ $product->stock }}
                </div>
            </li>
        @empty
            <li style="padding: 1.5rem; text-align: center; color: #888;">
                Tidak ada produk ditemukan.
            </li>
        @endforelse
    </ul>

    <div style="margin-top: 1.5rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection

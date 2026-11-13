{{-- Partial Kartu Produk: Seluruh area kartu merupakan tautan ke detail produk --}}
<a href="{{ route('shop.products.show', $product) }}" class="product-card">
    <div class="product-card-media">
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="Foto {{ $product->name }}" class="product-card-img" loading="lazy">
        @else
            <div class="product-card-placeholder" title="Foto produk tidak tersedia">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="product-card-body">
        <h2 class="product-card-title">{{ $product->name }}</h2>
        <div class="product-card-category">{{ $product->category?->name ?? 'Material Bangunan' }}</div>

        <div class="product-card-footer">
            <div class="product-card-price">{{ $product->formatted_price }}</div>
            @if ($product->stock > 0)
                <div class="product-card-stock">Stok: {{ $product->stock }}</div>
            @else
                <div class="product-card-stock out">Stok habis</div>
            @endif
        </div>
    </div>
</a>

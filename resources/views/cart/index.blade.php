@extends('layouts.app', ['title' => 'Keranjang - MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
@endphp

<div class="shop-container">
    <h1 class="cart-heading">Keranjang Belanja</h1>

    @if ($removedCount > 0)
        <div class="flash-alert flash-alert-error">
            {{ $removedCount }} produk sudah tidak tersedia dan dihapus dari keranjang.
        </div>
    @endif

    @if ($items->isEmpty())
        <div class="shop-empty-state">
            <h2 class="shop-empty-title">Keranjangmu masih kosong</h2>
            <p class="shop-empty-text">Pilih barang dari katalog untuk mulai berbelanja.</p>
            <a href="{{ route('shop.catalog') }}" class="shop-btn shop-btn-primary">Lihat katalog</a>
        </div>
    @else
        <div class="cart-layout">
            <ul class="cart-list">
                @foreach ($items as $item)
                    @php $product = $item->product; @endphp
                    <li class="cart-item">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}" class="cart-thumb">
                        @else
                            <div class="cart-thumb cart-thumb-empty" aria-hidden="true"></div>
                        @endif

                        <div>
                            <h2 class="cart-item-name">
                                <a href="{{ route('shop.products.show', ['product' => $product->slug]) }}">
                                    {{ $product->name }}
                                </a>
                            </h2>
                            <p class="cart-item-meta">
                                {{ $product->formatted_price }} per item &middot; stok {{ $product->stock }}
                            </p>

                            @if ($item->quantity > $product->stock)
                                <p class="field-error">
                                    Stok tersisa {{ $product->stock }}. Kurangi jumlahnya sebelum checkout.
                                </p>
                            @endif

                            <div class="cart-item-actions">
                                <div class="qty-control">
                                    <form method="POST" action="{{ route('cart.update', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                        <button type="submit" class="qty-btn"
                                                aria-label="Kurangi jumlah {{ $product->name }}"
                                                @disabled($item->quantity <= 1)>&minus;</button>
                                    </form>

                                    <span class="qty-value">{{ $item->quantity }}</span>

                                    <form method="POST" action="{{ route('cart.update', $item) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                        <button type="submit" class="qty-btn"
                                                aria-label="Tambah jumlah {{ $product->name }}"
                                                @disabled($item->quantity >= $product->stock)>+</button>
                                    </form>
                                </div>

                                <p class="cart-item-price">Subtotal {{ $rupiah($item->subtotal) }}</p>

                                <form method="POST" action="{{ route('cart.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="shop-btn shop-btn-sm shop-btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <aside class="cart-summary">
                <h2 class="cart-summary-title">Ringkasan</h2>
                <div class="cart-summary-row">
                    <span>Jumlah item</span>
                    <span>{{ $items->sum('quantity') }}</span>
                </div>
                <div class="cart-summary-row cart-total">
                    <span>Total</span>
                    <span>{{ $rupiah($total) }}</span>
                </div>

                {{-- Route checkout dibuat di Tahap 8; Route::has mencegah error sebelum itu. --}}
                @if (Route::has('checkout.index'))
                    @if ($hasStockProblem)
                        <button type="button" class="shop-btn shop-btn-primary" disabled style="width: 100%;">
                            Lanjut ke checkout
                        </button>
                        <p class="field-error">Perbaiki jumlah yang melebihi stok untuk melanjutkan.</p>
                    @else
                        <a href="{{ route('checkout.index') }}" class="shop-btn shop-btn-primary" style="width: 100%;">
                            Lanjut ke checkout
                        </a>
                    @endif
                @endif
            </aside>
        </div>
    @endif
</div>
@endsection
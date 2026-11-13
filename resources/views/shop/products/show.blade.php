@extends('layouts.app', ['title' => $product->name . ' - MatbuildShop'])

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <p style="margin-bottom: 1rem;">
        <a href="{{ route('shop.home') }}" style="color: var(--accent);">&larr; Kembali ke Katalog</a>
    </p>

    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">{{ $product->name }}</h1>
    <div style="font-size: 0.95rem; color: #666; margin-bottom: 1rem;">
        Kategori: {{ $product->category?->name ?? '-' }}
    </div>

    <div style="font-size: 1.5rem; font-weight: 700; color: var(--accent); margin-bottom: 0.5rem;">
        {{ $product->formatted_price }}
    </div>

    <p style="font-size: 0.95rem; margin-bottom: 1.5rem;">
        Status Stok: <strong>{{ $product->stock }} unit</strong>
    </p>

    <div style="line-height: 1.6; color: #333;">
        {{ $product->description }}
    </div>
</div>
@endsection

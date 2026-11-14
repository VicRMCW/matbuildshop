@extends('layouts.app', ['title' => 'Checkout - MatbuildShop'])

@section('content')
@php
    $rupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
    $user = auth()->user();
@endphp

<div class="shop-container">
    <h1 class="cart-heading">Checkout</h1>

    <div class="cart-layout">
        <form method="POST" action="{{ $formAction ?? route('checkout.store') }}" class="card" id="checkout-form">
            @csrf
            <h2 class="card-title">Data penerima</h2>

            <div class="form-group">
                <label for="recipient_name">Nama penerima</label>
                <input type="text" id="recipient_name" name="recipient_name" maxlength="100"
                       value="{{ old('recipient_name', $user->name) }}" required>
                @error('recipient_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Nomor telepon</label>
                <input type="tel" id="phone" name="phone" placeholder="08123456789"
                       value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="shipping_address">Alamat pengiriman</label>
                <textarea id="shipping_address" name="shipping_address" maxlength="500"
                          placeholder="Jalan, nomor, RT/RW, kelurahan, kota, kode pos"
                          required>{{ old('shipping_address', $user->address) }}</textarea>
                @error('shipping_address')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </form>

        <aside class="cart-summary">
            <h2 class="cart-summary-title">Ringkasan pesanan</h2>

            <ul class="checkout-items">
                @foreach ($items as $item)
                    <li class="checkout-item">
                        <div>
                            <p class="checkout-item-name">{{ $item->product->name }}</p>
                            <p class="cart-item-meta">
                                {{ $item->quantity }} &times; {{ $item->product->formatted_price }}
                            </p>
                        </div>
                        <p class="checkout-item-subtotal">{{ $rupiah($item->subtotal) }}</p>
                    </li>
                @endforeach
            </ul>

            <div class="cart-summary-row cart-total">
                <span>Total</span>
                <span>{{ $rupiah($total) }}</span>
            </div>

            {{-- Tombol di luar form tetap mengirim form lewat atribut form="..." --}}
            <button type="submit" form="checkout-form" class="shop-btn shop-btn-primary" style="width: 100%;">
                Buat pesanan
            </button>
            <p class="checkout-note">
                Total dihitung ulang oleh server saat pesanan dibuat.
            </p>
            <a href="{{ $backUrl ?? route('cart.index') }}" class="checkout-back">{{ $backLabel ?? 'Kembali ke keranjang' }}</a>
        </aside>
    </div>
</div>
@endsection
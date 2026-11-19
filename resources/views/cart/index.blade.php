
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
            <p class="shop-empty-text">
                Pilih barang dari katalog untuk mulai berbelanja.
            </p>

            <a href="{{ route('shop.catalog') }}" class="shop-btn shop-btn-primary">
                Lihat katalog
            </a>
        </div>

    @else

        {{-- Form khusus untuk mengirim pilihan produk checkout --}}
        <form
            id="checkout-selection-form"
            method="POST"
            action="{{ route('checkout.prepare') }}"
        >
            @csrf
        </form>

        <div class="cart-layout">

            <ul class="cart-list">

                @foreach ($items as $item)

                    @php
                        $product = $item->product;
                    @endphp

                    <li class="cart-item"
                        style="
                            display: grid;
                            grid-template-columns: 96px minmax(0, 1fr);
                            align-items: center;
                            gap: 16px;
                        ">

                        {{-- Checkbox dan gambar produk --}}
                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: flex-start;
                            gap: 8px;
                            width: 96px;
                        ">

                            <input
                                type="checkbox"
                                name="cart_item_ids[]"
                                value="{{ $item->id }}"
                                form="checkout-selection-form"
                                checked
                                data-cart-select
                                data-subtotal="{{ $item->subtotal }}"
                                data-quantity="{{ $item->quantity }}"
                                data-stock-ok="{{ $item->checkout_stock_ok ? '1' : '0' }}"
                                aria-label="Pilih {{ $product->name }} untuk checkout"
                            >

                            @if ($product->image)

                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="cart-thumb"
                                    style="
                                        width: 72px;
                                        height: 72px;
                                        flex: 0 0 72px;
                                        object-fit: contain;
                                    "
                                >

                            @else

                                <div
                                    class="cart-thumb cart-thumb-empty"
                                    style="
                                        width: 72px;
                                        height: 72px;
                                        flex: 0 0 72px;
                                    "
                                    aria-hidden="true">
                                </div>

                            @endif

                        </div>

                        {{-- Informasi produk --}}
                        <div style="min-width: 0;">

                            <h2 class="cart-item-name">
                                <a href="{{ route('shop.products.show', ['product' => $product->slug]) }}">
                                    {{ $product->name }}
                                </a>
                            </h2>

                            <p class="cart-item-meta">
                                {{ $product->formatted_price }} per item
                                &middot; stok {{ $product->stock }}
                            </p>

                            @if ($item->quantity > $product->stock)

                                <p class="field-error">
                                    Stok tersisa {{ $product->stock }}.
                                    Kurangi jumlahnya sebelum checkout.
                                </p>

                            @endif

                            {{-- Aksi jumlah dan hapus produk --}}
                            <div class="cart-item-actions">

                                <div class="qty-control">

                                    {{-- Kurangi jumlah --}}
                                    <form
                                        method="POST"
                                        action="{{ route('cart.update', $item) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="quantity"
                                            value="{{ $item->quantity - 1 }}"
                                        >

                                        <button
                                            type="submit"
                                            class="qty-btn"
                                            aria-label="Kurangi jumlah"
                                            @disabled($item->quantity <= 1)
                                        >
                                            &minus;
                                        </button>
                                    </form>

                                    <span class="qty-value">
                                        {{ $item->quantity }}
                                    </span>

                                    {{-- Tambah jumlah --}}
                                    <form
                                        method="POST"
                                        action="{{ route('cart.update', $item) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="quantity"
                                            value="{{ $item->quantity + 1 }}"
                                        >

                                        <button
                                            type="submit"
                                            class="qty-btn"
                                            aria-label="Tambah jumlah"
                                            @disabled($item->quantity >= $product->stock)
                                        >
                                            +
                                        </button>
                                    </form>

                                </div>

                                <p class="cart-item-price">
                                    Subtotal {{ $rupiah($item->subtotal) }}
                                </p>

                                {{-- Hapus produk --}}
                                <form
                                    method="POST"
                                    action="{{ route('cart.destroy', $item) }}"
                                    onsubmit="return confirm('Hapus produk ini dari keranjang?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="shop-btn shop-btn-sm shop-btn-danger"
                                    >
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </div>

                    </li>

                @endforeach

            </ul>

            {{-- Ringkasan belanja --}}
            <aside class="cart-summary">

                <h2 class="cart-summary-title">
                    Ringkasan
                </h2>

                {{-- Pilih semua --}}
                <label style="
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin-bottom: 16px;
                ">
                    <input
                        type="checkbox"
                        id="select-all-cart-items"
                        checked
                    >

                    Pilih semua produk
                </label>

                <div class="cart-summary-row">
                    <span>Produk dipilih</span>
                    <span id="selected-products-count">
                        {{ $items->count() }}
                    </span>
                </div>

                <div class="cart-summary-row">
                    <span>Jumlah item</span>
                    <span id="selected-quantity">
                        {{ $items->sum('quantity') }}
                    </span>
                </div>

                <div class="cart-summary-row cart-total">
                    <span>Total</span>
                    <span id="selected-cart-total">
                        {{ $rupiah($total) }}
                    </span>
                </div>

                <button
                    type="submit"
                    form="checkout-selection-form"
                    id="checkout-selection-button"
                    class="shop-btn shop-btn-primary"
                    style="width: 100%;"
                >
                    Lanjut ke checkout
                </button>

                <p
                    id="checkout-selection-warning"
                    class="field-error"
                    hidden
                >
                    Ada produk yang dipilih dengan jumlah melebihi stok.
                    Hapus centangnya atau kurangi jumlahnya.
                </p>

                <p
                    id="checkout-selection-empty"
                    class="field-error"
                    hidden
                >
                    Pilih minimal satu produk untuk melanjutkan checkout.
                </p>

            </aside>

        </div>

    @endif

</div>


{{-- JavaScript pilihan produk dan perhitungan total --}}
@if ($items->isNotEmpty())

<script>
document.addEventListener('DOMContentLoaded', function () {

    const checkboxes = Array.from(
        document.querySelectorAll('[data-cart-select]')
    );

    const selectAll = document.getElementById('select-all-cart-items');

    const totalElement = document.getElementById('selected-cart-total');

    const productsCountElement = document.getElementById(
        'selected-products-count'
    );

    const quantityElement = document.getElementById(
        'selected-quantity'
    );

    const submitButton = document.getElementById(
        'checkout-selection-button'
    );

    const stockWarning = document.getElementById(
        'checkout-selection-warning'
    );

    const emptyWarning = document.getElementById(
        'checkout-selection-empty'
    );

    const rupiahFormatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    });

    function updateCheckoutSummary() {

        const selected = checkboxes.filter(
            checkbox => checkbox.checked
        );

        // Menghitung total harga produk yang dipilih.
        const total = selected.reduce(
            (sum, checkbox) => {
                return sum + Number(checkbox.dataset.subtotal || 0);
            },
            0
        );

        // Menghitung jumlah seluruh item yang dipilih.
        const quantity = selected.reduce(
            (sum, checkbox) => {
                return sum + Number(checkbox.dataset.quantity || 0);
            },
            0
        );

        // Memeriksa ketersediaan stok.
        const hasStockProblem = selected.some(
            checkbox => checkbox.dataset.stockOk !== '1'
        );

        // Memperbarui ringkasan.
        productsCountElement.textContent = selected.length;

        quantityElement.textContent = quantity;

        totalElement.textContent = rupiahFormatter.format(total);

        // Memperbarui checkbox pilih semua.
        selectAll.checked = selected.length === checkboxes.length;

        selectAll.indeterminate =
            selected.length > 0 &&
            selected.length < checkboxes.length;

        // Mengatur tombol checkout.
        submitButton.disabled =
            selected.length === 0 || hasStockProblem;

        // Menampilkan pesan sesuai kondisi.
        stockWarning.hidden = !hasStockProblem;

        emptyWarning.hidden = selected.length !== 0;

    }

    // Ketika checkbox produk berubah.
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener(
            'change',
            updateCheckoutSummary
        );
    });

    // Ketika pilih semua diklik.
    selectAll.addEventListener('change', function () {

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });

        updateCheckoutSummary();

    });

    // Menjalankan perhitungan saat halaman dibuka.
    updateCheckoutSummary();

});
</script>

@endif

@endsection
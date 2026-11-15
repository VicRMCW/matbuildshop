@extends('layouts.admin', ['title' => 'Kelola Produk - Admin MatbuildShop'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Produk</h1>
        <p class="page-subtitle">Inventaris material konstruksi dan perkakas toko.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        + Tambah Produk
    </a>
</div>

<div class="search-bar">
    <form method="GET" action="{{ route('admin.products.index') }}" class="search-form">
        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nama produk atau kategori..."
            class="search-input"
        >

        <button type="submit" class="btn btn-secondary">Cari</button>

        @if($search)
            <a href="{{ route('admin.products.index') }}"
               class="btn btn-secondary"
               style="padding: 0 16px;">
                Reset
            </a>
        @endif
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-photo">Foto</th>
                    <th>Nama & Kategori</th>
                    <th style="width: 140px;">Harga</th>
                    <th style="width: 90px; text-align: center;">Stok</th>
                    <th style="width: 110px; text-align: center;">Status</th>
                    <th class="col-actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $prod)
                    <tr>
                        <td class="col-photo">
                            @if ($prod->image)
                                <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="product-thumb">
                            @else
                                <div class="product-thumb-placeholder" title="Tanpa Foto">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                        <circle cx="9" cy="9" r="2"/>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="product-name">{{ $prod->name }}</strong>
                            <div class="product-category">
                                {{ $prod->category?->name ?? '-' }}
                            </div>
                        </td>
                        <td>
                            <span class="price-text">{{ $prod->formatted_price }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-count">
                                {{ $prod->stock }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if ($prod->is_active)
                                <span class="badge badge-success">
                                    Aktif
                                </span>
                            @else
                                <span class="badge badge-muted">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <div class="actions-wrap">
                                <a href="{{ route('admin.products.edit', $prod) }}" class="btn btn-secondary btn-sm">
                                    Ubah
                                </a>

                                <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 32px 16px; text-align: center; color: var(--adm-text-muted);">
                            Tidak ada data produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Memakai view pagination khusus dengan tombol 40x40px dan teks Indonesia --}}
{{ $products->links('vendor.pagination.custom') }}
@endsection

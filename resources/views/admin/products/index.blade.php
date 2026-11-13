@extends('layouts.admin', ['title' => 'Kelola Produk - Admin MatbuildShop'])

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Daftar Produk</h1>
        <p style="font-size: 0.9rem; color: #666;">Inventaris material konstruksi dan perkakas toko.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn">+ Tambah Produk</a>
</div>

<div class="card" style="margin-bottom: 1.25rem; padding: 1rem;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display: flex; gap: 0.75rem; align-items: center;">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk bangunan..." style="max-width: 360px;">
        <button type="submit" class="btn" style="padding: 0.55rem 1rem;">Cari</button>
        @if($search)
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding: 0.55rem 0.85rem;">Reset</a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
            <tr style="background: #F8F7F5; border-bottom: 1px solid var(--border-color);">
                <th style="padding: 0.75rem 1rem; width: 64px; text-align: center;">Foto</th>
                <th style="padding: 0.75rem 1rem; font-weight: 600;">Nama & Kategori</th>
                <th style="padding: 0.75rem 1rem; font-weight: 600;">Harga</th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center;">Stok</th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center;">Status</th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $prod)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        @if ($prod->image)
                            <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-color);">
                        @else
                            <div style="width: 48px; height: 48px; background: #E5E7EB; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; color: #9CA3AF;" title="Tanpa Foto">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                    <circle cx="9" cy="9" r="2"/>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        <strong>{{ $prod->name }}</strong>
                        <div style="font-size: 0.8rem; color: #666; margin-top: 0.15rem;">
                            {{ $prod->category?->name ?? '-' }}
                        </div>
                    </td>
                    <td style="padding: 0.75rem 1rem; font-weight: 600;">
                        {{ $prod->formatted_price }}
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <span style="display: inline-block; padding: 0.15rem 0.5rem; background: #F2F1EF; border-radius: 4px; font-weight: 600; font-size: 0.85rem;">
                            {{ $prod->stock }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        @if ($prod->is_active)
                            <span style="display: inline-block; padding: 0.2rem 0.55rem; background: #E8F5E9; color: #1E4620; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                Aktif
                            </span>
                        @else
                            <span style="display: inline-block; padding: 0.2rem 0.55rem; background: #F3F4F6; color: #4B5563; border-radius: 4px; font-size: 0.8rem;">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: right; white-space: nowrap;">
                        <a href="{{ route('admin.products.edit', $prod) }}" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.85rem;">Ubah</a>

                        <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.85rem; color: #B91C1C;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 2rem 1rem; text-align: center; color: #777;">
                        Tidak ada data produk ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.25rem;">
    {{ $products->links() }}
</div>
@endsection

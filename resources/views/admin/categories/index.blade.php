@extends('layouts.admin', ['title' => 'Daftar Kategori - Admin MatbuildShop'])

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Kategori Produk</h1>
        <p style="font-size: 0.9rem; color: #666;">Kelola kelompok bahan dan alat bangunan.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn">+ Tambah Kategori</a>
</div>

<div class="card" style="padding: 0; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
        <thead>
            <tr style="background: #F8F7F5; border-bottom: 1px solid var(--border-color);">
                <th style="padding: 0.85rem 1rem; font-weight: 600;">Nama Kategori</th>
                <th style="padding: 0.85rem 1rem; font-weight: 600;">Slug</th>
                <th style="padding: 0.85rem 1rem; font-weight: 600; text-align: center;">Jumlah Produk</th>
                <th style="padding: 0.85rem 1rem; font-weight: 600; text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 0.85rem 1rem;">
                        <strong>{{ $cat->name }}</strong>
                        @if($cat->description)
                            <div style="font-size: 0.8rem; color: #666; margin-top: 0.2rem;">{{ Str::limit($cat->description, 70) }}</div>
                        @endif
                    </td>
                    <td style="padding: 0.85rem 1rem; color: #666;">{{ $cat->slug }}</td>
                    <td style="padding: 0.85rem 1rem; text-align: center;">
                        <span style="display: inline-block; padding: 0.2rem 0.6rem; background: #F2F1EF; border-radius: 4px; font-weight: 600;">
                            {{ $cat->products_count }}
                        </span>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: right; white-space: nowrap;">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.85rem;">Ubah</a>

                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display: inline;" onsubmit="return confirm('Hapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.85rem; color: #B91C1C;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 2rem 1rem; text-align: center; color: #777;">
                        Belum ada kategori yang terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.25rem;">
    {{ $categories->links() }}
</div>
@endsection

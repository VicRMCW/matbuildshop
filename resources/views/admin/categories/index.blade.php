@extends('layouts.admin', ['title' => 'Daftar Kategori - Admin MatbuildShop'])

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Kategori Produk</h1>
        <p class="page-subtitle">Kelola kelompok bahan dan alat bangunan.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        + Tambah Kategori
    </a>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Slug</th>
                    <th style="text-align: center; width: 140px;">Jumlah Produk</th>
                    <th class="col-actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                    <tr>
                        <td>
                            <strong style="color: var(--adm-text); font-weight: 600;">{{ $cat->name }}</strong>
                            @if($cat->description)
                                <div style="font-size: 14px; color: var(--adm-text-muted); margin-top: 2px;">{{ Str::limit($cat->description, 80) }}</div>
                            @endif
                        </td>
                        <td style="color: var(--adm-text-muted);">{{ $cat->slug }}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-count">
                                {{ $cat->products_count }}
                            </span>
                        </td>
                        <td class="col-actions">
                            <div class="actions-wrap">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary btn-sm">
                                    Ubah
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display: inline;" onsubmit="return confirm('Hapus kategori ini?');">
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
                        <td colspan="4" style="padding: 32px 16px; text-align: center; color: var(--adm-text-muted);">
                            Belum ada kategori yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Memakai view pagination khusus dengan tombol 40x40px dan teks Indonesia --}}
{{ $categories->links('vendor.pagination.custom') }}
@endsection

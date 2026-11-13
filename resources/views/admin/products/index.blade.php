@extends('layouts.admin', ['title' => 'Kelola Produk - Admin MatbuildShop'])

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Daftar Produk</h1>
        <p class="text-sm text-neutral-600 mt-1">Inventaris material konstruksi dan perkakas toko.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center h-11 px-5 text-sm font-medium rounded text-white bg-[#C8601A] hover:bg-[#A64E13] transition-colors self-start sm:self-auto">
        + Tambah Produk
    </a>
</div>

<div class="bg-white border border-neutral-300 rounded-md p-4 mb-5 shadow-xs">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk bangunan..."
            class="h-11 px-4 text-sm rounded border border-neutral-300 bg-white text-neutral-900 focus:outline-none focus:ring-2 focus:ring-[#C8601A] focus:border-[#C8601A] w-full sm:max-w-md">
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center justify-center h-11 px-5 text-sm font-medium rounded text-white bg-[#C8601A] hover:bg-[#A64E13] transition-colors">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center h-11 px-4 text-sm font-medium rounded border border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-100 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white border border-neutral-300 rounded-md overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-neutral-100 border-b border-neutral-300 text-neutral-800">
                    <th class="py-3.5 px-4 w-16 text-center font-semibold">Foto</th>
                    <th class="py-3.5 px-4 font-semibold">Nama & Kategori</th>
                    <th class="py-3.5 px-4 font-semibold">Harga</th>
                    <th class="py-3.5 px-4 font-semibold text-center">Stok</th>
                    <th class="py-3.5 px-4 font-semibold text-center">Status</th>
                    <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($products as $prod)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="py-3 px-4 text-center">
                            @if ($prod->image)
                                <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="w-12 h-12 object-cover rounded border border-neutral-300 mx-auto">
                            @else
                                <div class="w-12 h-12 bg-neutral-200 rounded border border-neutral-300 inline-flex items-center justify-center text-neutral-400 mx-auto" title="Tanpa Foto">
                                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/>
                                        <circle cx="9" cy="9" r="2"/>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <strong class="text-neutral-900 font-semibold block">{{ $prod->name }}</strong>
                            <div class="text-xs text-neutral-500 mt-0.5">
                                {{ $prod->category?->name ?? '-' }}
                            </div>
                        </td>
                        <td class="py-3 px-4 font-semibold text-neutral-900">
                            {{ $prod->formatted_price }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs font-semibold bg-neutral-100 text-neutral-800 rounded border border-neutral-200">
                                {{ $prod->stock }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if ($prod->is_active)
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 rounded">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 text-xs font-semibold bg-neutral-100 text-neutral-600 border border-neutral-200 rounded">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $prod) }}" class="inline-flex items-center justify-center h-9 px-3 text-xs font-medium rounded border border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-100 transition-colors">
                                    Ubah
                                </a>

                                <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center h-9 px-3 text-xs font-medium rounded border border-neutral-300 bg-white text-red-700 hover:bg-red-50 hover:border-red-300 transition-colors cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-neutral-500">
                            Tidak ada data produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $products->links() }}
</div>
@endsection

@extends('layouts.admin', ['title' => 'Daftar Kategori - Admin MatbuildShop'])

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Kategori Produk</h1>
        <p class="text-sm text-neutral-600 mt-1">Kelola kelompok bahan dan alat bangunan.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center h-11 px-5 text-sm font-medium rounded text-white bg-[#C8601A] hover:bg-[#A64E13] transition-colors self-start sm:self-auto">
        + Tambah Kategori
    </a>
</div>

<div class="bg-white border border-neutral-300 rounded-md overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead>
                <tr class="bg-neutral-100 border-b border-neutral-300 text-neutral-800">
                    <th class="py-3.5 px-4 font-semibold">Nama Kategori</th>
                    <th class="py-3.5 px-4 font-semibold">Slug</th>
                    <th class="py-3.5 px-4 font-semibold text-center">Jumlah Produk</th>
                    <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="py-4 px-4">
                            <strong class="text-neutral-900 font-semibold">{{ $cat->name }}</strong>
                            @if($cat->description)
                                <div class="text-xs text-neutral-500 mt-0.5">{{ Str::limit($cat->description, 80) }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-neutral-600">{{ $cat->slug }}</td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-semibold bg-neutral-100 text-neutral-800 rounded border border-neutral-200">
                                {{ $cat->products_count }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="inline-flex items-center justify-center h-9 px-3 text-xs font-medium rounded border border-neutral-300 bg-white text-neutral-700 hover:bg-neutral-100 transition-colors">
                                    Ubah
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
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
                        <td colspan="4" class="py-8 px-4 text-center text-neutral-500">
                            Belum ada kategori yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-5">
    {{ $categories->links() }}
</div>
@endsection

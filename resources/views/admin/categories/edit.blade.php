@extends('layouts.admin', ['title' => 'Ubah Kategori - Admin MatbuildShop'])

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Ubah Kategori</h1>
            <p class="text-sm text-neutral-600 mt-1">Perbarui informasi kelompok barang bangunan.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white border border-neutral-300 rounded-md p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection

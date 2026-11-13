@extends('layouts.admin', ['title' => 'Ubah Produk - Admin MatbuildShop'])

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Ubah Data Produk</h1>
            <p class="text-sm text-neutral-600 mt-1">Perbarui detail harga, stok, atau gambar produk.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white border border-neutral-300 rounded-md p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.products._form')
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin', ['title' => 'Ubah Produk - Admin MatbuildShop'])

@section('content')
<div class="form-card">
    <div class="page-header" style="margin-bottom: 24px;">
        <div>
            <h1 class="page-title" style="font-size: 24px;">Ubah Data Produk</h1>
            <p class="page-subtitle">Perbarui detail harga, stok, atau gambar produk.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="header-back-link">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.products._form')
    </form>
</div>
@endsection

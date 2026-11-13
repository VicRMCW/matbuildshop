@extends('layouts.admin', ['title' => 'Ubah Produk - Admin MatbuildShop'])

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Ubah Data Produk</h1>
        <p style="font-size: 0.9rem; color: #666;">Perbarui detail harga, stok, atau gambar produk.</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.products._form')
        </form>
    </div>
</div>
@endsection

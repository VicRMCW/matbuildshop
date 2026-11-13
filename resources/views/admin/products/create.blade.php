@extends('layouts.admin', ['title' => 'Tambah Produk - Admin MatbuildShop'])

@section('content')
<div class="form-card">
    <div class="page-header" style="margin-bottom: 24px;">
        <div>
            <h1 class="page-title" style="font-size: 24px;">Tambah Produk Baru</h1>
            <p class="page-subtitle">Daftarkan material atau alat konstruksi baru ke inventaris.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="header-back-link">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @include('admin.products._form')
    </form>
</div>
@endsection

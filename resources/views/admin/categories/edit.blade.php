@extends('layouts.admin', ['title' => 'Ubah Kategori - Admin MatbuildShop'])

@section('content')
<div class="form-card">
    <div class="page-header" style="margin-bottom: 24px;">
        <div>
            <h1 class="page-title" style="font-size: 24px;">Ubah Kategori</h1>
            <p class="page-subtitle">Perbarui informasi kelompok barang bangunan.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="header-back-link">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @method('PUT')
        @include('admin.categories._form')
    </form>
</div>
@endsection

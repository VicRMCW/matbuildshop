@extends('layouts.admin', ['title' => 'Tambah Kategori - Admin MatbuildShop'])

@section('content')
<div class="form-card">
    <div class="page-header" style="margin-bottom: 24px;">
        <div>
            <h1 class="page-title" style="font-size: 24px;">Tambah Kategori Baru</h1>
            <p class="page-subtitle">Tambahkan klasifikasi material atau perkakas baru.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="header-back-link">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @include('admin.categories._form')
    </form>
</div>
@endsection

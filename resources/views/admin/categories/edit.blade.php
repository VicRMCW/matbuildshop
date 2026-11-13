@extends('layouts.admin', ['title' => 'Ubah Kategori - Admin MatbuildShop'])

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Ubah Kategori</h1>
        <p style="font-size: 0.9rem; color: #666;">Perbarui informasi kelompok barang bangunan.</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection

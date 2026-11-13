@extends('layouts.admin', ['title' => 'Tambah Kategori - Admin MatbuildShop'])

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Tambah Kategori Baru</h1>
        <p style="font-size: 0.9rem; color: #666;">Tambahkan klasifikasi material atau perkakas baru.</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @include('admin.categories._form')
        </form>
    </div>
</div>
@endsection

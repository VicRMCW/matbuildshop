@extends('layouts.admin', ['title' => 'Tambah Produk - Admin MatbuildShop'])

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.5rem; margin-bottom: 0.25rem;">Tambah Produk Baru</h1>
        <p style="font-size: 0.9rem; color: #666;">Daftarkan material atau alat konstruksi baru ke inventaris.</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @include('admin.products._form')
        </form>
    </div>
</div>
@endsection

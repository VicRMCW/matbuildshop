@extends('layouts.app', ['title' => 'Daftar Akun - MatbuildShop'])

@section('content')
<div style="max-width: 460px; margin: 2rem auto;">
    <div class="card">
        <h1 style="font-size: 1.4rem; margin-bottom: 0.5rem;">Daftar Pelanggan</h1>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1.5rem;">Buat akun untuk memesan bahan dan alat bangunan.</p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi (min. 8 karakter)</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
        </form>

        <p style="font-size: 0.85rem; margin-top: 1.5rem; text-align: center; color: #555;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent);">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection

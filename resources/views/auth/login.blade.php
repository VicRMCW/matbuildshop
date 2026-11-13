@extends('layouts.app', ['title' => 'Masuk - MatbuildShop'])

@section('content')
<div style="max-width: 420px; margin: 2rem auto;">
    <div class="card">
        <h1 style="font-size: 1.4rem; margin-bottom: 0.5rem;">Masuk Akun</h1>
        <p style="font-size: 0.9rem; color: #666; margin-bottom: 1.5rem;">Silakan masuk ke akun MatbuildShop Anda.</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem;">
                <input type="checkbox" id="remember" name="remember" value="1" style="width: auto;">
                <label for="remember" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Ingat sesi saya</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
        </form>

        <p style="font-size: 0.85rem; margin-top: 1.5rem; text-align: center; color: #555;">
            Belum punya akun? <a href="{{ route('register') }}" style="color: var(--shop-accent);">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection

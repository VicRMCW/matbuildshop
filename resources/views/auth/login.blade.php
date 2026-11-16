```php
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

                <div style="position: relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        style="width: 100%; padding-right: 45px;"
                    >

                    <button
                        type="button"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; color: #666;"
                    >
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

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

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const isHidden = password.type === 'password';

        password.type = isHidden ? 'text' : 'password';

        this.setAttribute('aria-pressed', isHidden);
        this.setAttribute(
            'aria-label',
            isHidden ? 'Sembunyikan password' : 'Tampilkan password'
        );

        eyeIcon.innerHTML = isHidden
            ? '<path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 002.8 2.8"/><path d="M9.9 5.2A11 11 0 0112 5c6.4 0 10 7 10 7a14 14 0 01-3.1 3.8"/><path d="M6.2 6.2A15 15 0 002 12s3.6 7 10 7a10 10 0 004-.8"/>'
            : '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>';
    });
</script>
@endsection
```

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

            {{-- Kata Sandi --}}
            <div class="form-group">
                <label for="password">Kata Sandi (min. 8 karakter)</label>

                <div style="position: relative;">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        style="width: 100%; padding-right: 45px;"
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; color: #666;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Kata Sandi --}}
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>

                <div style="position: relative;">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        style="width: 100%; padding-right: 45px;"
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password_confirmation"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 5px; color: #666;"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
        </form>

        <p style="font-size: 0.85rem; margin-top: 1.5rem; text-align: center; color: #555;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--shop-accent);">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        this.setAttribute('aria-pressed', isHidden);
        this.setAttribute(
            'aria-label',
            isHidden ? 'Sembunyikan password' : 'Tampilkan password'
        );

        this.innerHTML = isHidden
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 002.8 2.8"/><path d="M9.9 5.2A11 11 0 0112 5c6.4 0 10 7 10 7a14 14 0 01-3.1 3.8"/><path d="M6.2 6.2A15 15 0 002 12s3.6 7 10 7a10 10 0 004-.8"/></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>';
    });
});
</script>
@endsection

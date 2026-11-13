@extends('layouts.app', ['title' => 'Profil Saya - MatbuildShop'])

@section('content')
<div style="max-width: 600px; margin: 1rem auto;">
    <h1 style="font-size: 1.5rem; margin-bottom: 1.25rem;">Pengaturan Profil</h1>

    @if (session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" value="{{ $user->email }}" disabled style="background: #F2F1EF; cursor: not-allowed;">
                <span style="font-size: 0.8rem; color: #666;">Alamat email tidak dapat diubah sendiri.</span>
            </div>

            <div class="form-group">
                <label for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                @error('phone')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Alamat Pengiriman</label>
                <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap tujuan kirim">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <hr style="margin: 1.75rem 0; border: 0; border-top: 1px solid var(--border-color);">

            <h2 style="font-size: 1.1rem; margin-bottom: 0.5rem;">Ganti Kata Sandi</h2>
            <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">Kosongkan bila tidak bermaksud mengganti kata sandi.</p>

            <div class="form-group">
                <label for="current_password">Kata Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password">
                @error('current_password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi Baru</label>
                <input type="password" id="password" name="password">
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection

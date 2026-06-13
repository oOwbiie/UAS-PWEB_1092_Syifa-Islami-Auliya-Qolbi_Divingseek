@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Gili Diving Center')

@section('content')
<div style="max-width: 450px; margin: 2rem auto;">
    <div class="section-title">
        <h2>Daftar Akun</h2>
        <p>Buat akun baru untuk mulai melakukan reservasi diving secara online.</p>
    </div>

    <div class="glass-card">
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ old('name') }}" required autocomplete="name" placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email') }}" required autocomplete="email" placeholder="budi@mail.com">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" 
                       required autocomplete="new-password" placeholder="Minimal 6 karakter">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" id="password-confirm" name="password_confirmation" class="form-control" 
                       required autocomplete="new-password" placeholder="Ulangi kata sandi">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">
                Daftar Akun Baru
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; font-size: 0.9rem; color: var(--color-text-muted);">
            Sudah memiliki akun? <a href="{{ route('login') }}" style="font-weight: 600;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection

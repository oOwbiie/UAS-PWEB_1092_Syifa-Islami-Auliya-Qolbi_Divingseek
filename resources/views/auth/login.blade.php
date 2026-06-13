@extends('layouts.app')

@section('title', 'Masuk - Gili Diving Center')

@section('content')
<div style="max-width: 450px; margin: 3rem auto;">
    <div class="section-title">
        <h2>Masuk Akun</h2>
        <p>Silakan masuk untuk mengakses riwayat reservasi Anda.</p>
    </div>

    <div class="glass-card">
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="budi@mail.com">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" id="password" name="password" class="form-control" 
                       required autocomplete="current-password" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">
                Masuk Sekarang
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; font-size: 0.9rem; color: var(--color-text-muted);">
            Belum punya akun? <a href="{{ route('register') }}" style="font-weight: 600;">Daftar Akun Baru</a>
        </div>
        
        <!-- Quick login hint -->
        <div style="background-color: var(--primary-light); border: 1px solid var(--primary); padding: 1rem; border-radius: 8px; margin-top: 1.5rem; font-size: 0.8rem; line-height: 1.5; color: var(--color-text-main);">
            <strong>💡 Uji Coba Cepat:</strong><br>
            • Admin: <code>admin@gilidiving.com</code> (password: <code>admin123</code>)<br>
            • Customer: <code>budi@gmail.com</code> (password: <code>budi123</code>)
        </div>
    </div>
</div>
@endsection

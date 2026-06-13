@extends('layouts.app')

@section('title', 'Kelola Kontak Kantor - Admin')

@section('content')
<!-- Admin Sub-Navigation -->
<div class="glass-card" style="padding: 1rem; margin-bottom: 2.5rem; display: flex; gap: 1rem; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <div style="font-weight: 700; font-family: var(--font-heading); font-size: 1.15rem; color: var(--primary);">
        🛠️ Panel Administrasi
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📊 Riwayat Reservasi
        </a>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            🗂️ Kelola Paket Diving
        </a>
        <a href="{{ route('admin.contact.edit') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📞 Kelola Kontak Kantor
        </a>
    </div>
</div>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="section-title">
        <h2>Kelola Kontak Kantor</h2>
        <p>Perbarui informasi kontak resmi dan alamat operasional kantor diving yang ditampilkan pada bagian footer website.</p>
    </div>

    <div class="glass-card">
        <form action="{{ route('admin.contact.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Lengkap Kantor</label>
                <textarea id="alamat" name="alamat" class="form-control" rows="3" required placeholder="Contoh: Gili Trawangan, Lombok Utara, Nusa Tenggara Barat, Indonesia">{{ old('alamat', $contact->alamat ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="nomor_hp" class="form-label">Nomor WhatsApp Resmi</label>
                <input type="text" id="nomor_hp" name="nomor_hp" class="form-control" 
                       value="{{ old('nomor_hp', $contact->nomor_hp ?? '') }}" required placeholder="Contoh: 081234567890">
            </div>

            <div class="form-group">
                <label for="jam_buka" class="form-label">Jam Operasional (Buka - Tutup)</label>
                <input type="text" id="jam_buka" name="jam_buka" class="form-control" 
                       value="{{ old('jam_buka', $contact->jam_buka ?? '') }}" required placeholder="Contoh: 08.00 - 20.00 WITA">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="email" class="form-label">Email Hubungan Pelanggan</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email', $contact->email ?? '') }}" required placeholder="Contoh: info@gilidiving.com">
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="flex: 1;">Kembali</a>
                <button type="submit" class="btn btn-primary" style="flex: 2;">
                    Simpan Informasi Kontak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

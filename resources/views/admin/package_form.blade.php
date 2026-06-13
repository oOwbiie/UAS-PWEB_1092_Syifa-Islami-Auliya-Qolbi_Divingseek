@extends('layouts.app')

@section('title', isset($package) ? 'Edit Paket Diving' : 'Tambah Paket Diving Baru')

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
        <a href="{{ route('admin.packages.index') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            🗂️ Kelola Paket Diving
        </a>
        <a href="{{ route('admin.contact.edit') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📞 Kelola Kontak Kantor
        </a>
    </div>
</div>

<div style="max-width: 650px; margin: 0 auto;">
    <div class="section-title">
        <h2>{{ isset($package) ? 'Edit Paket Diving' : 'Tambah Paket Baru' }}</h2>
        <p>Silakan isi rincian paket diving di bawah ini secara lengkap.</p>
    </div>

    <div class="glass-card">
        <form action="{{ isset($package) ? route('admin.packages.update', $package->id) : route('admin.packages.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="nama_paket" class="form-label">Nama Paket</label>
                <input type="text" id="nama_paket" name="nama_paket" class="form-control" 
                       value="{{ old('nama_paket', $package->nama_paket ?? '') }}" required placeholder="Contoh: Paket Open Water Course">
            </div>

            <div class="form-group">
                <label for="kategori" class="form-label">Kategori Paket</label>
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value="" disabled selected>Pilih kategori...</option>
                    <option value="beginner" {{ old('kategori', $package->kategori ?? '') === 'beginner' ? 'selected' : '' }}>Beginner (Pemula)</option>
                    <option value="intermediate" {{ old('kategori', $package->kategori ?? '') === 'intermediate' ? 'selected' : '' }}>Intermediate (Menengah)</option>
                    <option value="professional" {{ old('kategori', $package->kategori ?? '') === 'professional' ? 'selected' : '' }}>Professional (Pakar)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga" class="form-label">Harga Paket (Rp)</label>
                <input type="number" id="harga" name="harga" class="form-control" 
                       value="{{ old('harga', $package->harga ?? '') }}" required placeholder="Contoh: 1200000" min="0">
            </div>

            <div class="form-group">
                <label for="durasi" class="form-label">Durasi & Sesi</label>
                <input type="text" id="durasi" name="durasi" class="form-control" 
                       value="{{ old('durasi', $package->durasi ?? '') }}" required placeholder="Contoh: 4 Jam (2x Dive)">
            </div>

            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi Paket</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4" required placeholder="Tulis penjelasan lengkap mengenai rincian paket diving di sini...">{{ old('deskripsi', $package->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="fasilitas" class="form-label">Fasilitas Termasuk (Pisahkan dengan baris baru)</label>
                <textarea id="fasilitas" name="fasilitas" class="form-control" rows="5" required placeholder="Contoh:&#10;Peralatan Diving Lengkap&#10;Instruktur Berlisensi&#10;Snack Box & Air Mineral">{{ old('fasilitas', $package->fasilitas ?? '') }}</textarea>
            </div>

            @php
                $isUrl = isset($package) && $package->gambar && filter_var($package->gambar, FILTER_VALIDATE_URL);
                $isLocal = isset($package) && $package->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($package->gambar);
            @endphp

            @if(isset($package) && $package->gambar)
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Gambar Saat Ini:</label>
                    <div style="width: 150px; height: 100px; border-radius: 8px; overflow: hidden; background: #eee; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; margin-top: 0.5rem;">
                        @if($isUrl)
                            <img src="{{ $package->gambar }}?v={{ $package->updated_at->timestamp }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($isLocal)
                            <img src="{{ asset('storage/' . $package->gambar) }}?v={{ $package->updated_at->timestamp }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 1.5rem;">🌊</span>
                        @endif
                    </div>
                    @if($package->gambar !== 'default.jpg')
                        <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" id="remove_image" name="remove_image" value="1">
                            <label for="remove_image" style="font-size: 0.85rem; color: var(--danger); cursor: pointer; font-weight: 600;">Hapus Gambar Saat Ini & Gunakan Default</label>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Image File Upload -->
            <div class="form-group">
                <label for="gambar_file" class="form-label">Upload Gambar Baru (File JPEG/PNG)</label>
                <input type="file" id="gambar_file" name="gambar_file" class="form-control">
            </div>

            <!-- Image URL Alternative -->
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="gambar_url" class="form-label">Atau Gunakan URL Gambar Eksternal</label>
                <input type="text" id="gambar_url" name="gambar_url" class="form-control" 
                       value="{{ old('gambar_url', $isUrl ? $package->gambar : '') }}" placeholder="https://example.com/image.jpg">
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline" style="flex: 1; display: flex; align-items: center; justify-content: center;">Batal</a>
                <button type="submit" class="btn btn-primary" style="flex: 2;">
                    {{ isset($package) ? 'Simpan Perubahan' : 'Tambah Paket Baru' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

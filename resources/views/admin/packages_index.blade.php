@extends('layouts.app')

@section('title', 'Kelola Paket Diving - Admin')

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

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div class="section-title" style="margin-bottom: 0; text-align: left;">
        <h2>Kelola Paket Diving</h2>
        <p>Tambah, edit, atau hapus paket diving yang ditawarkan kepada customer.</p>
    </div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
        ➕ Tambah Paket Baru
    </a>
</div>

<div class="glass-card">
    @if($packages->isEmpty())
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">Belum ada paket diving. Silakan tambahkan paket baru.</p>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $pkg)
                        <tr>
                            <td>
                                <div style="width: 80px; height: 50px; border-radius: 6px; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; overflow: hidden;">
                                    @if($pkg->gambar && filter_var($pkg->gambar, FILTER_VALIDATE_URL))
                                        <img src="{{ $pkg->gambar }}?v={{ $pkg->updated_at->timestamp }}" alt="{{ $pkg->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                                    @elseif($pkg->gambar && Storage::disk('public')->exists($pkg->gambar))
                                        <img src="{{ asset('storage/' . $pkg->gambar) }}?v={{ $pkg->updated_at->timestamp }}" alt="{{ $pkg->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span>🌊</span>
                                    @endif
                                </div>
                            </td>
                            <td><strong>{{ $pkg->nama_paket }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $pkg->kategori }}">{{ $pkg->kategori }}</span>
                            </td>
                            <td>{{ $pkg->durasi }}</td>
                            <td><strong>Rp {{ number_format($pkg->harga, 0, ',', '.') }}</strong></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                        ✏️ Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.packages.delete', $pkg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket diving ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

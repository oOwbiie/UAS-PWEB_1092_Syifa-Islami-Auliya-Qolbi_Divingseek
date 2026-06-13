@extends('layouts.app')

@section('title', 'Dashboard Admin - Gili Diving Center')

@section('content')
<!-- Admin Sub-Navigation -->
<div class="glass-card" style="padding: 1rem; margin-bottom: 2.5rem; display: flex; gap: 1rem; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <div style="font-weight: 700; font-family: var(--font-heading); font-size: 1.15rem; color: var(--primary);">
        🛠️ Panel Administrasi
    </div>
    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📊 Riwayat Reservasi
        </a>
        <a href="{{ route('admin.verifikasi.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            ⏳ Verifikasi Pembayaran
        </a>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            🗂️ Kelola Paket Diving
        </a>
        <a href="{{ route('admin.contact.edit') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📞 Kelola Kontak Kantor
        </a>
    </div>
</div>

<div class="section-title">
    <h2>Dashboard Analitik</h2>
    <p>Selamat datang, Administrator. Berikut adalah ringkasan kinerja penjualan reservasi diving Anda.</p>
</div>

<!-- Statistic Cards -->
<div class="dashboard-stats" style="grid-template-columns: repeat(5, 1fr);">
    <div class="glass-card stat-card">
        <div class="stat-icon">🗂️</div>
        <div>
            <div class="stat-number">{{ $totalPackages }}</div>
            <div class="stat-label">Total Paket</div>
        </div>
    </div>
    
    <div class="glass-card stat-card">
        <div class="stat-icon" style="color: var(--secondary); background-color: var(--secondary-light);">📝</div>
        <div>
            <div class="stat-number">{{ $totalReservations }}</div>
            <div class="stat-label">Total Reservasi</div>
        </div>
    </div>

    <a href="{{ route('admin.verifikasi.index') }}" class="glass-card stat-card" style="text-decoration: none; color: inherit; transition: transform 0.2s ease;">
        <div class="stat-icon" style="color: var(--warning); background-color: var(--warning-light);">⏳</div>
        <div>
            <div class="stat-number">{{ $pendingVerificationCount }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </a>

    <div class="glass-card stat-card">
        <div class="stat-icon" style="color: var(--success); background-color: var(--success-light);">✅</div>
        <div>
            <div class="stat-number">{{ $paidReservations }}</div>
            <div class="stat-label">Reservasi Lunas</div>
        </div>
    </div>

    <div class="glass-card stat-card">
        <div class="stat-icon" style="color: var(--accent); background-color: var(--accent-light);">💰</div>
        <div>
            <div class="stat-number" style="font-size: 1.15rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
</div>

<!-- Transaction Table -->
<div class="glass-card">
    <h3 style="font-size: 1.35rem; margin-bottom: 1.5rem;">Daftar Transaksi Masuk</h3>

    <!-- Search & Filter Form -->
    <form action="{{ route('admin.dashboard') }}" method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 2; min-width: 250px;">
            <label for="search" class="form-label" style="margin-bottom: 0.35rem; font-size: 0.85rem;">Cari Pelanggan / Paket / HP:</label>
            <input type="text" id="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ketik nama customer, email, nomor HP, atau paket...">
        </div>
        <div style="flex: 1; min-width: 150px;">
            <label for="status" class="form-label" style="margin-bottom: 0.35rem; font-size: 0.85rem;">Filter Status:</label>
            <select id="status" name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem;">🔍 Cari</button>
            @if(request()->has('search') || request()->has('status'))
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="padding: 0.65rem 1.25rem; display: flex; align-items: center; justify-content: center;">❌ Reset</a>
            @endif
        </div>
    </form>
    
    @if($reservations->isEmpty())
        <p style="text-align: center; color: var(--color-text-muted); padding: 2rem 0;">Belum ada data reservasi masuk.</p>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Paket Diving</th>
                        <th>Tanggal Diving</th>
                        <th>Peserta</th>
                        <th>Total Bayar</th>
                        <th>Status Pembayaran</th>
                        <th style="width: 280px;">Kelola Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $res)
                        <tr>
                            <td><strong>#{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>
                                <div><strong>{{ $res->nama_customer }}</strong></div>
                                <div style="font-size: 0.8rem; color: var(--color-text-muted);">{{ $res->email }} | {{ $res->nomor_hp }}</div>
                                <div style="font-size: 0.8rem; margin-top: 0.25rem;">
                                    <span class="badge" style="text-transform: none; font-size: 0.7rem; padding: 0.15rem 0.4rem; background: var(--primary-light); color: var(--primary-hover);">
                                        👤 Reservasi User: {{ $res->user ? $res->user->reservations_count : 0 }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $res->package->nama_paket }}</td>
                            <td>{{ date('d M Y', strtotime($res->tanggal_diving)) }}</td>
                            <td>{{ $res->jumlah_peserta }} orang</td>
                            <td><strong>Rp {{ number_format($res->total_harga, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $res->status_pembayaran }}">
                                    {{ $res->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <!-- Status Update Form -->
                                    <form action="{{ route('admin.reservations.status', $res->id) }}" method="POST" style="display: flex; gap: 0.25rem; align-items: center;">
                                        @csrf
                                        <select name="status_pembayaran" class="form-control" style="padding: 0.35rem 0.5rem; font-size: 0.85rem; width: auto; min-width: 105px;">
                                            <option value="pending" {{ $res->status_pembayaran === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="menunggu_verifikasi" {{ $res->status_pembayaran === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                            <option value="disetujui" {{ $res->status_pembayaran === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ $res->status_pembayaran === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            <option value="paid" {{ $res->status_pembayaran === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="cancelled" {{ $res->status_pembayaran === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.6rem; font-size: 0.8rem; border-radius: 6px;">
                                            Simpan
                                        </button>
                                    </form>
                                    
                                    <!-- Delete Transaction -->
                                    <form action="{{ route('admin.reservations.delete', $res->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi reservasi ini?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.6rem; font-size: 0.8rem; border-radius: 6px;" title="Hapus">
                                            🗑️
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

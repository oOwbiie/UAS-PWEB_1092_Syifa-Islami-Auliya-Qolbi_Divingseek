@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Admin')

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
        <a href="{{ route('admin.contact.edit') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
            📞 Kelola Kontak Kantor
        </a>
    </div>
</div>

<div class="section-title" style="text-align: left; margin-bottom: 2rem;">
    <h2>Verifikasi Pembayaran Manual</h2>
    <p>Periksa bukti transfer dari customer dan setujui atau tolak pembayaran mereka.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <span>✅</span>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if($reservations->isEmpty())
    <div class="glass-card" style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1.5rem;">🎉</div>
        <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">Semua Bersih!</h3>
        <p style="color: var(--color-text-muted);">Tidak ada pembayaran baru yang menunggu verifikasi saat ini.</p>
    </div>
@else
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @foreach($reservations as $res)
            <div class="glass-card" style="padding: 2rem; display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; align-items: start;">
                <!-- Left Column: Details -->
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                        <span style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">
                            Invoice #{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <span style="font-size: 0.85rem; color: var(--color-text-muted);">
                            Diupload: {{ date('d M Y H:i', strtotime($res->payment_date)) }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 0.5rem;">Data Customer</h4>
                            <div style="font-weight: 700; margin-bottom: 0.25rem;">{{ $res->nama_customer }}</div>
                            <div style="font-size: 0.85rem; color: var(--color-text-muted);">{{ $res->email }}</div>
                            <div style="font-size: 0.85rem; color: var(--color-text-muted);">{{ $res->nomor_hp }}</div>
                        </div>

                        <div>
                            <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 0.5rem;">Pilihan Paket</h4>
                            <div style="font-weight: 700; margin-bottom: 0.25rem;">{{ $res->package->nama_paket }}</div>
                            <div style="font-size: 0.85rem; color: var(--color-text-muted);">Tanggal Diving: <strong>{{ date('d M Y', strtotime($res->tanggal_diving)) }}</strong></div>
                            <div style="font-size: 0.85rem; color: var(--color-text-muted);">Jumlah Peserta: <strong>{{ $res->jumlah_peserta }} orang</strong></div>
                        </div>
                    </div>

                    <div style="background: var(--bg-base); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: var(--color-text-muted);">Total Tagihan:</span>
                        <span style="font-size: 1.35rem; font-weight: 800; color: var(--secondary);">Rp {{ number_format($res->total_harga, 0, ',', '.') }}</span>
                    </div>

                    <!-- Actions -->
                    <div style="display: flex; gap: 1rem;">
                        <form action="{{ route('admin.verifikasi.approve', $res->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">
                                Setujui Pembayaran
                            </button>
                        </form>

                        <button onclick="showRejectModal('{{ $res->id }}')" class="btn btn-danger" style="flex: 1; padding: 0.75rem;">
                            Tolak Pembayaran
                        </button>
                    </div>
                </div>

                <!-- Right Column: Proof Preview -->
                <div style="text-align: center;">
                    <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 0.75rem; text-align: left;">Bukti Pembayaran</h4>
                    <div style="border-radius: 8px; border: 1px solid var(--border-color); overflow: hidden; background: #000; height: 260px; display: flex; align-items: center; justify-content: center; position: relative;">
                        @if($res->bukti_pembayaran && Storage::disk('public')->exists($res->bukti_pembayaran))
                            <img src="{{ asset('storage/' . $res->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            <a href="{{ asset('storage/' . $res->bukti_pembayaran) }}" target="_blank" style="position: absolute; bottom: 0.5rem; right: 0.5rem; background: rgba(0,0,0,0.6); color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                                🔍 Perbesar
                            </a>
                        @else
                            <span style="color: white; padding: 1rem; font-size: 0.9rem;">Gambar bukti transfer tidak ditemukan</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Reject Modal Mockup (pure CSS / simple JS) -->
<div id="reject-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 2rem; position: relative; margin: 1rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--danger);">Alasan Penolakan Bukti Transfer</h3>
        <form id="reject-form" method="POST" action="">
            @csrf
            <div class="form-group">
                <label for="rejection_reason" class="form-label">Tulis pesan alasan untuk customer:</label>
                <textarea id="rejection_reason" name="rejection_reason" class="form-control" rows="4" required placeholder="Contoh: Bukti buram / nominal transfer tidak sesuai."></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline" style="flex: 1;">Batal</button>
                <button type="submit" class="btn btn-danger" style="flex: 1;">Tolak Bukti</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showRejectModal(id) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('reject-form');
        form.action = `/admin/verifikasi/${id}/reject`;
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('reject-modal').style.display = 'none';
    }
</script>
@endsection

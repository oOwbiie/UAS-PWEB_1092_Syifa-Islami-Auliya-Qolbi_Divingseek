@extends('layouts.app')

@section('title', 'Pembayaran QRIS Reservasi Diving')

@section('content')
<div class="qris-card glass-card">
    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📱</div>
    <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Pembayaran QRIS</h2>
    <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">Silakan scan QRIS di bawah ini menggunakan aplikasi e-wallet (GoPay, OVO, Dana, LinkAja) atau Mobile Banking Anda.</p>

    <!-- Reservation Summary -->
    <div class="glass-card" style="background: var(--bg-base); border-color: var(--border-color); padding: 1.25rem; margin-bottom: 2rem; text-align: left;">
        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
            <span style="color: var(--color-text-muted);">Paket Diving:</span>
            <strong>{{ $reservation->package->nama_paket }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
            <span style="color: var(--color-text-muted);">Tanggal Menyelam:</span>
            <strong>{{ date('d M Y', strtotime($reservation->tanggal_diving)) }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem;">
            <span style="color: var(--color-text-muted);">Jumlah Peserta:</span>
            <strong>{{ $reservation->jumlah_peserta }} orang</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; border-top: 1px dashed var(--border-color); padding-top: 0.75rem; margin-top: 0.5rem; color: var(--color-text-main);">
            <span>Total Bayar:</span>
            <span style="color: var(--primary);">Rp {{ number_format($reservation->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Status Information -->
    @if($reservation->status_pembayaran === 'ditolak')
        <div class="glass-card" style="border-color: var(--danger); background-color: var(--danger-light); padding: 1.25rem; margin-bottom: 2rem; color: #991b1b; text-align: left;">
            <h4 style="font-weight: 800; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">❌ Bukti Pembayaran Ditolak</h4>
            <p style="font-size: 0.95rem; line-height: 1.6;">
                Bukti pembayaran Anda ditolak oleh admin. Silakan periksa kembali transfer Anda dan upload ulang bukti pembayaran yang valid.
            </p>
            @if($reservation->rejection_reason)
                <div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed rgba(239, 68, 68, 0.3); font-size: 0.9rem;">
                    <strong>Alasan Penolakan:</strong> "{{ $reservation->rejection_reason }}"
                </div>
            @endif
        </div>
    @elseif($reservation->status_pembayaran === 'menunggu_verifikasi')
        <div class="glass-card" style="border-color: var(--primary); background-color: var(--primary-light); padding: 1.25rem; margin-bottom: 2rem; color: var(--primary-hover); text-align: center;">
            <h4 style="font-weight: 800; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">⏳ Menunggu Disetujui Admin</h4>
            <p style="font-size: 0.95rem; line-height: 1.6;">
                Bukti pembayaran Anda telah diunggah dan sedang dalam proses verifikasi oleh tim administrasi kami. Invoice akan otomatis tersedia setelah pembayaran disetujui.
            </p>
        </div>
    @endif

    @if($reservation->status_pembayaran === 'pending' || $reservation->status_pembayaran === 'ditolak')
        <!-- Payment Info & Transfer Instructions -->
        <div class="glass-card" style="background: var(--bg-surface); padding: 1.5rem; margin-bottom: 2rem; text-align: left;">
            <h4 style="font-size: 1.1rem; margin-bottom: 1rem; color: var(--primary); font-family: var(--font-heading);">
                ℹ️ Instruksi Pembayaran
            </h4>
            <p style="font-size: 0.9rem; line-height: 1.6; color: var(--color-text-muted); margin-bottom: 1rem;">
                Silakan scan QRIS di bawah ini atau lakukan transfer manual ke rekening bank kami sebesar nominal yang tertera di atas. Setelah selesai, harap unggah bukti transfer (foto/screenshot) di form bawah ini.
            </p>
            <div style="padding: 0.75rem; background: var(--bg-base); border-radius: 8px; border-left: 4px solid var(--secondary); font-size: 0.9rem; margin-bottom: 1rem;">
                <strong>Transfer Bank Mandiri:</strong><br>
                No. Rekening: <strong>161-00-123456-7</strong><br>
                Atas Nama: <strong>CV Gili Diving Trawangan</strong>
            </div>
        </div>

        <!-- QRIS Image Mockup -->
        <div class="qris-image" style="margin-bottom: 2rem;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="180" height="180" style="display: block; margin: 0 auto;">
                <!-- Outer border -->
                <rect x="5" y="5" width="190" height="190" rx="10" fill="none" stroke="#0f172a" stroke-width="4"/>
                <!-- QRIS Header Text -->
                <rect x="5" y="5" width="190" height="35" rx="5" fill="#0f172a"/>
                <text x="100" y="27" fill="white" font-family="'Outfit', sans-serif" font-size="14" font-weight="900" text-anchor="middle">QRIS MOCKUP</text>

                <!-- Target Corners -->
                <rect x="25" y="55" width="30" height="30" fill="none" stroke="#0284c7" stroke-width="6"/>
                <rect x="33" y="63" width="14" height="14" fill="#0f172a"/>

                <rect x="145" y="55" width="30" height="30" fill="none" stroke="#0284c7" stroke-width="6"/>
                <rect x="153" y="63" width="14" height="14" fill="#0f172a"/>

                <rect x="25" y="145" width="30" height="30" fill="none" stroke="#0284c7" stroke-width="6"/>
                <rect x="33" y="153" width="14" height="14" fill="#0f172a"/>

                <!-- Simulated QR grid nodes -->
                <rect x="70" y="55" width="15" height="15" fill="#0d9488"/>
                <rect x="95" y="65" width="20" height="10" fill="#0f172a"/>
                <rect x="125" y="55" width="10" height="25" fill="#f59e0b"/>

                <rect x="70" y="80" width="25" height="10" fill="#0f172a"/>
                <rect x="105" y="80" width="15" height="15" fill="#0284c7"/>
                <rect x="130" y="85" width="10" height="20" fill="#0f172a"/>
                <rect x="150" y="95" width="25" height="25" fill="#0d9488"/>

                <rect x="25" y="95" width="15" height="10" fill="#0f172a"/>
                <rect x="45" y="110" width="10" height="25" fill="#f59e0b"/>
                <rect x="70" y="105" width="20" height="20" fill="#0f172a"/>
                <rect x="100" y="110" width="35" height="15" fill="#0284c7"/>

                <rect x="70" y="135" width="15" height="15" fill="#0f172a"/>
                <rect x="95" y="135" width="15" height="30" fill="#0d9488"/>
                <rect x="120" y="145" width="25" height="15" fill="#f59e0b"/>
                <rect x="150" y="135" width="25" height="15" fill="#0f172a"/>
                <rect x="155" y="155" width="15" height="20" fill="#0284c7"/>

                <!-- Center Logo representation -->
                <circle cx="100" cy="100" r="14" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                <text x="100" y="104" fill="#0284c7" font-family="sans-serif" font-weight="bold" font-size="11" text-anchor="middle">DIV</text>
            </svg>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('payment.upload', $reservation->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="text-align: left;">
                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran (JPG/JPEG/PNG, maks 2MB)</label>
                <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="form-control" accept="image/jpeg,image/png,image/jpg" required style="padding: 0.5rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; font-size: 1.05rem;">
                🚀 Kirim Bukti Pembayaran
            </button>
        </form>
    @endif

    <div style="margin-top: 1rem;">
        <a href="{{ route('my.reservations') }}" class="btn btn-outline" style="width: 100%;">Kembali ke Riwayat Reservasi</a>
    </div>
</div>
@endsection

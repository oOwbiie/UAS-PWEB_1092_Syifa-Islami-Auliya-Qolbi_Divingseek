@extends('layouts.app')

@section('title', 'Detail Reservasi #' . $reservation->id)

@section('content')
<div style="max-width: 650px; margin: 0 auto;">
    <div class="section-title">
        <h2>Struk Reservasi Diving</h2>
        <p>Terima kasih telah melakukan pemesanan. Berikut adalah rincian data reservasi Anda.</p>
    </div>

    <div class="glass-card" style="padding: 2.5rem; position: relative;">
        <!-- Watermark / Stamp for Paid status -->
        @if($reservation->status_pembayaran === 'paid' || $reservation->status_pembayaran === 'disetujui')
            <div style="position: absolute; top: 1.5rem; right: 1.5rem; border: 3px double #10b981; color: #10b981; font-weight: 800; font-size: 1.25rem; padding: 0.35rem 1rem; border-radius: 6px; transform: rotate(15deg); font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px;">
                LUNAS / APPROVED
            </div>
        @elseif($reservation->status_pembayaran === 'cancelled' || $reservation->status_pembayaran === 'ditolak')
            <div style="position: absolute; top: 1.5rem; right: 1.5rem; border: 3px double #ef4444; color: #ef4444; font-weight: 800; font-size: 1.25rem; padding: 0.35rem 1rem; border-radius: 6px; transform: rotate(15deg); font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px;">
                BATAL / CANCELLED
            </div>
        @else
            <div style="position: absolute; top: 1.5rem; right: 1.5rem; border: 3px double #f59e0b; color: #f59e0b; font-weight: 800; font-size: 1.25rem; padding: 0.35rem 1rem; border-radius: 6px; transform: rotate(15deg); font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 2px;">
                PENDING
            </div>
        @endif

        <h3 style="font-size: 1.25rem; border-bottom: 2px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1.5rem; color: var(--primary);">
            Invoice #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
        </h3>

        <!-- Section 1: Customer Info -->
        <div style="margin-bottom: 1.5rem;">
            <h4 style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted); margin-bottom: 0.5rem;">Data Diri Pelanggan</h4>
            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                <div>Nama Lengkap: <strong>{{ $reservation->nama_customer }}</strong></div>
                <div>Alamat Email: <strong>{{ $reservation->email }}</strong></div>
                <div>Nomor WhatsApp: <strong>{{ $reservation->nomor_hp }}</strong></div>
            </div>
        </div>

        <!-- Section 2: Reservation Info -->
        <div style="margin-bottom: 1.5rem;">
            <h4 style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted); margin-bottom: 0.5rem;">Rincian Paket & Tanggal</h4>
            <div class="glass-card" style="background: var(--bg-base); padding: 1.25rem; border-color: var(--border-color);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Nama Paket:</span>
                    <strong>{{ $reservation->package->nama_paket }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Kategori:</span>
                    <strong style="text-transform: capitalize;">{{ $reservation->package->kategori }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Durasi Menyelam:</span>
                    <strong>{{ $reservation->package->durasi }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tanggal Diving:</span>
                    <strong>{{ date('d F Y', strtotime($reservation->tanggal_diving)) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Jumlah Peserta:</span>
                    <strong>{{ $reservation->jumlah_peserta }} orang</strong>
                </div>
            </div>
        </div>

        <!-- Section 3: Payment Details -->
        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted); margin-bottom: 0.5rem;">Rincian Pembayaran</h4>
            <div class="glass-card" style="background: var(--bg-base); padding: 1.25rem; border-color: var(--border-color);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tanggal Reservasi:</span>
                    <strong>{{ date('d F Y H:i', strtotime($reservation->created_at)) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tanggal Transfer:</span>
                    <strong>{{ $reservation->payment_date ? date('d F Y H:i', strtotime($reservation->payment_date)) : '-' }}</strong>
                </div>
                @if($reservation->verification_date)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Tanggal Verifikasi:</span>
                        <strong>{{ date('d F Y H:i', strtotime($reservation->verification_date)) }}</strong>
                    </div>
                @endif
                @if($reservation->verifiedByAdmin)
                    <div style="display: flex; justify-content: space-between;">
                        <span>Petugas Verifikasi:</span>
                        <strong>{{ $reservation->verifiedByAdmin->name }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Cost Summary -->
        <div style="border-top: 2px solid var(--border-color); padding-top: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; font-size: 1.25rem; font-weight: 800;">
                <span>Total Pembayaran:</span>
                <span style="color: var(--primary);">Rp {{ number_format($reservation->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Action buttons -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button onclick="window.print()" class="btn btn-outline" style="flex: 1;">
                🖨️ Cetak Struk
            </button>
            @if($reservation->status_pembayaran === 'pending')
                <a href="{{ route('payment.page', $reservation->id) }}" class="btn btn-primary" style="flex: 1;">
                    💳 Selesaikan Pembayaran
                </a>
            @else
                <a href="{{ route('home') }}" class="btn btn-primary" style="flex: 1;">
                    🏡 Kembali ke Beranda
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

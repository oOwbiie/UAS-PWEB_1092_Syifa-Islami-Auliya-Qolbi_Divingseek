@extends('layouts.app')

@section('title', 'Riwayat Reservasi Saya')

@section('content')
<div class="section-title">
    <h2>Riwayat Reservasi Saya</h2>
    <p>Pantau status transaksi pembayaran dan tanggal jadwal diving Anda di bawah ini.</p>
</div>

<div class="glass-card">
    @if($reservations->isEmpty())
        <div style="text-align: center; padding: 3rem 1rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📅</div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Belum Ada Reservasi</h3>
            <p style="color: var(--color-text-muted); margin-bottom: 1.5rem;">Anda belum memesan paket diving apa pun saat ini.</p>
            <a href="{{ route('packages') }}" class="btn btn-primary">Lihat Pilihan Paket</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Paket Diving</th>
                        <th>Tanggal Diving</th>
                        <th>Jumlah Peserta</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td><strong>#{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                            <td>{{ $reservation->package->nama_paket }}</td>
                            <td>{{ date('d M Y', strtotime($reservation->tanggal_diving)) }}</td>
                            <td>{{ $reservation->jumlah_peserta }} orang</td>
                            <td><strong>Rp {{ number_format($reservation->total_harga, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $reservation->status_pembayaran }}">
                                    {{ $reservation->status_pembayaran }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    @if($reservation->status_pembayaran === 'paid' || $reservation->status_pembayaran === 'disetujui')
                                        <a href="{{ route('reservation.detail', $reservation->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            📄 Lihat Invoice
                                        </a>
                                    @elseif($reservation->status_pembayaran === 'pending' || $reservation->status_pembayaran === 'ditolak')
                                        <a href="{{ route('payment.page', $reservation->id) }}" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            💳 Bayar
                                        </a>
                                    @else
                                        <a href="{{ route('payment.page', $reservation->id) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                            🔍 Cek Status
                                        </a>
                                    @endif
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

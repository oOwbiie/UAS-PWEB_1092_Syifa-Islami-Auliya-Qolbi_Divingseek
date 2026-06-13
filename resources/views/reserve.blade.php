@extends('layouts.app')

@section('title', 'Form Reservasi Paket Diving')

@section('content')
<div style="max-width: 750px; margin: 0 auto;">
    <div class="section-title">
        <h2>Form Reservasi Diving</h2>
        <p>Lengkapi formulir pemesanan di bawah ini untuk mengamankan slot petualangan diving Anda.</p>
    </div>

    <div class="glass-card" style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 2.5rem; align-items: start;">
        <!-- Left Column: Form -->
        <form action="{{ route('reserve.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">

            <div class="form-group">
                <label for="nama_customer" class="form-label">Nama Lengkap</label>
                <input type="text" id="nama_customer" name="nama_customer" class="form-control" 
                       value="{{ old('nama_customer', auth()->user()->name) }}" required placeholder="Contoh: Budi Santoso">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="{{ old('email', auth()->user()->email) }}" required placeholder="Contoh: budi@mail.com">
            </div>

            <div class="form-group">
                <label for="nomor_hp" class="form-label">Nomor WhatsApp / HP</label>
                <input type="text" id="nomor_hp" name="nomor_hp" class="form-control" 
                       value="{{ old('nomor_hp') }}" required placeholder="Contoh: 081234567890">
            </div>

            <div class="form-group">
                <label for="tanggal_diving" class="form-label">Tanggal Diving</label>
                <input type="date" id="tanggal_diving" name="tanggal_diving" class="form-control" 
                       value="{{ old('tanggal_diving', date('Y-m-d', strtotime('+1 day'))) }}" required min="{{ date('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                <input type="number" id="jumlah_peserta" name="jumlah_peserta" class="form-control" 
                       value="{{ old('jumlah_peserta', 1) }}" required min="1" max="20" oninput="calculateTotal()">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem; margin-top: 1rem;">
                Lanjutkan Ke Pembayaran QRIS
            </button>
        </form>

        <!-- Right Column: Package Details & Price Summary -->
        <div class="glass-card" style="background: var(--bg-base); padding: 1.5rem; border-color: var(--border-color); height: 100%;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; color: var(--primary);">Detail Paket</h3>
            
            <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem;">{{ $package->nama_paket }}</h4>
            <p style="font-size: 0.85rem; color: var(--color-text-muted); line-height: 1.5; margin-bottom: 1.25rem;">
                {{ Str::limit($package->deskripsi, 100) }}
            </p>

            <div style="font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <div>⏱️ Durasi: <strong>{{ $package->durasi }}</strong></div>
                <div>🏷️ Kategori: <strong style="text-transform: capitalize;">{{ $package->kategori }}</strong></div>
            </div>

            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; margin-top: 1rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.5rem; color: var(--color-text-muted);">
                    <span>Harga per orang:</span>
                    <span>Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 0.75rem; color: var(--color-text-muted);">
                    <span>Jumlah peserta:</span>
                    <span id="summary-qty">1 orang</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; border-top: 1px dashed var(--border-color); padding-top: 0.75rem; color: var(--color-text-main);">
                    <span>Total Biaya:</span>
                    <span id="summary-total" style="color: var(--primary);">Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const hargaPaket = {{ $package->harga }};
    
    function calculateTotal() {
        const inputQty = document.getElementById('jumlah_peserta');
        const summaryQty = document.getElementById('summary-qty');
        const summaryTotal = document.getElementById('summary-total');
        
        let qty = parseInt(inputQty.value) || 1;
        if (qty < 1) qty = 1;
        
        const total = hargaPaket * qty;
        
        summaryQty.textContent = qty + ' orang';
        summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection

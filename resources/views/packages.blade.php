@extends('layouts.app')

@section('title', 'Pilihan Paket Diving Gili Trawangan')

@section('content')
<div class="section-title">
    <h2>Paket Diving yang Tersedia</h2>
    <p>Kami menawarkan paket petualangan bawah laut terlengkap untuk mewujudkan impian menyelam Anda di Gili Trawangan.</p>
</div>

<div class="packages-grid">
    @foreach($packages as $package)
        <div class="glass-card package-card">
            @php
                $isUrl = $package->gambar && filter_var($package->gambar, FILTER_VALIDATE_URL);
                $isLocal = $package->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($package->gambar);
            @endphp
            <!-- Ocean-themed placeholder banner or real image -->
            <div style="position: relative; height: 200px; background: linear-gradient(45deg, #0f172a 0%, #0284c7 50%, #0d9488 100%); display: flex; align-items: center; justify-content: center; color: white; overflow: hidden;">
                <span class="package-badge badge-{{ $package->kategori }}">{{ $package->kategori }}</span>
                @if($isUrl)
                    <img src="{{ $package->gambar }}?v={{ $package->updated_at->timestamp }}" alt="{{ $package->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                @elseif($isLocal)
                    <img src="{{ asset('storage/' . $package->gambar) }}?v={{ $package->updated_at->timestamp }}" alt="{{ $package->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <div style="text-align: center;">
                        <div style="font-size: 4.5rem; margin-bottom: 0.5rem;">
                            @if($package->kategori === 'beginner') 🧜‍♂️ @elseif($package->kategori === 'intermediate') 🐠 @else 🦈 @endif
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="package-body">
                <div class="package-header">
                    <h3 style="font-size: 1.5rem;">{{ $package->nama_paket }}</h3>
                </div>
                
                <div class="package-price">
                    Rp {{ number_format($package->harga, 0, ',', '.') }} <span style="font-size: 0.9rem; font-weight: normal; color: var(--color-text-muted);">/ peserta</span>
                </div>
                
                <p class="package-desc" style="margin-bottom: 1.5rem;">{{ $package->deskripsi }}</p>
                
                <div class="package-meta" style="margin-bottom: 2rem;">
                    <div class="meta-item">
                        <span class="meta-icon">⏱️</span>
                        <span>Durasi Menyelam: <strong>{{ $package->durasi }}</strong></span>
                    </div>
                    <div class="meta-item" style="align-items: flex-start;">
                        <span class="meta-icon">📋</span>
                        <div>
                            <span style="font-weight: 600; display: block; margin-bottom: 0.25rem;">Fasilitas Termasuk:</span>
                            <ul style="margin-left: 1.25rem; font-size: 0.85rem; color: var(--color-text-muted); display: flex; flex-direction: column; gap: 0.25rem;">
                                @foreach(explode("\n", $package->fasilitas) as $facility)
                                    @if(trim($facility))
                                        <li>{{ trim($facility) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('reserve.form', $package->id) }}" class="btn btn-primary" style="margin-top: auto; width: 100%;">
                    Pesan Paket Ini
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection

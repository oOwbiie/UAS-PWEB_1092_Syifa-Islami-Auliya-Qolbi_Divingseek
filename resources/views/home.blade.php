@extends('layouts.app')

@section('title', 'Selamat Datang di Gili Diving Center')

@section('content')
<!-- Hero Section -->
<section class="hero glass-card" style="margin-bottom: 5rem;">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Jelajahi Keindahan Bawah Laut <span>Gili Trawangan</span></h1>
            <p>Rasakan petualangan menyelam spektakuler bersama instruktur profesional kami. Temukan keanekaragaman terumbu karang eksotis, penyu laut raksasa, dan spot diving premium kelas dunia.</p>
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('packages') }}" class="btn btn-primary">Lihat Paket Diving</a>
                <a href="#about-diving" class="btn btn-outline">Pelajari Lebih Lanjut</a>
            </div>
        </div>
        <div class="hero-image-wrapper">
            <!-- Ocean diving illustration represented inside a modern styled background -->
            <div style="width: 100%; height: 350px; background: linear-gradient(180deg, #0284c7 0%, #0f172a 100%); border-radius: 24px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
                <div style="position: absolute; width: 200px; height: 200px; background: rgba(56, 189, 248, 0.2); filter: blur(50px); top: 20%; left: 20%; border-radius: 50%;"></div>
                <div style="z-index: 1; text-align: center; color: white; padding: 2rem;">
                    <div style="font-size: 5rem; margin-bottom: 1rem;">🤿🐢🐠</div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">Gili Trawangan Dive Center</h3>
                    <p style="font-size: 0.9rem; opacity: 0.8;">Surga Menyelam Terbaik di Indonesia</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Diving Section -->
<section id="about-diving" style="margin-bottom: 5rem; scroll-margin-top: 100px;">
    <div class="section-title">
        <h2>Mengenal Olahraga Diving</h2>
        <p>Lebih dari sekadar berenang, diving adalah gerbang menuju dunia baru yang menakjubkan.</p>
    </div>
    
    <div class="glass-card" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
        <div>
            <div style="font-size: 4rem; margin-bottom: 1rem;">🌊</div>
            <h3 style="font-size: 1.75rem; margin-bottom: 1rem;">Mengapa Diving Sangat Istimewa?</h3>
            <p style="color: var(--color-text-muted); line-height: 1.8; margin-bottom: 1rem;">
                Scuba Diving (Self-Contained Underwater Breathing Apparatus) memungkinkan kita bernapas di bawah air menggunakan tabung oksigen khusus. Olahraga ini memberikan kebebasan mutlak untuk melayang secara tiga dimensi, mengeksplorasi ekosistem laut yang tidak bisa dijangkau oleh manusia biasa.
            </p>
            <p style="color: var(--color-text-muted); line-height: 1.8;">
                Gili Trawangan di Lombok Utara terkenal di seluruh penjuru dunia karena memiliki air laut yang super jernih dengan visibilitas luar biasa sepanjang tahun, serta suhu air hangat yang sangat nyaman untuk menyelam.
            </p>
        </div>
        
        <div>
            <div class="glass-card" style="background: var(--primary-light); border-color: var(--primary); padding: 1.5rem; margin-bottom: 1rem;">
                <h4 style="margin-bottom: 0.5rem; color: var(--primary);">🫁 Kesehatan Kardiovaskular & Paru</h4>
                <p style="font-size: 0.9rem; color: var(--color-text-main);">Menyelam melatih teknik pernapasan dalam, meningkatkan kapasitas paru-paru, dan melancarkan sirkulasi darah ke seluruh tubuh.</p>
            </div>
            <div class="glass-card" style="background: var(--secondary-light); border-color: var(--secondary); padding: 1.5rem; margin-bottom: 1rem;">
                <h4 style="margin-bottom: 0.5rem; color: var(--secondary);">🧠 Menghilangkan Stres & Meditasi</h4>
                <p style="font-size: 0.9rem; color: var(--color-text-main);">Keheningan di bawah air membantu menenangkan pikiran, meredakan kecemasan, serta memicu hormon kebahagiaan (endorfin).</p>
            </div>
            <div class="glass-card" style="background: var(--accent-light); border-color: var(--accent); padding: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem; color: var(--accent);">💪 Kekuatan & Kelenturan Otot</h4>
                <p style="font-size: 0.9rem; color: var(--color-text-main);">Berenang menentang hambatan air secara lembut melatih seluruh kelompok otot tubuh tanpa membebani persendian.</p>
            </div>
        </div>
    </div>
</section>

<!-- Packages Showcase Section -->
<section style="margin-bottom: 5rem;">
    <div class="section-title">
        <h2>Pilihan Paket Diving Terpopuler</h2>
        <p>Pilih paket diving yang sesuai dengan sertifikasi dan tingkat pengalaman menyelam Anda.</p>
    </div>
    
    <div class="packages-grid">
        @foreach($packages as $package)
            <div class="glass-card package-card">
                @php
                    $isUrl = $package->gambar && filter_var($package->gambar, FILTER_VALIDATE_URL);
                    $isLocal = $package->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($package->gambar);
                @endphp
                <!-- Fallback to illustration or real image -->
                <div style="position: relative; height: 180px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; color: white; overflow: hidden;">
                    <span class="package-badge badge-{{ $package->kategori }}">{{ $package->kategori }}</span>
                    @if($isUrl)
                        <img src="{{ $package->gambar }}?v={{ $package->updated_at->timestamp }}" alt="{{ $package->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                    @elseif($isLocal)
                        <img src="{{ asset('storage/' . $package->gambar) }}?v={{ $package->updated_at->timestamp }}" alt="{{ $package->nama_paket }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <div style="font-size: 4rem;">
                            @if($package->kategori === 'beginner') 🔰 @elseif($package->kategori === 'intermediate') 🧭 @else 🏆 @endif
                        </div>
                    @endif
                </div>
                
                <div class="package-body">
                    <div class="package-header">
                        <h3>{{ $package->nama_paket }}</h3>
                    </div>
                    
                    <div class="package-price">
                        Rp {{ number_format($package->harga, 0, ',', '.') }} <span>/ orang</span>
                    </div>
                    
                    <p class="package-desc">{{ Str::limit($package->deskripsi, 120) }}</p>
                    
                    <div class="package-meta">
                        <div class="meta-item">
                            <span class="meta-icon">⏳</span>
                            <span>Durasi: <strong>{{ $package->durasi }}</strong></span>
                        </div>
                    </div>
                    
                    <a href="{{ route('reserve.form', $package->id) }}" class="btn btn-primary" style="margin-top: auto; width: 100%;">
                        Reservasi Sekarang
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection

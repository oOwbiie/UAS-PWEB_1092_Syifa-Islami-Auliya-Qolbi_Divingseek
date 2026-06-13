<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Reservasi Diving Gili Trawangan')</title>
    
    <!-- CSS File -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Theme Detection Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-icon">🤿</span>
                <span>GiliDiving</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="nav-link {{ Route::is('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('packages') }}" class="nav-link {{ Route::is('packages') || Route::is('reserve.form') ? 'active' : '' }}">Paket Diving</a></li>
                @auth
                    @if(auth()->user()->role === 'customer')
                        <li><a href="{{ route('my.reservations') }}" class="nav-link {{ Route::is('my.reservations') ? 'active' : '' }}">Reservasi Saya</a></li>
                    @else
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">Dashboard Admin</a></li>
                    @endif
                @endauth
            </ul>
            
            <div class="nav-actions">
                <!-- Theme Toggle Button -->
                <button type="button" id="theme-toggle" class="btn-theme" aria-label="Toggle Theme" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                </button>
                
                @auth
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span style="font-weight: 500; font-size: 0.9rem;">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Keluar</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container fade-in" style="flex-grow: 1; padding-top: 3rem; padding-bottom: 5rem;">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <span>🟢</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span>🔴</span> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="display: block;">
                <p><strong>Silakan periksa form inputan Anda:</strong></p>
                <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Dynamic Official Info -->
    @php
        $contact = \App\Models\ContactInformation::first();
    @endphp
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-column">
                <h3>Gili Diving Center</h3>
                <p>Pusat reservasi diving terpercaya di Gili Trawangan. Kami menyediakan pengalaman menyelam tak terlupakan untuk pemula hingga penyelam profesional dengan instruktur bersertifikasi internasional.</p>
                <p>&copy; {{ date('Y') }} Gili Diving Center. All rights reserved.</p>
            </div>
            <div class="footer-column">
                <h3>Tautan Penting</h3>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem;">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('packages') }}">Paket Diving</a></li>
                    @guest
                        <li><a href="{{ route('login') }}">Login Akun</a></li>
                    @else
                        @if(auth()->user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                        @else
                            <li><a href="{{ route('my.reservations') }}">Reservasi Saya</a></li>
                        @endif
                    @endguest
                </ul>
            </div>
            <div class="footer-column">
                <h3>Kontak Resmi Kantor</h3>
                @if($contact)
                    <ul class="footer-contact-list">
                        <li class="footer-contact-item">
                            <span class="footer-contact-icon">📍</span>
                            <span>{{ $contact->alamat }}</span>
                        </li>
                        <li class="footer-contact-item">
                            <span class="footer-contact-icon">📞</span>
                            <span>{{ $contact->nomor_hp }}</span>
                        </li>
                        <li class="footer-contact-item">
                            <span class="footer-contact-icon">✉️</span>
                            <span>{{ $contact->email }}</span>
                        </li>
                        <li class="footer-contact-item">
                            <span class="footer-contact-icon">🕒</span>
                            <span>Setiap Hari ({{ $contact->jam_buka }})</span>
                        </li>
                    </ul>
                @else
                    <p>Informasi kontak kantor sedang diperbarui oleh administrator.</p>
                @endif
            </div>
        </div>
    </footer>

    <!-- Theme Switcher Logic -->
    <script>
        function updateThemeIcon(theme) {
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        // Initialize Theme Icon on Load
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            updateThemeIcon(currentTheme);
        });
    </script>
    @yield('scripts')
</body>
</html>

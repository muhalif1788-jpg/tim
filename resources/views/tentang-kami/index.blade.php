<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Kedai Pesisir</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="LOGO" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-main">Predict &</span>
                        <span class="logo-sub">Selenpleapnya</span>
                    </div>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                        <li><a href="{{ route('customer.products.index') }}" class="nav-link">Produk</a></li>
                        <li><a href="{{ url('/tentang') }}" class="nav-link active">Tentang Kami</a></li>
                        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
                        
                        <!-- Keranjang -->
                        @auth
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                            @endphp
                            <li>
                                <a href="{{ route('cart.index') }}" class="nav-link">
                                    <i data-feather="shopping-cart"></i>
                                    Keranjang
                                    @if($cartCount > 0)
                                        <span class="cart-badge">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="nav-link">
                                    <i data-feather="shopping-cart"></i>
                                    Keranjang
                                </a>
                            </li>
                        @endauth
                        
                        <!-- User Info -->
                        @auth
                            <li class="user-info">
                                <i data-feather="user"></i>
                                <span>Hi, {{ Auth::user()->name }}</span>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                    @csrf
                                    <button type="submit" class="logout-btn">
                                        <i data-feather="log-out"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="login-btn">
                                    <i data-feather="log-in"></i>
                                    Login
                                </a>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Tentang Kami Hero Section -->
    <section class="tentang-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">TENTANG KAMI</h1>
                <p class="hero-subtitle">Menghadirkan Cita Rasa Autentik Parepare</p>
                <p class="hero-description">Kedai Pesisir adalah pelopor dalam menyajikan abon berkualitas tinggi dengan resep turun-temurun dari Parepare</p>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Content -->
    <section class="tentang-content">
        <div class="container">
            <!-- Visi Section -->
            <div class="section visi-section">
                <div class="section-header">
                    <h2>Visi</h2>
                </div>
                <div class="section-content">
                    <p class="visi-text">Menjadi penyedia abon berkualitas terbaik di Parepare</p>
                </div>
            </div>

            <!-- Misi Section -->
            <div class="section misi-section">
                <div class="section-header">
                    <h2>Misi</h2>
                </div>
                <div class="section-content">
                    <ul class="misi-list">
                        <li class="misi-item">
                            <div class="misi-icon">
                                <i data-feather="check-circle"></i>
                            </div>
                            <div class="misi-text">
                                Menyediakan produk abon yang higienis, lezat, dan bernilai gizi tinggi dengan bahan-bahan pilihan dari peternak lokal.
                            </div>
                        </li>
                        <li class="misi-item">
                            <div class="misi-icon">
                                <i data-feather="check-circle"></i>
                            </div>
                            <div class="misi-text">
                                Mengembangkan sistem penjualan online yang memudahkan pelanggan untuk membeli produk dengan aman dan efisien.
                            </div>
                        </li>
                        <li class="misi-item">
                            <div class="misi-icon">
                                <i data-feather="check-circle"></i>
                            </div>
                            <div class="misi-text">
                                Mendukung pemberdayaan pelaku UMKM lokal melalui kolaborasi dalam rantai pasok bahan baku.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Team Section -->
            <div class="section team-section">
                <div class="section-header">
                    <h2>Tim Kami</h2>
                </div>
                <div class="section-content">
                    <div class="team-grid">
                        <!-- Manager -->
                        <div class="team-member">
                            <div class="member-avatar">
                                <i data-feather="user"></i>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">ALIF</h3>
                                <p class="member-position">Manager</p>
                            </div>
                        </div>

                        <!-- Chef -->
                        <div class="team-member">
                            <div class="member-avatar">
                                <i data-feather="user"></i>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">ALIF</h3>
                                <p class="member-position">Chef</p>
                            </div>
                        </div>

                        <!-- Marketing Manager -->
                        <div class="team-member">
                            <div class="member-avatar">
                                <i data-feather="user"></i>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">ALIF</h3>
                                <p class="member-position">Marketing Manager</p>
                            </div>
                        </div>

                        <!-- Staff -->
                        <div class="team-member">
                            <div class="member-avatar">
                                <i data-feather="user"></i>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">ALIF</h3>
                                <p class="member-position">Staff</p>
                            </div>
                        </div>

                        <!-- Supply Chain Management -->
                        <div class="team-member">
                            <div class="member-avatar">
                                <i data-feather="user"></i>
                            </div>
                            <div class="member-info">
                                <h3 class="member-name">ALIF</h3>
                                <p class="member-position">Supply Chain Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sejarah Section -->
            <div class="section sejarah-section">
                <div class="section-header">
                    <h2>Sejarah Kami</h2>
                </div>
                <div class="section-content">
                    <p class="sejarah-text">
                        Kedai Pesisir didirikan dengan semangat untuk melestarikan cita rasa autentik abon khas Parepare. 
                        Dengan dedikasi dan komitmen terhadap kualitas, kami terus berinovasi untuk memberikan pengalaman 
                        kuliner terbaik kepada pelanggan setia kami.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>KEDAI PESISIR</h3>
                    <p>Kedai dengan sejuta cita rasa yang tak terlupakan</p>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>Email: info@kedaipesisir.com</p>
                    <p>Telp: (021) 1234-5678</p>
                </div>
                <div class="footer-section">
                    <h3>Alamat</h3>
                    <p>Jl. Contoh No. 123<br>Jakarta, Indonesia</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Kedai Pesisir. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>
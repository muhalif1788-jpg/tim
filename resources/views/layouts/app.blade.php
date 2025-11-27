<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abon Sapi - Cita Rasa yang Tak Terlupakan</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                        <li><a href="#home" class="nav-link active">Home</a></li>
                        <li><a href="#produk" class="nav-link">Produk</a></li>
                        <li><a href="#tentang" class="nav-link">Tentang</a></li>
                        <li><a href="#kontak" class="nav-link">Kontak</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-brand">
                    <h1 class="brand-title">ABON SAPI</h1>
                    <p class="brand-subtitle">ADAN AKAM</p>
                </div>
                
                <div class="hero-main">
                    <h2 class="hero-tagline">CITA RASA YANG TIDAK<br>TERLAIHKAN</h2>
                    <p class="hero-description">DENAI DENGAN SERRUN HATI<br>HIGIENIS DAN MURAH</p>
                </div>

                <div class="hero-footer">
                    <p class="hero-slogan">KEDAI DENGAN SEJUTA<br>CITA RASA</p>
                </div>

                <div class="hero-actions">
                    <button class="btn btn-primary">Pesan Sekarang</button>
                    <button class="btn btn-secondary">Lihat Produk</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🥩</div>
                    <h3>Bahan Premium</h3>
                    <p>Daging sapi pilihan kualitas terbaik</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🧼</div>
                    <h3>Higienis</h3>
                    <p>Proses produksi bersih dan terjamin</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Harga Terjangkau</h3>
                    <p>Kualitas premium harga bersahabat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products" id="produk">
        <div class="container">
            <h2 class="section-title">Produk Kami</h2>
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product1.jpg') }}" alt="Abon Sapi Original">
                    </div>
                    <div class="product-info">
                        <h3>Abon Sapi Original</h3>
                        <p class="product-price">Rp 45.000</p>
                        <button class="btn btn-small">Beli Sekarang</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product2.jpg') }}" alt="Abon Sapi Pedas">
                    </div>
                    <div class="product-info">
                        <h3>Abon Sapi Pedas</h3>
                        <p class="product-price">Rp 48.000</p>
                        <button class="btn btn-small">Beli Sekarang</button>
                    </div>
                </div>
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product3.jpg') }}" alt="Abon Sapi Balado">
                    </div>
                    <div class="product-info">
                        <h3>Abon Sapi Balado</h3>
                        <p class="product-price">Rp 50.000</p>
                        <button class="btn btn-small">Beli Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ABON SAPI</h3>
                    <p>Kedai dengan sejuta cita rasa yang tidak terlupakan</p>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>Email: info@abonsapi.com</p>
                    <p>Telp: (021) 1234-5678</p>
                </div>
                <div class="footer-section">
                    <h3>Alamat</h3>
                    <p>Jl. Contoh No. 123<br>Jakarta, Indonesia</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Abon Sapi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
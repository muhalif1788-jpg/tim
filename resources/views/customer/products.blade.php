<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Kedai Pesisir</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
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
                        <li><a href="{{ url('/produk') }}" class="nav-link active">Produk</a></li>
                        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang Kami</a></li>
                        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Products Hero Section -->
    <section class="products-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">PRODUK KAMI</h1>
                <p class="hero-subtitle">Beberapa Pilihan Abon Terbaik</p>
                <p class="hero-description">Temukan berbagai varian abon dengan cita rasa autentik dan kualitas premium</p>
            </div>
        </div>
    </section>

    <!-- Products Grid Section -->
    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <h2>Semua Produk</h2>
                <div class="products-filter">
                    <button class="filter-btn active">Semua</button>
                    <button class="filter-btn">Abon Sapi</button>
                    <button class="filter-btn">Abon Ayam</button>
                    <button class="filter-btn">Abon Ikan</button>
                </div>
            </div>

            <div class="products-grid">
                <!-- Product 1 -->
                <div class="product-card">
                    <div class="product-badge">Terlaris</div>
                    <div class="product-image">
                        <img src="{{ asset('images/product1.jpg') }}" alt="Abon Sapi Original">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Sapi Original</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★★</span>
                            <span class="rating-value">4.9</span>
                        </div>
                        <p class="product-description">Abon sapi pilihan dengan rasa original yang gurih dan nikmat</p>
                        <div class="product-price">
                            <span class="current-price">Rp 45.000</span>
                            <span class="original-price">Rp 50.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card">
                    <div class="product-badge">Baru</div>
                    <div class="product-image">
                        <img src="{{ asset('images/product2.jpg') }}" alt="Abon Sapi Pedas">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Sapi Pedas</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="rating-value">4.7</span>
                        </div>
                        <p class="product-description">Abon sapi dengan sensasi pedas yang menggugah selera</p>
                        <div class="product-price">
                            <span class="current-price">Rp 48.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product3.jpg') }}" alt="Abon Sapi Balado">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Sapi Balado</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="rating-value">4.6</span>
                        </div>
                        <p class="product-description">Abon sapi dengan bumbu balado khas Padang yang autentik</p>
                        <div class="product-price">
                            <span class="current-price">Rp 50.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product4.jpg') }}" alt="Abon Ayam Original">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Ayam Original</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="rating-value">4.5</span>
                        </div>
                        <p class="product-description">Abon ayam pilihan dengan tekstur lembut dan rasa gurih</p>
                        <div class="product-price">
                            <span class="current-price">Rp 35.000</span>
                            <span class="original-price">Rp 40.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/product5.jpg') }}" alt="Abon Ikan Tenggiri">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Ikan Tenggiri</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★☆</span>
                            <span class="rating-value">4.4</span>
                        </div>
                        <p class="product-description">Abon ikan tenggiri dengan cita rasa laut yang segar</p>
                        <div class="product-price">
                            <span class="current-price">Rp 42.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="product-card">
                    <div class="product-badge">Hemat</div>
                    <div class="product-image">
                        <img src="{{ asset('images/product6.jpg') }}" alt="Abon Sapi Keju">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Sapi Keju</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★★</span>
                            <span class="rating-value">4.8</span>
                        </div>
                        <p class="product-description">Perpaduan abon sapi dan keju yang creamy dan lezat</p>
                        <div class="product-price">
                            <span class="current-price">Rp 55.000</span>
                            <span class="original-price">Rp 65.000</span>
                        </div>
                        <div class="product-actions">
                            <button class="btn btn-primary">
                                <i data-feather="shopping-cart"></i>
                                Tambah Keranjang
                            </button>
                            <button class="btn btn-secondary">Detail</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="load-more">
                <button class="btn btn-outline">Muat Lebih Banyak</button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Tidak Menemukan yang Anda Cari?</h2>
                <p>Hubungi kami untuk produk custom atau pertanyaan lainnya</p>
                <a href="{{ url('/kontak') }}" class="btn btn-primary">Hubungi Kami</a>
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
    <script src="{{ asset('js/produk.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abon Sapi - Cita Rasa yang Tak Terlupakan</title>
    <script src="https://unpkg.com/feather-icons"></script>
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
                        <li><a href="#tentang" class="nav-link">Tentang Kami</a></li>
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
                <!-- Hero Image -->
                <div class="hero-image">
                    <img src="{{ asset('images/hero.jpg') }}" alt="Abon Sapi" class="hero-jpg">
                </div>

                <div class="hero-actions">
                    <button class="btn btn-primary">Pesan Sekarang</button>
                    <button class="btn btn-secondary">Lihat Produk</button>
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
            <p class="section-end">Selengkapnya>></p>
        </div>
    </section>
    
    <!-- Why Kedai Pesisir Section -->
    <section class="why-section" id="tentang">
        <div class="container">
            <div class="why-content">
                <!-- Left Side - Features -->
                <div class="why-features">
                    <div class="why-header">
                        <p class="why-subtitle">Why Kedai Pesisir ?</p>
                        <h2 class="why-title">CITA RASA YANG TIDAK<br>ADA DUANYA</h2>
                    </div>
                    
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i data-feather="heart"></i>
                            </div>
                            <div class="feature-text">
                                <h3>DIBUAT DENGAN SEPENUH HATI</h3>
                                <p>Abon Kedai Pesisir alkani dan dupra pithan yang dolah dengan reese traditionel, menyhasikan rasa gurih kiva pesisir Indonesia.</p>
                            </div>
                        </div>
                        
                        <div class="feature-divider"></div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i data-feather="shield"></i>
                            </div>
                            <div class="feature-text">
                                <h3>HIGIENIS DAN MURAH</h3>
                                <p>Setap genak karar dipenas higiene dan targa bulan mengavel, mehiga-rita rasa alane den kusitras kerekli.</p>
                            </div>
                        </div>
                        
                        <div class="feature-divider"></div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i data-feather="package"></i>
                            </div>
                            <div class="feature-text">
                                <h3>DIKEMAS DENGAN MODERN</h3>
                                <p>Karai hidroartinan menjhasikan zhou kout kengti dengan pengianun capat din kanssan modern yang patak untuk dinlansal kapan sijas.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Image -->
                <div class="why-image">
                    <img src="{{ asset('images/why-image.jpg') }}" alt="Why Choose Kedai Pesisir" class="why-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section class="tentang-section">
        <div class="container">
            <div class="tentang-content">
                <!-- Left Side - Image -->
                <div class="tentang-image">
                    <img src="{{ asset('images/tentang-image.jpg') }}" alt="Tentang Kedai Pesisir" class="tentang-img">
                </div>
                
                <!-- Right Side - Text Content -->
                <div class="tentang-text">
                    <div class="tentang-header">
                        <h2 class="tentang-title">Tentang Kami</h2>
                        <h3 class="tentang-subtitle">KEDAI DENGAN SEJUTA</h3>
                        <h4 class="tentang-tagline">CITA RASA!!</h4>
                    </div>
                    
                    <div class="tentang-description">
                        <p>
                            Di Soubi Postal, kami sentaya bahasi setiap saapan kawa menguhallesi kehittimizin den tuteikan. 
                            Ompain tahanslektan tehsalt dari terri diri dents, kami menguhallesi diyun yang telah kanya katt, 
                            tenga yang lepeng dan konsultati, akan kesang yang bengeni jirkan untuk pietayangan yang menzorisi 
                            cik tota sutantia kaka pelkek. Tentekan kachi kani itu mokana kaigamura tidak iPosefer nembara 
                            kakszanar untuk kempi adalah dahla.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Testimoni Section -->
    <section class="testimoni-section">
        <div class="container">
            <div class="testimoni-header">
                <h2 class="testimoni-title">APA KATA MEREKA?</h2>
            </div>
            
            <div class="testimoni-grid">
                <!-- Testimoni 1 -->
                <div class="testimoni-card">
                    <div class="testimoni-content">
                        <div class="quote-icon">“</div>
                        <p class="testimoni-text">
                            Abonnya enak banget! Rasanya authentic dan bikin ketagihan. 
                            Keluarga saya suka semua, terutama anak-anak.
                        </p>
                    </div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/avatar1.jpg') }}" alt="Customer 1">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Wijaya</h4>
                            <p>Ibu Rumah Tangga</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="testimoni-card">
                    <div class="testimoni-content">
                        <div class="quote-icon">“</div>
                        <p class="testimoni-text">
                            Qualitynya top! Higienis dan packingnya rapi. 
                            Pengiriman cepat pula. Sudah langganan 3 bulan.
                        </p>
                    </div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/avatar2.jpg') }}" alt="Customer 2">
                        </div>
                        <div class="author-info">
                            <h4>Budi Santoso</h4>
                            <p>Karyawan Swasta</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="testimoni-card">
                    <div class="testimoni-content">
                        <div class="quote-icon">“</div>
                        <p class="testimoni-text">
                            Harganya terjangkau untuk kualitas premium seperti ini. 
                            Rasa abon sapi originalnya benar-benar juara!
                        </p>
                    </div>
                    <div class="testimoni-author">
                        <div class="author-avatar">
                            <img src="{{ asset('images/avatar3.jpg') }}" alt="Customer 3">
                        </div>
                        <div class="author-info">
                            <h4>Maya Sari</h4>
                            <p>Mahasiswa</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testimoni Navigation Dots -->
            <div class="testimoni-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="kontak">
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

    <script>
        feather.replace();
    </script>
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
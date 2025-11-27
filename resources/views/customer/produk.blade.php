<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Abon Sapi</title>
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">AB</div>
                    <div class="logo-text">
                        <span class="logo-main">Predict &</span>
                        <span class="logo-sub">Selenpleapnya</span>
                    </div>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                        <li><a href="{{ url('/produk') }}" class="nav-link active">Produk</a></li>
                        <li><a href="{{ url('/kontakt') }}" class="nav-link">Kontakt</a></li>
                        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Apps Team Section -->
            <section class="apps-team">
                <div class="apps-header">
                    <h2 class="apps-title">Apps Team</h2>
                    <div class="team-members">
                        <span class="member">Ram Apps</span>
                        <span class="member">Run Tue</span>
                    </div>
                </div>
            </section>

            <!-- Products Header -->
            <section class="products-header">
                <h1 class="section-title">PRODUK KAMI</h1>
                <p class="section-subtitle">Beberapa Pilihan Abon</p>
            </section>

            <!-- Products Grid -->
            <section class="products-grid">
                <!-- Product 1 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/abon-ayam.jpg') }}" alt="Abon Ayam">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Ayam</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★</span>
                            <span class="rating-value">4,0</span>
                        </div>
                        <p class="product-price">Rp.20k-50k</p>
                        <button class="product-btn">Selengkapnya</button>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/abon-sapi.jpg') }}" alt="Abon Sapi">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Sapi</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★</span>
                            <span class="rating-value">4,0</span>
                        </div>
                        <p class="product-price">Rp.20k-50k</p>
                        <button class="product-btn">Selengkapnya</button>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ asset('images/abon-ikan.jpg') }}" alt="Abon Ikan">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Abon Ikan</h3>
                        <div class="product-rating">
                            <span class="stars">★★★★</span>
                            <span class="rating-value">4,0</span>
                        </div>
                        <p class="product-price">Rp.20k-50k</p>
                        <button class="product-btn">Selengkapnya</button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <div class="logo-icon">AB</div>
                    <div class="logo-text">
                        <span class="logo-main">Predict &</span>
                        <span class="logo-sub">Selenpleapnya</span>
                    </div>
                </div>
                
                <div class="footer-info">
                    <h3 class="footer-title">Contract Information</h3>
                    <p class="footer-description">
                        Kodi Projiri mengbahdan abon yang lebih hanya untuk secara orga dengan. 
                        Senkut dan kantak atau piliikan tapan. Tarih dari megbahdan cita atau 
                        anataka dan kantanangan manusia di
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/produk.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abon Ummi - Cita Rasa yang Tak Terlupakan</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="{{ url('/') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <img src="{{ asset('images/logo.png') }}" alt="LOGO" class="logo-img">
                    <div class="logo-text">
                        <span class="logo-main">ABON</span>
                        <span class="logo-sub">UMMI</span>
                    </div>
                </a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="#home" class="nav-link active">Beranda</a></li>
                    
                    <!-- Auth Logic untuk Produk Link -->
                    @auth
                        <li><a href="{{ route('customer.products.index') }}" class="nav-link">Produk</a></li>
                    @else
                        <li><a href="#produk" class="nav-link">Produk</a></li>
                    @endauth
                    

                    
                    <li><a href="#tentang" class="nav-link">Tentang Kami</a></li>
                    <li><a href="#kontak" class="nav-link">Kontak</a></li>

                                        <!-- Navbar Keranjang -->
                    @auth
                        <li><a href="{{ route('cart.index') }}" class="nav-link">
                            <i data-feather="shopping-cart"></i>
                            Keranjang
                            @if(isset($cartCount) && $cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a></li>
                    @else
                        <li>
                            <button class="nav-link-btn" onclick="showLoginAlert('mengakses keranjang')">
                                <i data-feather="shopping-cart"></i>
                                
                            </button>
                        </li>
                    @endauth
                    
                    <!-- Auth Logic untuk Login/Logout -->
                    @auth
                        <li class="nav-auth">
                            <span class="user-welcome">
                                <i data-feather="user"></i>
                                Hi, {{ Auth::user()->name }}
                            </span>
                        </li>
                        <li class="nav-auth">
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="btn-logout">
                                    <i data-feather="log-out"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-auth">
                            <a href="{{ route('login') }}" class="btn-login">
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

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="container">
        <div class="hero-content">
            <!-- Hero Image -->
            <div class="hero-image">
                <img src="{{ asset('images/hero.jpg') }}" alt="Abon Sapi" class="hero-jpg">
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
 <section class="rekomendasi" id="rekomendasi" style="padding-bottom: 0;">
    <div class="container">
        <h2 class="section-title">Rekomendasi Untuk Anda</h2>
        
        @if(isset($recommendations) && $recommendations->isNotEmpty())
            <div class="products-grid">
                @foreach($recommendations as $product)
                <div class="product-card" style="border: 2px solid #ffc107;">
                    <div class="product-image">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->nama_produk }}">
                        @endif
                        <div style="position: absolute; top: 10px; left: 10px; background: #ffc107; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                            TERLARIS
                        </div>
                    </div>
                    <div class="product-info">
                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                            <i data-feather="star" style="width: 14px; height: 14px; fill: #ffc107; color: #ffc107;"></i>
                            <span style="font-size: 14px; font-weight: bold;">{{ number_format($product->avg_rating ?? 0, 1) }}</span>
                            <span style="font-size: 12px; color: #777;">({{ $product->total_terjual ?? 0 }} terjual)</span>
                        </div>
                        <h3>{{ $product->nama_produk }}</h3>
                        <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        
                        @if($product->stok > 0)
                            @auth
                                <button class="btn btn-small" onclick="handleOrder()">Beli Sekarang</button>
                            @else
                                <button class="btn btn-small" onclick="showLoginAlert('membeli {{ $product->nama_produk }}')">
                                    Beli Sekarang
                                </button>
                            @endauth
                        @else
                            <button class="btn btn-small btn-disabled" disabled>Stok Habis</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="no-products">
                <p>Belum ada rekomendasi saat ini.</p>
            </div>
        @endif
    </div>
</section>

<div style="margin-top: 50px;"></div>
<section class="produk" id="produk">
    <div class="container">
        <h2 class="section-title">Produk Kami</h2>
        
        @if($produk->isEmpty())
            <div class="no-products">
                <p>Belum ada produk tersedia saat ini.</p>
            </div>
        @else
            <div class="products-grid">
                @foreach($produk as $product)
                <div class="product-card">
                    <div class="product-image">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->nama_produk }}">
                        @endif
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->nama_produk }}</h3>  <!-- NAMA_PRODUK bukan name -->
                        <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        
                        @if($product->stok > 0)  <!-- STOK bukan stock -->
                            @auth
                                <button class="btn btn-small" onclick="handleOrder()">Beli Sekarang</button>
                            @else
                                <button class="btn btn-small" onclick="showLoginAlert('membeli {{ $product->nama_produk }}')">
                                    Beli Sekarang
                                </button>
                            @endauth
                        @else
                            <button class="btn btn-small btn-disabled" disabled>Stok Habis</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
<!-- Kenapa Harus Abon Umi -->
<section class="why-section" id="tentang">
    <div class="container">
        <div class="why-content">
            <!-- Left Side - Features -->
            <div class="why-features">
                <div class="why-header">
                    <p class="why-subtitle">Kenapa Harus Abon Umi ?</p>
                    <h2 class="why-title">CITA RASA YANG TIDAK<br>ADA DUANYA</h2>
                </div>
                
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="heart"></i>
                        </div>
                        <div class="feature-text">
                            <h3>DIBUAT DENGAN SEPENUH HATI</h3>
                            <p>Abon Ummi terbuat dari ikan pilihan yang diolah dengan resep tradisional, menghasilkan cita rasa gurih khas Indonesia.</p>
                        </div>
                    </div>
                    
                    <div class="feature-divider"></div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="shield"></i>
                        </div>
                        <div class="feature-text">
                            <h3>HIGIENIS DAN MURAH</h3>
                            <p>"Setiap tahap pengolahan diproses secara higienis guna menjaga kualitas, sehingga menghasilkan cita rasa alami yang konsisten dan berkualitas tinggi.</p>
                        </div>
                    </div>
                    
                    <div class="feature-divider"></div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i data-feather="package"></i>
                        </div>
                        <div class="feature-text">
                            <h3>DIKEMAS DENGAN MODERN</h3>
                            <p>Dikemas secara modern dan praktis, menjadikannya pilihan sempurna untuk dinikmati kapan pun Anda mau.</p>
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
                    Abon Ummi, kami senantiasa menghargai setiap masukan pelanggan guna meningkatkan kualitas dan pelayanan kami.
                    Dengan komitmen untuk memberikan yang terbaik, kami terus berinovasi melalui tim yang berdedikasi dan konsisten, 
                    demi memberikan pengalaman yang memuaskan bagi setiap pelanggan. Kepuasan Anda adalah prioritas utama kami.
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
                    <div class="quote-icon">"</div>
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
                    <div class="quote-icon">"</div>
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
                    <div class="quote-icon">"</div>
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
                <h3>ABON UMMI</h3>
                <p>Abon dengan sejuta cita rasa yang tidak terlupakan</p>
            </div>
            <div class="footer-section">
                <h3>Kontak</h3>
                <p>Email: abonummi@gmail.com</p>
                <p>Telp: (+62) 82394338184</p>
            </div>
            <div class="footer-section">
                <h3>Alamat</h3>
                <p>Jl. Keterampilan Cappa Galung Kota Parepare<br>Sulawesi Selatan, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Abon Ummi. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    // Fungsi untuk show login alert
    function showLoginAlert(action) {
        Swal.fire({
            title: 'Login Diperlukan',
            html: `Anda perlu login untuk <strong>${action}</strong>`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Nanti Saja'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}";
            }
        });
    }

    feather.replace();
</script>
<script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
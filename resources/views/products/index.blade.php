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
                        <li><a href="{{ route('customer.products.index') }}" class="nav-link active">Produk</a></li>
                        <li><a href="{{ url('/tentang') }}" class="nav-link">Tentang Kami</a></li>
                        <li><a href="{{ url('/kontak') }}" class="nav-link">Kontak</a></li>
                        
                        <!-- Keranjang -->
                        @auth
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                            @endphp
                            <li>
                                <a href="{{ route('cart.index') }}" class="nav-link">
                                    <i data-feather="shopping-cart"></i>
                                    
                                    @if($cartCount > 0)
                                        <span class="cart-badge">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="nav-link">
                                    <i data-feather="shopping-cart"></i>
                                    
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

    <!-- Products Hero Section -->
    <section class="products-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">PRODUK KAMI</h1>
                <p class="hero-subtitle">Beberapa Pilihan Abon Terbaik</p>
                <p class="hero-description">Temukan berbagai varian abon dengan cita rasa autentik dan kualitas premium</p>
                
                <!-- Search Form -->
                <form action="{{ route('customer.products.search') }}" method="GET" class="search-form">
                    <div class="search-box">
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Cari produk..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i data-feather="search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <h2>Semua Produk</h2>
                <div class="products-filter">
                    <!-- Filter Kategori -->
                    <a href="{{ route('customer.products.index') }}" 
                       class="filter-btn {{ !request()->has('kategori') ? 'active' : '' }}">
                        Semua
                    </a>
                    @foreach($kategoris as $kategori)
                    <a href="{{ route('customer.products.index', ['kategori' => $kategori->id]) }}" 
                       class="filter-btn {{ request('kategori') == $kategori->id ? 'active' : '' }}">
                        {{ $kategori->nama_kategori }}
                    </a>
                    @endforeach
                </div>
            </div>

            @if($produk->isEmpty())
                <div class="no-products">
                    <p>Belum ada produk tersedia saat ini.</p>
                </div>
            @else
                <div class="products-grid">
                    @foreach($produk as $item)
                    <div class="product-card">
                        @if($item->stok <= 5 && $item->stok > 0)
                            <div class="product-badge">Hampir Habis</div>
                        @elseif($item->stok == 0)
                            <div class="product-badge out-of-stock">Stok Habis</div>
                        @endif
                        
                        <div class="product-image">
                            @if($item->gambar)
                                <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_produk }}">
                            @else
                                <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $item->nama_produk }}">
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $item->nama_produk }}</h3>
                            
                            <!-- Kategori -->
                            @if($item->kategori)
                                <div class="product-category">
                                    <span class="category-badge">{{ $item->kategori->nama_kategori }}</span>
                                </div>
                            @endif
                            
                            <!-- Harga -->
                            <div class="product-price">
                                <span class="current-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="product-stock">Stok: {{ $item->stok }}</span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="product-actions">
                                @if($item->stok > 0)
                                    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="produk_id" value="{{ $item->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-feather="shopping-cart"></i>
                                            Tambah Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-primary" disabled>
                                        <i data-feather="shopping-cart"></i>
                                        Stok Habis
                                    </button>
                                @endif
                                
                                <a href="{{ route('customer.products.show', $item->id) }}" class="btn btn-secondary">Detail</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    {{ $produk->withQueryString()->links() }}
                </div>
            @endif
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
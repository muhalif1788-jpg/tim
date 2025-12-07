<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Kedai Pesisir</title>
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
                        <li><a href="{{ route('customer.products.index') }}" class="nav-link">Produk</a></li>
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

    <!-- Product Detail -->
    <section class="product-detail-section">
        <div class="container">
            <div class="product-detail-content">
                <!-- Product Image -->
                <div class="product-detail-image">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $produk->nama_produk }}">
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="product-detail-info">
                    <h1 class="product-detail-name">{{ $produk->nama_produk }}</h1>
                    
                    @if($produk->kategori)
                        <div class="product-category-detail">
                            <span>Kategori:</span>
                            <strong>{{ $produk->kategori->nama_kategori }}</strong>
                        </div>
                    @endif
                    
                    <div class="product-detail-price">
                        <h2>Rp {{ number_format($produk->harga, 0, ',', '.') }}</h2>
                    </div>
                    
                    <div class="product-stock-detail">
                        <span>Stok:</span>
                        <strong>{{ $produk->stok }} pcs</strong>
                    </div>
                    
                    @if($produk->deskripsi)
                        <div class="product-description-detail">
                            <h3>Deskripsi Produk</h3>
                            <p>{{ $produk->deskripsi }}</p>
                        </div>
                    @endif
                    
                    <!-- Add to Cart Form -->
                    @if($produk->stok > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form-detail">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                            
                            <div class="quantity-selector">
                                <label for="quantity">Jumlah:</label>
                                <input type="number" name="quantity" id="quantity" value="1" 
                                       min="1" max="{{ $produk->stok }}" class="quantity-input">
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-large">
                                <i data-feather="shopping-cart"></i>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    @else
                        <button class="btn btn-primary btn-large" disabled>
                            <i data-feather="shopping-cart"></i>
                            Stok Habis
                        </button>
                    @endif
                    
                    <!-- Back to Products -->
                    <a href="{{ route('customer.products.index') }}" class="btn btn-secondary">
                        <i data-feather="arrow-left"></i>
                        Kembali ke Produk
                    </a>
                </div>
            </div>
            
            <!-- Related Products -->
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="related-products">
                    <h3>Produk Terkait</h3>
                    <div class="related-products-grid">
                        @foreach($relatedProducts as $related)
                        <div class="related-product-card">
                            <div class="related-product-image">
                                @if($related->gambar)
                                    <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->nama_produk }}">
                                @else
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $related->nama_produk }}">
                                @endif
                            </div>
                            <div class="related-product-info">
                                <h4>{{ $related->nama_produk }}</h4>
                                <p class="related-price">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                <a href="{{ route('customer.products.show', $related->id) }}" class="btn btn-secondary btn-small">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
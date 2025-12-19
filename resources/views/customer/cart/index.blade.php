<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Abon Sapi</title>
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <span class="logo-main">Predict &</span>
                        <span class="logo-sub">Selenpleapnya</span>
                    </div>
                </a>
            </div>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                    <li><a href="{{ route('customer.products.index') }}" class="nav-link">Produk</a></li>
                    <li><a href="{{ route('cart.index') }}" class="nav-link active">
                        <i data-feather="shopping-cart"></i>
                        @if($carts->count() > 0)
                            <span class="cart-badge" id="cart-count">{{ $total_items }}</span>
                        @endif
                    </a></li>
                    <li><a href="{{ url('/#tentang') }}" class="nav-link">Tentang Kami</a></li>
                    <li><a href="{{ url('/#kontak') }}" class="nav-link">Kontak</a></li>
                    
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
                </ul>
            </nav>
        </div>
    </div>
</header>

<!-- Alert Messages -->
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            <i data-feather="check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i data-feather="alert-circle"></i>
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info">
            <i data-feather="info"></i>
            {{ session('info') }}
        </div>
    @endif
</div>

<!-- Cart Section -->
<section class="cart-container">
    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title">
                <i data-feather="shopping-cart"></i>
                Keranjang Belanja
            </h1>
            <p class="cart-subtitle">Kelola produk yang ingin Anda beli</p>
        </div>

        @if($carts->count() > 0)
            <div class="cart-content">
                <!-- Cart Items -->
                <div class="cart-items">
                    <div class="cart-header-actions">
                        <h3 class="cart-header-title">Daftar Produk ({{ $total_items }})</h3>
                        <form action="{{ route('cart.clear') }}" method="POST" class="clear-form" id="clear-form">
                            @csrf
                            <button type="button" class="cart-clear-btn" onclick="confirmClearCart()">
                                <i data-feather="trash-2"></i>
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                    
                    @foreach($carts as $cart)
                        @if($cart->produk && $cart->produk->status)
                            <div class="cart-item" id="cart-item-{{ $cart->id }}">
                                <div class="item-image">
                                    @if($cart->produk->gambar)
                                        <img src="{{ asset('storage/' . $cart->produk->gambar) }}" 
                                             alt="{{ $cart->produk->nama }}" 
                                             onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                                    @else
                                        <img src="{{ asset('images/default-product.jpg') }}" 
                                             alt="{{ $cart->produk->nama }}">
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <h4 class="item-name">{{ $cart->produk->nama }}</h4>
                                    @if($cart->produk->kategori)
                                        <span class="item-category">{{ $cart->produk->kategori->nama }}</span>
                                    @endif
                                    <p class="item-price">Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}</p>
                                    @if($cart->produk->stok < 10 && $cart->produk->stok > 0)
                                        <p class="stock-warning">Stok tinggal: {{ $cart->produk->stok }}</p>
                                    @elseif($cart->produk->stok == 0)
                                        <p class="stock-out">Stok habis</p>
                                    @endif
                                </div>
                                
                                <div class="item-actions">
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="quantity-form" id="quantity-form-{{ $cart->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="quantity-control">
                                            <button type="button" class="quantity-btn" onclick="decreaseQuantity({{ $cart->id }})">
                                                <i data-feather="minus"></i>
                                            </button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $cart->quantity }}" 
                                                   min="1" 
                                                   max="{{ min($cart->produk->stok, 99) }}"
                                                   class="quantity-input"
                                                   id="quantity-input-{{ $cart->id }}"
                                                   onchange="updateQuantity({{ $cart->id }})">
                                            <button type="button" class="quantity-btn" onclick="increaseQuantity({{ $cart->id }})">
                                                <i data-feather="plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="remove-form" id="remove-form-{{ $cart->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-remove" onclick="confirmRemoveItem({{ $cart->id }})">
                                            <i data-feather="trash-2"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="cart-item unavailable">
                                <div class="item-image">
                                    <img src="{{ asset('images/default-product.jpg') }}" alt="Produk Tidak Tersedia">
                                </div>
                                
                                <div class="item-details">
                                    <h4 class="item-name text-muted">Produk Tidak Tersedia</h4>
                                    <p class="item-price">Rp 0</p>
                                </div>
                                
                                <div class="item-actions">
                                    <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="remove-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-remove">
                                            <i data-feather="trash-2"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 class="summary-title">Ringkasan Pesanan</h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotal ({{ $total_items }} item)</span>
                        <span class="summary-value" id="subtotal-display">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Ongkos Kirim</span>
                        <span class="summary-value" id="shipping-display">
                            @php
                                $shipping = $total > 100000 ? 0 : 15000;
                            @endphp
                            @if($shipping == 0)
                                <span class="free-shipping">Gratis</span>
                            @else
                                Rp {{ number_format($shipping, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    
                    @php
                        $discount = $total > 150000 ? $total * 0.1 : 0;
                        $grand_total = $total + $shipping - $discount;
                    @endphp
                    
                    @if($discount > 0)
                    <div class="summary-row">
                        <span class="summary-label">Diskon (10%)</span>
                        <span class="summary-value discount" id="discount-display">
                            - Rp {{ number_format($discount, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="summary-row summary-total">
                        <span class="summary-label">Total</span>
                        <span class="summary-value" id="total-display">Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                    </div>
                    
                    <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary checkout-btn" onclick="return validateCart()">
                        <i data-feather="credit-card"></i>
                        Lanjut ke Pembayaran
                    </a>
                    
                    <div class="continue-shopping">
                        <a href="{{ route('customer.products.index') }}" class="btn-secondary">
                            <i data-feather="arrow-left"></i>
                            Lanjutkan Belanja
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i data-feather="shopping-cart"></i>
                </div>
                <h2>Keranjang Anda Kosong</h2>
                <p>Tambahkan beberapa produk favorit Anda untuk mulai berbelanja</p>
                <a href="{{ route('customer.products.index') }}" class="btn-primary">
                    <i data-feather="shopping-bag"></i>
                    Mulai Belanja
                </a>
            </div>
        @endif
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

<script src="{{ asset('js/cart.js') }}"></script>
<script>
    // Initialize feather icons
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
</body>
</html>
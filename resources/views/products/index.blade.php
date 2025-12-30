@extends('layouts.customer')

@section('title', 'Produk - Kedai Pesisir')

@section('content')
<div class="products-page">
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
                                        <button type="submit" class="btn btn-primary add-to-cart-btn">
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
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
        
        // Add to cart form submission
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('.add-to-cart-btn');
                const originalHTML = button.innerHTML;
                
                // Show loading state
                button.innerHTML = '<i data-feather="loader"></i> Menambahkan...';
                button.disabled = true;
                feather.replace();
                
                // Submit form
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count in header
                        updateCartCount(data.cartCount);
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Produk ditambahkan ke keranjang',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Gagal menambahkan ke keranjang'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menambahkan ke keranjang'
                    });
                })
                .finally(() => {
                    // Reset button
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                    feather.replace();
                });
            });
        });
        
        // Update cart count function
        function updateCartCount(count) {
            let cartBadge = document.querySelector('.cart-badge');
            const cartLink = document.querySelector('a[href="{{ route("cart.index") }}"]');
            
            if (count > 0) {
                if (!cartBadge) {
                    cartBadge = document.createElement('span');
                    cartBadge.className = 'cart-badge';
                    cartBadge.textContent = count;
                    cartLink.appendChild(cartBadge);
                } else {
                    cartBadge.textContent = count;
                }
            } else if (cartBadge) {
                cartBadge.remove();
            }
        }
        
        // Load initial cart count
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    updateCartCount(data.count);
                }
            });
    });
</script>
@endsection
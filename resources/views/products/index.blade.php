@extends('layouts.customer')

@section('title', 'Produk - Kedai Pesisir')

@section('content')
<div class="products-page">
    <section class="products-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">PRODUK KAMI</h1>
                <p class="hero-subtitle">Beberapa Pilihan Abon Terbaik</p>
                <p class="hero-description">Temukan berbagai varian abon dengan cita rasa autentik dan kualitas premium</p>
                
                <form action="{{ route('customer.products.index') }}" method="GET" class="search-form">
                    <div class="search-box">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari produk..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i data-feather="search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="container">
            <div class="products-header">
                <h2>Semua Produk</h2>
                
                <div class="products-filter">
                    <a href="{{ route('customer.products.index', array_merge(request()->except('kategori'), ['sort' => request('sort', 'terbaru')])) }}" 
                       class="filter-btn {{ !request()->has('kategori') ? 'active' : '' }}">
                        Semua
                    </a>
                    @foreach($kategoris as $kategori)
                    <a href="{{ route('customer.products.index', array_merge(request()->except('kategori'), ['kategori' => $kategori->id, 'sort' => request('sort', 'terbaru')])) }}" 
                       class="filter-btn {{ request('kategori') == $kategori->id ? 'active' : '' }}">
                        {{ $kategori->nama_kategori }}
                    </a>
                    @endforeach
                </div>
            </div>

            @if(request('search'))
                <div class="search-info">
                    <p>Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></p>
                    <a href="{{ route('customer.products.index') }}" class="clear-search">
                        <i data-feather="x"></i> Hapus pencarian
                    </a>
                </div>
            @endif

            @if($produk->isEmpty())
                <div class="no-products">
                    @if(request('search'))
                        <p>Tidak ada produk yang sesuai dengan pencarian "<strong>{{ request('search') }}</strong>".</p>
                    @elseif(request('kategori'))
                        <p>Tidak ada produk dalam kategori ini.</p>
                    @else
                        <p>Belum ada produk tersedia saat ini.</p>
                    @endif
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                        Lihat Semua Produk
                    </a>
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
                                <img src="{{ asset('storage/' . $item->gambar) }}" 
                                     alt="{{ $item->nama_produk }}" loading="lazy">
                            @else
                                <img src="{{ asset('images/default-product.jpg') }}" 
                                     alt="{{ $item->nama_produk }}" loading="lazy">
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $item->nama_produk }}</h3>
                            
                            <div class="rating-display" style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                                @php 
                                    // PERBAIKAN: Menggunakan $item untuk rata-rata dan count
                                    $avgRating = $item->penilaian->avg('rating') ?: 0; 
                                    $totalPenilaian = $item->penilaian->count();
                                @endphp
                                
                                <i data-feather="star" style="width: 14px; height: 14px; fill: #fbbf24; color: #fbbf24;"></i>
                                
                                <span style="font-size: 13px; color: #6b7280; font-weight: 600;">
                                    {{ number_format($avgRating, 1) }} 
                                    <span style="font-weight: 400; color: #9ca3af;">({{ $totalPenilaian }})</span>
                                </span>
                            </div>
                            
                            @if($item->kategori)
                                <div class="product-category">
                                    <span class="category-badge">{{ $item->kategori->nama_kategori }}</span>
                                </div>
                            @endif
                            
                            <div class="product-price">
                                <span class="current-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="product-stock">Stok: {{ $item->stok }}</span>
                            </div>
                            
                            @if($item->deskripsi)
                                <div class="product-description-short">
                                    {{ Str::limit(strip_tags($item->deskripsi), 60) }}
                                </div>
                            @endif
                            
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
                                
                                <a href="{{ route('customer.products.show', $item->id) }}" 
                                   class="btn btn-secondary">Detail</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
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
                        updateCartCount(data.cartCount);
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
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                    feather.replace();
                });
            });
        });
        
        function updateCartCount(count) {
            let cartBadge = document.querySelector('.cart-badge');
            const cartLink = document.querySelector('a[href="{{ route("cart.index") }}"]');
            
            if (count > 0) {
                if (!cartBadge) {
                    cartBadge = document.createElement('span');
                    cartBadge.className = 'cart-badge';
                    cartBadge.textContent = count;
                    if(cartLink) cartLink.appendChild(cartBadge);
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
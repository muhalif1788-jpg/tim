@extends('layouts.customer')

@section('title', $produk->nama_produk . ' - Kedai Pesisir')

@section('content')
<div class="product-detail-page">
    <!-- Product Detail Section -->
    <section class="product-detail-section">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <span>></span>
                <a href="{{ route('customer.products.index') }}">Produk</a>
                <span>></span>
                <span>{{ $produk->nama_produk }}</span>
            </div>
            
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
                        @if($produk->stok > 0)
                            <strong class="stock-available">{{ $produk->stok }} pcs</strong>
                        @else
                            <strong class="stock-unavailable">Stok Habis</strong>
                        @endif
                    </div>
                    
                    @if($produk->deskripsi)
                        <div class="product-description-detail">
                            <h3>Deskripsi Produk</h3>
                            <p>{{ nl2br(e($produk->deskripsi)) }}</p>
                        </div>
                    @endif
                    
                    <!-- Add to Cart Form -->
                    @if($produk->stok > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form-detail" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $produk->id }}">
                            
                            <div class="quantity-selector">
                                <label for="quantity">Jumlah:</label>
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn minus" onclick="decreaseQuantity()">-</button>
                                    <input type="number" name="quantity" id="quantity" value="1" 
                                           min="1" max="{{ $produk->stok }}" class="quantity-input">
                                    <button type="button" class="quantity-btn plus" onclick="increaseQuantity()">+</button>
                                </div>
                                <span class="max-stock">Maks: {{ $produk->stok }} pcs</span>
                            </div>
                            
                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary btn-large" id="addToCartBtn">
                                    <i data-feather="shopping-cart"></i>
                                    Tambah ke Keranjang
                                </button>
                                
                                @auth
                                    <button type="button" class="btn btn-secondary btn-large" onclick="buyNow()">
                                        <i data-feather="shopping-bag"></i>
                                        Beli Sekarang
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-large" onclick="showLoginAlert('membeli produk')">
                                        <i data-feather="shopping-bag"></i>
                                        Beli Sekarang
                                    </button>
                                @endauth
                            </div>
                        </form>
                    @else
                        <div class="out-of-stock-alert">
                            <div class="alert alert-warning">
                                <i data-feather="alert-circle"></i>
                                <span>Produk sedang tidak tersedia. Silakan cek kembali nanti.</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Share Product -->
                    <div class="product-share">
                        <span>Bagikan:</span>
                        <div class="share-buttons">
                            <button class="share-btn" onclick="shareProduct('facebook')">
                                <i data-feather="facebook"></i>
                            </button>
                            <button class="share-btn" onclick="shareProduct('twitter')">
                                <i data-feather="twitter"></i>
                            </button>
                            <button class="share-btn" onclick="shareProduct('whatsapp')">
                                <i data-feather="message-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="related-products">
                    <h3 class="section-title">Produk Terkait</h3>
                    <div class="related-products-grid">
                        @foreach($relatedProducts as $related)
                        <div class="related-product-card">
                            <div class="related-product-image">
                                <a href="{{ route('customer.products.show', $related->id) }}">
                                    @if($related->gambar)
                                        <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->nama_produk }}">
                                    @else
                                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $related->nama_produk }}">
                                    @endif
                                </a>
                                @if($related->stok == 0)
                                    <div class="product-badge out-of-stock">Habis</div>
                                @elseif($related->stok <= 5)
                                    <div class="product-badge">Hampir Habis</div>
                                @endif
                            </div>
                            <div class="related-product-info">
                                <h4>
                                    <a href="{{ route('customer.products.show', $related->id) }}">
                                        {{ $related->nama_produk }}
                                    </a>
                                </h4>
                                <div class="related-product-price">
                                    <span class="current-price">Rp {{ number_format($related->harga, 0, ',', '.') }}</span>
                                    <span class="product-stock">Stok: {{ $related->stok }}</span>
                                </div>
                                <div class="related-product-actions">
                                    @if($related->stok > 0)
                                        <a href="{{ route('customer.products.show', $related->id) }}" 
                                           class="btn btn-secondary btn-small">
                                            <i data-feather="eye"></i>
                                            Lihat Detail
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-small" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
        
        // Add to cart form submission
        const addToCartForm = document.getElementById('addToCartForm');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = document.getElementById('addToCartBtn');
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
    
    // Quantity control functions
    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    }
    
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        let value = parseInt(quantityInput.value);
        const max = parseInt(quantityInput.max);
        if (value < max) {
            quantityInput.value = value + 1;
        }
    }
    
    // Buy now function
    function buyNow() {
        const form = document.getElementById('addToCartForm');
        if (form) {
            // Create hidden input for redirect
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_to';
            redirectInput.value = '{{ route("cart.index") }}';
            form.appendChild(redirectInput);
            
            // Submit form
            form.submit();
        }
    }
    
    // Share product function
    function shareProduct(platform) {
        const url = window.location.href;
        const title = '{{ $produk->nama_produk }}';
        const text = 'Lihat produk ini: {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga, 0, ",", ".") }}';
        
        let shareUrl = '';
        
        switch(platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                break;
            case 'whatsapp':
                shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text + ' ' + url)}`;
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    }
    
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
    
    // Login alert for non-logged in users
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
</script>
@endsection
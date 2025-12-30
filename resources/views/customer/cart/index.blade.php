@extends('layouts.customer')

@section('title', 'Keranjang Belanja - Abon Sapi')
@section('content')
<div class="cart-page">
    <div class="container">
        <!-- Alert Messages -->
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

        <!-- Cart Header -->
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
                                             alt="{{ $cart->produk->nama_produk ?? $cart->produk->nama }}" 
                                             onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                                    @else
                                        <img src="{{ asset('images/default-product.jpg') }}" 
                                             alt="{{ $cart->produk->nama_produk ?? $cart->produk->nama }}">
                                    @endif
                                </div>
                                
                                <div class="item-details">
                                    <h4 class="item-name">{{ $cart->produk->nama_produk ?? $cart->produk->nama }}</h4>
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
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
        
        // Update cart count in header
        updateCartHeaderCount({{ $total_items ?? 0 }});
    });

    function updateCartHeaderCount(count) {
        const cartBadge = document.querySelector('.cart-badge');
        const cartLink = document.querySelector('a[href="{{ route("cart.index") }}"]');
        
        if (count > 0) {
            if (!cartBadge) {
                const badge = document.createElement('span');
                badge.className = 'cart-badge';
                badge.id = 'cart-count';
                badge.textContent = count;
                cartLink.appendChild(badge);
            } else {
                cartBadge.textContent = count;
            }
        } else if (cartBadge) {
            cartBadge.remove();
        }
    }

    function confirmClearCart() {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: "Semua item di keranjang akan dihapus. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('clear-form').submit();
            }
        });
    }

    function confirmRemoveItem(cartId) {
        Swal.fire({
            title: 'Hapus Item?',
            text: "Item ini akan dihapus dari keranjang",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove-form-' + cartId).submit();
            }
        });
    }

    function decreaseQuantity(cartId) {
        const input = document.getElementById('quantity-input-' + cartId);
        const currentValue = parseInt(input.value);
        
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateQuantity(cartId);
        }
    }

    function increaseQuantity(cartId) {
        const input = document.getElementById('quantity-input-' + cartId);
        const currentValue = parseInt(input.value);
        const max = parseInt(input.max);
        
        if (currentValue < max) {
            input.value = currentValue + 1;
            updateQuantity(cartId);
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Stok Terbatas',
                text: 'Jumlah melebihi stok yang tersedia',
            });
        }
    }

    function updateQuantity(cartId) {
        const form = document.getElementById('quantity-form-' + cartId);
        const input = document.getElementById('quantity-input-' + cartId);
        const quantity = parseInt(input.value);
        const max = parseInt(input.max);
        
        if (quantity < 1) {
            input.value = 1;
            return;
        }
        
        if (quantity > max) {
            input.value = max;
            Swal.fire({
                icon: 'info',
                title: 'Stok Terbatas',
                text: 'Jumlah disesuaikan dengan stok maksimum: ' + max,
            });
        }
        
        // Submit form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartHeaderCount(data.cartCount);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Jumlah item diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Reload page to update totals
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal memperbarui jumlah item'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat memperbarui jumlah'
            });
        });
    }

    function validateCart() {
        // Check if any product is out of stock
        const outOfStockItems = document.querySelectorAll('.stock-out');
        if (outOfStockItems.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Dapat Checkout',
                text: 'Ada produk yang stoknya habis. Silakan hapus terlebih dahulu.',
            });
            return false;
        }
        return true;
    }
</script>
@endsection
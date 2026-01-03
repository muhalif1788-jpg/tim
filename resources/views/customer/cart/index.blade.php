@extends('layouts.customer')

@section('title', 'Keranjang Belanja - Abon Sapi')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="cart-page">
    <div class="container">
        {{-- Alert Messages --}}
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

        {{-- Cart Header --}}
        <div class="cart-header">
            <h1 class="cart-title">
                <i data-feather="shopping-cart"></i>
                Keranjang Belanja
            </h1>
            <p class="cart-subtitle">Kelola produk yang ingin Anda beli</p>
        </div>

        {{-- Main Cart Content --}}
        <div id="cart-content-container">
            @if($carts->count() > 0)
                <div class="cart-content">
                    <div class="cart-items">
                        <div class="cart-header-actions">
                            <h3 class="cart-header-title">Daftar Produk (<span id="total-items-badge">{{ $total_items }}</span>)</h3>
                        </div>
                        
                        @foreach($carts as $cart)
                            @if($cart->produk && $cart->produk->status)
                                <div class="cart-item" id="cart-item-{{ $cart->id }}" data-cart-id="{{ $cart->id }}" data-max-stock="{{ $cart->produk->stok }}">
                                    <div class="item-image">
                                        <img src="{{ $cart->produk->gambar ? asset('storage/' . $cart->produk->gambar) : asset('images/default-product.jpg') }}" 
                                             alt="{{ $cart->produk->nama_produk ?? $cart->produk->nama }}"
                                             onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                                    </div>
                                    
                                    <div class="item-details">
                                        <h4 class="item-name">{{ $cart->produk->nama_produk ?? $cart->produk->nama }}</h4>
                                        @if($cart->produk->kategori)
                                            <span class="item-category">{{ $cart->produk->kategori->nama }}</span>
                                        @endif
                                        <p class="item-price" id="price-{{ $cart->id }}" data-price="{{ $cart->produk->harga }}">
                                            Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}
                                        </p>
                                        <p class="item-subtotal" id="subtotal-{{ $cart->id }}">
                                            Subtotal: Rp {{ number_format($cart->produk->harga * $cart->quantity, 0, ',', '.') }}
                                        </p>
                                        
                                        @if($cart->produk->stok < 10 && $cart->produk->stok > 0)
                                            <p class="stock-warning" id="stock-info-{{ $cart->id }}">
                                                Sisa Stok: <span class="stock-count">{{ $cart->produk->stok }}</span>
                                            </p>
                                        @elseif($cart->produk->stok <= 0)
                                            <p class="stock-out">Stok habis</p>
                                        @endif
                                    </div>
                                    
                                    <div class="item-actions">
                                        <div class="quantity-control">
                                            <button type="button" 
                                                    class="quantity-btn btn-minus" 
                                                    data-action="decrease"
                                                    data-cart-id="{{ $cart->id }}"
                                                    @if($cart->quantity <= 1) disabled @endif>
                                                <i data-feather="minus"></i>
                                            </button>
                                            
                                            <span class="quantity-display" id="quantity-display-{{ $cart->id }}">
                                                {{ $cart->quantity }}
                                            </span>

                                            <button type="button" 
                                                    class="quantity-btn btn-plus" 
                                                    data-action="increase"
                                                    data-cart-id="{{ $cart->id }}"
                                                    data-max-stock="{{ $cart->produk->stok }}"
                                                    @if($cart->quantity >= $cart->produk->stok) disabled @endif>
                                                <i data-feather="plus"></i>
                                            </button>
                                        </div>
                                        
                                        <button type="button" 
                                                class="btn-remove" 
                                                data-cart-id="{{ $cart->id }}">
                                            <i data-feather="trash-2"></i>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="cart-summary">
                        <h3 class="summary-title">Ringkasan Pesanan</h3>
                        <div class="summary-row">
                            <span>Subtotal (<span id="summary-items-count">{{ $total_items }}</span> item)</span>
                            <span id="subtotal-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        @php
                            $shipping = $total > 100000 ? 0 : 15000;
                            $discount = $total > 150000 ? $total * 0.1 : 0;
                            $grand_total = $total + $shipping - $discount;
                        @endphp

                        <div class="summary-row">
                            <span>Ongkos Kirim</span>
                            <span id="shipping-amount">{!! $shipping == 0 ? '<span class="free-shipping">Gratis</span>' : 'Rp '.number_format($shipping, 0, ',', '.') !!}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="summary-row">
                                <span>Diskon (10%)</span>
                                <span class="discount" id="discount-amount">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span id="grand-total-amount">Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
                        </div>
                        
                        <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary checkout-btn" id="checkout-btn">
                            <i data-feather="credit-card"></i>
                            Lanjut ke Pembayaran
                        </a>
                    </div>
                </div>
            @else
                {{-- Empty Cart State --}}
                <div class="cart-empty-state">
                    <div class="empty-cart">
                        <div class="empty-cart-icon">
                            <i data-feather="shopping-cart"></i>
                        </div>
                        <h2>Keranjang Belanja Anda Kosong</h2>
                        <p class="empty-cart-message">Tambahkan produk favorit Anda untuk mulai berbelanja</p>
                        <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                            <i data-feather="shopping-bag"></i>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Loading animation */
    .quantity-display.loading {
        position: relative;
        color: transparent;
    }
    
    .quantity-display.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 16px;
        height: 16px;
        margin: -8px 0 0 -8px;
        border: 2px solid rgba(41, 64, 102, 0.2);
        border-top-color: #294066;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Remove animation */
    .cart-item-removing {
        animation: fadeOut 0.3s ease forwards;
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    
    /* Button states */
    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Empty Cart State Styles */
    .cart-empty-state {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
        padding: 40px 20px;
        animation: fadeIn 0.5s ease;
    }
    
    .cart-empty-state .empty-cart {
        text-align: center;
        background: white;
        border-radius: 15px;
        padding: 50px 30px;
        box-shadow: 0 5px 20px rgba(41, 64, 102, 0.08);
        border: 2px solid rgba(41, 64, 102, 0.1);
        max-width: 500px;
        width: 100%;
    }
    
    .cart-empty-state .empty-cart-icon {
        margin-bottom: 25px;
    }
    
    .cart-empty-state .empty-cart-icon i {
        width: 80px;
        height: 80px;
        stroke-width: 1.5;
        color: #cbd5e1;
    }
    
    .cart-empty-state .empty-cart h2 {
        font-size: 24px;
        color: #294066;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .cart-empty-state .empty-cart-message {
        color: #64748b;
        margin-bottom: 30px;
        font-size: 16px;
        line-height: 1.6;
    }
    
    .cart-empty-state .btn-primary {
        background: linear-gradient(135deg, #294066 0%, #1a2d4d 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .cart-empty-state .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(41, 64, 102, 0.2);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive untuk empty cart */
    @media (max-width: 768px) {
        .cart-empty-state .empty-cart {
            padding: 40px 20px;
        }
        
        .cart-empty-state .empty-cart-icon i {
            width: 60px;
            height: 60px;
        }
        
        .cart-empty-state .empty-cart h2 {
            font-size: 20px;
        }
        
        .cart-empty-state .empty-cart-message {
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush
@extends('layouts.customer')

@section('title', 'Dashboard - Abon Sapi')

@section('content')
<div class="dashboard">
    <!-- Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-content">
            <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="welcome-text">Selamat berbelanja di Abon Sapi - Cita Rasa Tak Terlupakan</p>
        </div>
        <div class="welcome-date">
            <p>{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon cart-icon">
                <i data-feather="shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $cartCount }}</h3>
                <p>Item di Keranjang</p>
            </div>
            <a href="{{ route('cart.index') }}" class="stat-link">Lihat →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon transaction-icon">
                <i data-feather="credit-card"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $transactionCount }}</h3>
                <p>Transaksi Aktif</p>
            </div>
            <a href="#" class="stat-link">Lihat →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon product-icon">
                <i data-feather="shopping-bag"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $productCount }}</h3>
                <p>Produk Tersedia</p>
            </div>
            <a href="{{ route('customer.products.index') }}" class="stat-link">Belanja →</a>
        </div>
    </div>

    <section class="section recommendations-section">
        <div class="section-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h2 style="color: #d97706;">Rekomendasi Khusus Untukmu</h2>
                <span style="background: #fef3c7; color: #d97706; padding: 2px 8px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid #d97706;">BEST SELLER</span>
            </div>
        </div>

        @if(isset($recommendations) && $recommendations->count() > 0)
        <div class="products-grid">
            @foreach($recommendations as $product)
            <div class="product-card" style="border: 2px solid #fef3c7; transition: transform 0.3s;">
                <div class="product-image">
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    @endif
                    
                    <div style="position: absolute; top: 10px; right: 10px; background: rgba(255, 255, 255, 0.9); padding: 4px 8px; border-radius: 8px; display: flex; align-items: center; gap: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i data-feather="star" style="width: 14px; height: 14px; fill: #fbbf24; color: #fbbf24;"></i>
                        <span style="font-weight: bold; font-size: 13px;">{{ number_format($product->avg_rating ?? 0, 1) }}</span>
                    </div>
                </div>
                
                <div class="product-info">
                    <h4 class="product-title">{{ Str::limit($product->nama_produk, 40) }}</h4>
                    <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 10px;">
                        <i data-feather="award" style="width: 12px; height: 12px; vertical-align: middle;"></i> 
                        Terjual: <strong>{{ $product->total_terjual ?? 0 }}</strong> pcs
                    </div>
                    
                    <div class="product-actions">
                        @if($product->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form" style="flex: 1;">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-to-cart" style="background: #e67e22; width: 100%; justify-content: center;">
                                    <i data-feather="shopping-cart"></i>
                                </button>
                            </form>

                            <a href="{{ route('customer.products.show', $product->id) }}" class="btn-view-detail" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 5px;">
                                <i data-feather="eye"></i> Detail
                            </a>
                        @else
                            <button class="btn-out-of-stock" disabled style="width: 100%;">
                                <i data-feather="x-circle"></i> Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    
    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <!-- Featured Products -->
    <section class="section featured-products">
        <div class="section-header">
            <h2>Produk Terbaru</h2>
            <a href="{{ route('customer.products.index') }}" class="btn-view-all">Lihat Semua →</a>
        </div>

        @if($featuredProducts->count() > 0)
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    @if($product->gambar)
                        @php
                            $imagePath = 'storage/' . $product->gambar;
                            $imageExists = file_exists(public_path($imagePath)) || 
                                         Storage::disk('public')->exists($product->gambar);
                        @endphp
                        @if($imageExists)
                            <img src="{{ asset($imagePath) }}" 
                                 alt="{{ $product->nama_produk }}" 
                                 loading="lazy">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" 
                                 alt="{{ $product->nama_produk }}" 
                                 loading="lazy">
                        @endif
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" 
                             alt="{{ $product->nama_produk }}" 
                             loading="lazy">
                    @endif
                    
                    @if($product->stok == 0)
                        <div class="out-of-stock">Habis</div>
                    @endif
                </div>
                
                <div class="product-info">
                    <h4 class="product-title">{{ Str::limit($product->nama_produk, 40) }}</h4>
                    <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    
                    <div class="product-stock">
                        <i data-feather="package"></i>
                        Stok: {{ $product->stok }}
                    </div>
                    
                    <div class="product-actions">
                        @if($product->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-to-cart">
                                    <i data-feather="shopping-cart"></i> Tambah ke Keranjang
                                </button>
                            </form>
                            <a href="{{ route('customer.products.show', $product->id) }}" class="btn-view-detail">
                                <i data-feather="eye"></i> Detail
                            </a>
                        @else
                            <button class="btn-out-of-stock" disabled>
                                <i data-feather="x-circle"></i> Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i data-feather="package"></i>
            <p>Belum ada produk tersedia</p>
            <a href="{{ route('customer.products.index') }}" class="btn btn-primary mt-2">
                Lihat Semua Produk
            </a>
        </div>
        @endif
    </section>

    <!-- Recent Transactions -->
    @if(isset($recentTransactions) && $recentTransactions->count() > 0)
    <section class="section recent-transactions">
        <div class="section-header">
            <h2>Transaksi Terbaru</h2>
            <a href="#" class="btn-view-all">Lihat Semua →</a>
        </div>
        
        <div class="transactions-table-container">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr>
                        <td>#{{ $transaction->order_id }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($transaction->status) }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customer.checkout.invoice', $transaction->order_id) }}" class="btn-invoice">
                                <i data-feather="file-text"></i> Invoice
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="{{ route('customer.products.index') }}" class="action-card">
            <div class="action-icon">
                <i data-feather="shopping-bag"></i>
            </div>
            <div class="action-content">
                <h4>Belanja Produk</h4>
                <p>Lihat semua produk kami</p>
            </div>
        </a>

        <a href="{{ route('cart.index') }}" class="action-card">
            <div class="action-icon">
                <i data-feather="shopping-cart"></i>
            </div>
            <div class="action-content">
                <h4>Keranjang Saya</h4>
                <p>Kelola keranjang belanja</p>
            </div>
        </a>

        <a href="#" class="action-card">
            <div class="action-icon">
                <i data-feather="credit-card"></i>
            </div>
            <div class="action-content">
                <h4>Riwayat Transaksi</h4>
                <p>Lihat semua transaksi</p>
            </div>
        </a>

        <a href="#" class="action-card">
            <div class="action-icon">
                <i data-feather="user"></i>
            </div>
            <div class="action-content">
                <h4>Profil Saya</h4>
                <p>Kelola akun Anda</p>
            </div>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Feather icons
feather.replace();

// Pastikan semua image memiliki fallback
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.onerror = function() {
            this.src = '{{ asset("images/default-product.jpg") }}';
        };
    });
});
</script>
@endsection
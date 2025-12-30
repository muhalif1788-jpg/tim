{{-- resources/views/customer/dashboard.blade.php --}}
@extends('layouts.customer')

@section('title', 'Dashboard - Abon Sapi')

@section('content')
<div class="dashboard">
    <!-- Welcome Section (tetap sama) -->
    <div class="welcome-card">
        <div class="welcome-content">
            <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="welcome-text">Selamat berbelanja di Abon Sapi - Cita Rasa Tak Terlupakan</p>
        </div>
        <div class="welcome-date">
            <p>{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <!-- Quick Stats - UPDATE -->
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

    <!-- Featured Products (tetap sama) -->
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
                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $product->nama_produk }}" loading="lazy">
                    @endif
                    @if($product->stok == 0)
                        <div class="out-of-stock">Habis</div>
                    @endif
                </div>
                <div class="product-info">
                    <h4 class="product-title">{{ Str::limit($product->nama_produk, 40) }}</h4>
                    <p class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    
                    <div class="product-actions">
                        @if($product->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-add-to-cart">
                                    <i data-feather="
                                    hopping-cart"></i> Tambah ke Keranjang
                                </button>
                            </form>
                            <a href="{{ route('customer.products.show', $product->id) }}" class="btn-view-detail">
                                Detail
                            </a>
                        @else
                            <button class="btn-out-of-stock" disabled>Stok Habis</button>
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
        </div>
        @endif
    </section>

    <!-- Recent Transactions (ganti Orders dengan Transactions) -->
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

    <!-- Quick Actions (tambah link ke transaksi) -->
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

@section('styles')
<style>
/* Tambah CSS untuk transaction icon */
.transaction-icon {
    background: #e3f2fd;
    color: #1565c0;
}

/* CSS lainnya tetap sama seperti sebelumnya */
</style>
@endsection
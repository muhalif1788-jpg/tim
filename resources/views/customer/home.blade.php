@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <!-- Logo -->
                <div class="mb-4">
                    <h1 class="text-white display-4 fw-bold">LOGO</h1>
                </div>
                
                <!-- Main Heading -->
                <h2 class="text-white display-5 fw-bold mb-3">
                    Predict &<br>
                    <span class="text-warning">Selenpleapnya</span>
                </h2>
                
                <!-- Product Title -->
                <h3 class="text-white h1 mb-4">ABON SAPI</h3>
                
                <!-- Tagline -->
                <div class="taglines mb-4">
                    <p class="text-white h5 mb-2">ADAN AKAM</p>
                    <p class="text-white mb-1">CITA RASA YANG TIDAK</p>
                    <p class="text-white mb-1">DENAI DENGAN SERRUN HATI</p>
                    <p class="text-white mb-1">HIGIENS DAN MURAH</p>
                    <p class="text-white mb-3">KEDAI DENGAN SEJUTA</p>
                    <p class="text-warning h4 fw-bold">CITA RASAII</p>
                </div>
                
                <!-- CTA Buttons -->
                <div class="cta-buttons mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg me-3">
                        <i class="fas fa-shopping-bag me-2"></i>Belanja Sekarang
                    </a>
                    <a href="#produk" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-eye me-2"></i>Lihat Produk
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <!-- Product Image Placeholder -->
                <div class="text-center">
                    <div style="background: rgba(255,255,255,0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px);">
                        <i class="fas fa-image fa-8x text-white opacity-50"></i>
                        <p class="text-white mt-3">Gambar Abon Sapi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if(isset($featuredProducts) && $featuredProducts->count() > 0)
<section id="produk" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-dark">Produk Unggulan Kami</h2>
                <p class="text-muted">Rasakan kelezatan abon sapi dengan kualitas terbaik</p>
            </div>
        </div>
        
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100 shadow-sm border-0">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 250px;">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" 
                                 alt="{{ $product->nama }}" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover;">
                        @else
                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-warning text-dark">Featured</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark">{{ $product->nama }}</h5>
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($product->deskripsi, 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </span>
                                <span class="text-muted">
                                    Stok: {{ $product->stok }}
                                </span>
                            </div>
                            
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                Lihat Semua Produk <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@else
<!-- Placeholder jika belum ada produk -->
<section id="produk" class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-dark">Produk Unggulan Kami</h2>
                <p class="text-muted">Rasakan kelezatan abon sapi dengan kualitas terbaik</p>
            </div>
        </div>
        
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada produk</h4>
            <p class="text-muted">Produk akan segera tersedia</p>
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                </div>
                <h4>Higenis & Terjamin</h4>
                <p class="text-muted">Diproses dengan standar kebersihan tinggi</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-tags fa-3x text-primary"></i>
                </div>
                <h4>Harga Terjangkau</h4>
                <p class="text-muted">Kualitas premium dengan harga bersaing</p>
            </div>
            
            <div class="col-md-4 text-center mb-4">
                <div class="feature-icon mb-3">
                    <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                </div>
                <h4>Pengiriman Cepat</h4>
                <p class="text-muted">Pesanan diproses dan dikirim dengan cepat</p>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
    background-size: cover;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.feature-icon {
    transition: transform 0.3s ease;
}

.feature-icon:hover {
    transform: scale(1.1);
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-lg {
    padding: 12px 30px;
}
</style>
@endsection
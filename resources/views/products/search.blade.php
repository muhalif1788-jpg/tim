@extends('layouts.app')

@section('title', 'Hasil Pencarian - Kedai Pesisir')

@section('content')
<div class="container">
    <div class="search-results-header">
        <h1>Hasil Pencarian</h1>
        <p>Menampilkan hasil untuk: "<strong>{{ $query }}</strong>"</p>
        <p class="result-count">{{ $produk->total() }} produk ditemukan</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form action="{{ route('customer.products.search') }}" method="GET">
            <input type="text" name="q" placeholder="Cari produk..." value="{{ $query }}">
            <button type="submit">
                <i data-feather="search"></i>
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    @if($produk->isEmpty())
        <div class="no-results">
            <p>Tidak ada produk yang sesuai dengan pencarian Anda.</p>
            <a href="{{ route('customer.products.index') }}" class="btn-all-products">Lihat Semua Produk</a>
        </div>
    @else
        <div class="products-grid">
            @foreach($produk as $item)
            <div class="product-card">
                <div class="product-image">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_produk }}">
                    @else
                        <img src="{{ asset('images/default-product.jpg') }}" alt="{{ $item->nama_produk }}">
                    @endif
                </div>
                <div class="product-info">
                    <h3>{{ $item->nama_produk }}</h3>
                    <p class="price">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <p class="stock">Stok: {{ $item->stok }}</p>
                    
                    <div class="product-actions">
                        @if($item->stok > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-cart">
                                    <i data-feather="shopping-cart"></i>
                                    Tambah Keranjang
                                </button>
                            </form>
                        @else
                            <button class="btn-disabled" disabled>Stok Habis</button>
                        @endif
                        
                        <a href="{{ route('customer.products.show', $item->id) }}" class="btn-detail">Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $produk->appends(['q' => $query])->links() }}
        </div>
    @endif
</div>
@endsection
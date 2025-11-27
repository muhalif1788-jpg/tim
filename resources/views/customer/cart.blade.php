@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Keranjang Belanja</h1>
    
    @if(empty($cartItems))
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h3>Keranjang kamu kosong</h3>
            <p class="text-muted">Yuk tambahkan produk ke keranjang!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                @foreach($cartItems as $id => $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="img-fluid rounded">
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title">{{ $item['name'] }}</h5>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <div class="input-group">
                                        <input type="number" name="quantity" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" class="form-control">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <h5>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</h5>
                            </div>
                            <div class="col-md-1">
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="text-end">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Kosongkan Keranjang
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Belanja</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                            Lanjut ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
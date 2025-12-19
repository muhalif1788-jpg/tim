{{-- resources/views/customer/checkout/index.blade.php --}}
@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Data Penerima</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.checkout.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_penerima" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" 
                                    value="{{ Auth::user()->name }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                    value="{{ Auth::user()->telepon ?? '' }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ Auth::user()->alamat ?? '' }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Lanjut ke Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Ringkasan Pesanan</h4>
                </div>
                <div class="card-body">
                    @foreach($carts as $cart)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <h6 class="mb-0">{{ $cart->produk->nama }}</h6>
                            <small>{{ $cart->quantity }} x Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}</small>
                        </div>
                        <div>Rp {{ number_format($cart->produk->harga * $cart->quantity, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pengiriman</span>
                        <span>Rp {{ number_format($biaya_pengiriman, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Admin</span>
                        <span>Rp {{ number_format($biaya_admin, 0, ',', '.') }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <h5>Rp {{ number_format($total, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">Kembali ke Keranjang</a>
            </div>
        </div>
    </div>
</div>
@endsection
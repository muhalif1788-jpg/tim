@extends('layouts.customer')

@section('content')
<div class="container error-container">
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h2>Pembayaran Gagal</h2>
        
        <div class="error-message">
            @if(session('error'))
                <p>{{ session('error') }}</p>
            @else
                <p>Terjadi kesalahan saat memproses pembayaran.</p>
            @endif
            
            <p class="success-note">
                <i class="fas fa-check-circle"></i> 
                Produk telah dikembalikan ke keranjang belanja Anda.
            </p>
        </div>
        
        <div class="error-actions">
            <a href="{{ route('cart.index') }}" class="btn-primary">
                <i class="fas fa-shopping-cart"></i> Lihat Keranjang
            </a>
            <a href="{{ route('customer.checkout.index') }}" class="btn-secondary">
                <i class="fas fa-redo"></i> Coba Lagi
            </a>
            <a href="{{ route('home') }}" class="btn-link">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    .error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .error-card {
        width: 100%;
        max-width: 500px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px 30px;
        text-align: center;
    }
    
    .error-icon {
        font-size: 64px;
        color: #dc3545;
        margin-bottom: 20px;
    }
    
    .error-card h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    
    .error-message {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    
    .success-note {
        color: #28a745;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border-left: 4px solid #28a745;
        margin-top: 15px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .error-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-primary {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-primary:hover {
        background-color: #45a049;
    }
    
    .btn-secondary {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-secondary:hover {
        background-color: #e9ecef;
    }
    
    .btn-link {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: color 0.3s ease;
    }
    
    .btn-link:hover {
        color: #4CAF50;
    }
</style>
@endsection
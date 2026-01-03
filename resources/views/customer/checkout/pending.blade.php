@extends('layouts.customer')

@section('content')
<div class="container pending-container">
    <div class="pending-card">
        <div class="pending-icon">
            <i class="fas fa-clock"></i>
        </div>
        
        <h2>Menunggu Pembayaran</h2>
        
        <div class="pending-message">
            @if(session('info'))
                <p>{{ session('info') }}</p>
            @else
                <p>Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran melalui metode yang telah Anda pilih.</p>
            @endif
            
            <div class="instructions">
                <h4>Instruksi:</h4>
                <ol>
                    <li>Selesaikan pembayaran melalui metode yang telah dipilih</li>
                    <li>Pembayaran akan diverifikasi secara otomatis</li>
                    <li>Anda akan menerima notifikasi ketika pembayaran berhasil</li>
                    <li>Jika pembayaran dibatalkan, produk akan dikembalikan ke keranjang</li>
                </ol>
            </div>
        </div>
        
        <div class="pending-actions">
            <a href="{{ route('home') }}" class="btn-primary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('customer.orders') }}" class="btn-secondary">
                <i class="fas fa-list"></i> Lihat Pesanan
            </a>
        </div>
        
        <div class="note">
            <i class="fas fa-info-circle"></i>
            Status pembayaran Anda akan diperbarui dalam beberapa menit.
        </div>
    </div>
</div>

<style>
    .pending-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .pending-card {
        width: 100%;
        max-width: 600px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px 30px;
        text-align: center;
    }
    
    .pending-icon {
        font-size: 64px;
        color: #ffc107;
        margin-bottom: 20px;
    }
    
    .pending-card h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 600;
    }
    
    .pending-message {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
        text-align: left;
    }
    
    .instructions {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        border-left: 4px solid #ffc107;
    }
    
    .instructions h4 {
        color: #333;
        margin-bottom: 10px;
        font-size: 18px;
    }
    
    .instructions ol {
        padding-left: 20px;
        color: #666;
    }
    
    .instructions li {
        margin-bottom: 8px;
    }
    
    .pending-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #4CAF50;
        color: white;
    }
    
    .btn-primary:hover {
        background-color: #45a049;
    }
    
    .btn-secondary {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
    }
    
    .btn-secondary:hover {
        background-color: #e9ecef;
    }
    
    .note {
        color: #666;
        font-size: 14px;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 6px;
        border: 1px dashed #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
</style>
@endsection
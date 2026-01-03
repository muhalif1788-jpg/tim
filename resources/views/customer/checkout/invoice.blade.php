@extends('layouts.customer')

@section('content')
<div class="container invoice-container">
    <div class="invoice-card">
        <div class="invoice-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Pembayaran Berhasil!</h2>
            <p class="invoice-subtitle">Terima kasih telah berbelanja di toko kami.</p>
        </div>
        
        <div class="invoice-details">
            <div class="detail-section">
                <h4>Informasi Pesanan</h4>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value">{{ $transaksi->order_id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value status-success">{{ ucfirst($transaksi->status) }}</span>
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Informasi Pembayaran</h4>
                <div class="detail-row">
                    <span class="detail-label">Total Harga:</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Biaya Pengiriman:</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Biaya Admin:</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row total-row">
                    <span class="detail-label">Total Pembayaran:</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
            
            @if($transaksi->details->count() > 0)
            <div class="detail-section">
                <h4>Detail Produk</h4>
                <div class="product-list">
                    @foreach($transaksi->details as $detail)
                    <div class="product-item">
                        <div class="product-info">
                            <h5>{{ $detail->produk->nama ?? 'Produk' }}</h5>
                            <p>{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</p>
                        </div>
                        <div class="product-subtotal">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <div class="invoice-actions">
            <button onclick="window.print()" class="btn-primary">
                <i class="fas fa-print"></i> Cetak Invoice
            </button>
            <a href="{{ route('customer.dashboard') }}" class="btn-secondary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    .invoice-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .invoice-card {
        width: 100%;
        max-width: 700px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 40px 30px;
    }
    
    .invoice-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .success-icon {
        font-size: 64px;
        color: #4CAF50;
        margin-bottom: 15px;
    }
    
    .invoice-header h2 {
        color: #333;
        font-size: 28px;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .invoice-subtitle {
        color: #666;
        font-size: 16px;
    }
    
    .detail-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .detail-section h4 {
        color: #333;
        font-size: 18px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 8px 0;
    }
    
    .detail-label {
        color: #666;
        font-size: 15px;
    }
    
    .detail-value {
        color: #333;
        font-size: 15px;
        font-weight: 500;
    }
    
    .total-row {
        font-size: 16px;
        font-weight: 600;
        color: #4CAF50;
        padding-top: 10px;
        border-top: 2px solid #eee;
        margin-top: 10px;
    }
    
    .status-success {
        color: #4CAF50;
        font-weight: 600;
    }
    
    .product-list {
        margin-top: 10px;
    }
    
    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .product-item:last-child {
        border-bottom: none;
    }
    
    .product-info h5 {
        margin: 0 0 5px 0;
        color: #333;
        font-size: 16px;
    }
    
    .product-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    
    .product-subtotal {
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }
    
    .invoice-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-primary, .btn-secondary {
        padding: 15px 20px;
        border-radius: 8px;
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
        border: none;
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
    
    .btn-link {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        text-align: center;
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
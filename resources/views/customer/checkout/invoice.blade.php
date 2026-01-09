@extends('layouts.customer')

@section('title', 'Invoice #' . $transaksi->order_id)

@section('content')
<div class="container invoice-container">
    <div class="invoice-card">
        <div class="invoice-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Pembayaran Berhasil!</h2>
            <p class="invoice-subtitle">Pesanan Anda telah kami terima dan sedang diproses.</p>
        </div>
        
        <div class="invoice-details">
            <div class="row mb-4">
                <div class="col-md-6 border-end">
                    <div class="detail-section">
                        <h4>Informasi Pesanan</h4>
                        <div class="detail-row">
                            <span class="detail-label">Order ID:</span>
                            <span class="detail-value fw-bold text-primary">#{{ $transaksi->order_id }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Tanggal:</span>
                            <span class="detail-value">{{ $transaksi->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Metode Bayar:</span>
                            <span class="detail-value">{{ strtoupper(str_replace('_', ' ', $transaksi->payment_type ?? 'Snap')) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value status-badge {{ $transaksi->status }}">
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 ps-md-4">
                    <div class="detail-section">
                        <h4>Tujuan Pengiriman</h4>
                        <div class="delivery-info">
                            <p class="mb-1 fw-bold">{{ $transaksi->nama_penerima }}</p>
                            <p class="mb-1 text-muted small">{{ $transaksi->no_telepon }}</p>
                            <p class="mb-0 text-muted small" style="line-height: 1.4;">
                                {{ $transaksi->alamat }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4>Detail Produk</h4>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 small text-muted">Produk</th>
                                <th class="py-2 small text-muted text-center">Qty</th>
                                <th class="py-2 small text-muted text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->details as $detail)
                            <tr class="border-bottom">
                                <td class="py-3">
                                    <h6 class="mb-0 fw-bold">{{ $detail->produk->nama ?? 'Produk' }}</h6>
                                    <small class="text-muted">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="detail-section bg-light p-3 rounded-3">
                <div class="detail-row">
                    <span class="detail-label">Subtotal Produk</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Biaya Pengiriman</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Biaya Admin</span>
                    <span class="detail-value">Rp {{ number_format($transaksi->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <hr>
                <div class="detail-row total-row">
                    <span class="h5 mb-0 fw-bold text-dark">Total Pembayaran</span>
                    <span class="h5 mb-0 fw-bold text-success">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="invoice-actions no-print">
            <button onclick="window.print()" class="btn btn-success py-3 rounded-3 fw-bold">
                <i class="fas fa-print me-2"></i> Cetak Invoice
            </button>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-dark py-3 rounded-3 fw-bold">
                <i class="fas fa-shopping-bag me-2"></i> Belanja Lagi
            </a>
        </div>
    </div>
</div>

<style>
    /* Layout styling */
    .invoice-container { padding: 40px 20px; background-color: #f8f9fa; min-height: 100vh; }
    .invoice-card { width: 100%; max-width: 800px; margin: auto; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); padding: 40px; }
    
    /* Header */
    .invoice-header { text-align: center; margin-bottom: 40px; }
    .success-icon { font-size: 70px; color: #28a745; margin-bottom: 15px; }
    .invoice-header h2 { font-weight: 800; color: #333; }
    
    /* Content */
    .detail-section h4 { font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 20px; }
    .detail-row { display: flex; justify-content: space-between; margin-bottom: 12px; }
    .detail-label { color: #666; font-size: 15px; }
    .detail-value { font-weight: 600; color: #333; }
    
    /* Status Badge */
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .status-badge.settlement, .status-badge.success { background: #e8f5e9; color: #2e7d32; }
    .status-badge.pending { background: #fff3e0; color: #ef6c00; }
    .status-badge.expire, .status-badge.cancel { background: #ffebee; color: #c62828; }

    /* Actions */
    .invoice-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 40px; }

    /* PRINT CSS */
    @media print {
        nav, footer, .invoice-actions, .no-print { display: none !important; }
        .invoice-container { background: white !important; padding: 0 !important; }
        .invoice-card { box-shadow: none !important; border: none !important; padding: 0 !important; max-width: 100% !important; }
        body { background: white !important; }
        .total-row .text-success { color: black !important; }
    }
</style>
@endsection
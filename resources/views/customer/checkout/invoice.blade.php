{{-- resources/views/customer/checkout/invoice.blade.php --}}
@extends('layouts.customer')

@section('title', 'Invoice #' . $transaksi->order_id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Invoice Card -->
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Invoice</h4>
                            <p class="text-muted mb-0">#{{ $transaksi->order_id }}</p>
                        </div>
                        <div class="text-end">
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'success' => 'success',
                                    'failed' => 'danger',
                                    'expired' => 'secondary',
                                    'canceled' => 'dark',
                                    'refunded' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$transaksi->status] ?? 'secondary' }} fs-6">
                                {{ strtoupper($transaksi->status) }}
                            </span>
                            <p class="mb-0 mt-1 small text-muted">
                                Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Company & Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Dari:</h6>
                            <address class="mb-0">
                                <strong>{{ config('app.name', 'Toko Online') }}</strong><br>
                                Alamat Toko<br>
                                Kota, Provinsi<br>
                                Telp: 021-12345678<br>
                                Email: info@toko.com
                            </address>
                        </div>
                        
                        <div class="col-md-6 text-end">
                            <h6>Untuk:</h6>
                            <address class="mb-0">
                                <strong>{{ $transaksi->nama_penerima }}</strong><br>
                                {{ $transaksi->alamat_pengiriman }}<br>
                                Telp: {{ $transaksi->telepon_penerima }}<br>
                                Email: {{ $transaksi->user->email }}
                            </address>
                        </div>
                    </div>
                    
                    <!-- Order Details -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Produk</th>
                                    <th width="15%" class="text-center">Harga</th>
                                    <th width="10%" class="text-center">Qty</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $detail->produk->nama }}</strong><br>
                                            <small class="text-muted">SKU: {{ $detail->produk->kode ?? '-' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $detail->jumlah }}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Payment & Shipping Info -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6>Informasi Pembayaran:</h6>
                                    @if($transaksi->payment_type)
                                    <p class="mb-1">
                                        <strong>Metode:</strong> 
                                        {{ ucfirst(str_replace('_', ' ', $transaksi->payment_type)) }}
                                    </p>
                                    @endif
                                    @if($transaksi->bank)
                                    <p class="mb-1">
                                        <strong>Bank:</strong> {{ strtoupper($transaksi->bank) }}
                                    </p>
                                    @endif
                                    @if($transaksi->va_number)
                                    <p class="mb-1">
                                        <strong>Virtual Account:</strong> {{ $transaksi->va_number }}
                                    </p>
                                    @endif
                                    @if($transaksi->paid_at)
                                    <p class="mb-0">
                                        <strong>Tanggal Bayar:</strong> 
                                        {{ $transaksi->paid_at->format('d M Y H:i') }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6>Pengiriman:</h6>
                                    <p class="mb-1">
                                        <strong>Kurir:</strong> Standard Delivery
                                    </p>
                                    <p class="mb-1">
                                        <strong>Estimasi:</strong> 2-3 hari kerja
                                    </p>
                                    <p class="mb-0">
                                        <strong>Catatan:</strong> 
                                        {{ $transaksi->catatan ?? 'Tidak ada catatan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-5">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="text-end"><strong>Subtotal</strong></td>
                                        <td class="text-end">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Biaya Pengiriman</strong></td>
                                        <td class="text-end">Rp {{ number_format($transaksi->biaya_pengiriman, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end"><strong>Biaya Admin</strong></td>
                                        <td class="text-end">Rp {{ number_format($transaksi->biaya_admin, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td class="text-end"><strong>TOTAL</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <div class="alert alert-light mt-4">
                        <h6>Catatan:</h6>
                        <ul class="mb-0">
                            <li>Invoice ini sah dan dikeluarkan oleh sistem</li>
                            <li>Pembayaran yang sudah dilakukan tidak dapat dikembalikan</li>
                            <li>Untuk pertanyaan, hubungi customer service kami</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                        
                        <div>
                            <button onclick="window.print()" class="btn btn-outline-primary me-2">
                                <i class="fas fa-print me-1"></i> Cetak Invoice
                            </button>
                            
                            @if($transaksi->status == 'pending')
                            <a href="{{ route('checkout.payment.retry', $transaksi->order_id) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-footer, .alert {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    body {
        padding: 0 !important;
        background: white !important;
    }
}
.card {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    border: none;
}
.table th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>
@endsection
{{-- resources/views/customer/checkout/finish.blade.php --}}
@extends('layouts.customer')

@section('title', 'Pembayaran Selesai')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    @if($transaksi->status == 'success')
                    <!-- Success State -->
                    <div class="mb-4">
                        <div class="circle-success mb-3">
                            <i class="fas fa-check fa-3x text-white"></i>
                        </div>
                        <h2 class="text-success">Pembayaran Berhasil!</h2>
                        <p class="text-muted">Terima kasih telah berbelanja di toko kami</p>
                    </div>
                    
                    <div class="alert alert-success mb-4">
                        <h5>Order ID: {{ $transaksi->order_id }}</h5>
                        <p class="mb-0">Total: <strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Detail Pesanan:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            <span class="badge bg-success">SUCCESS</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metode Pembayaran</strong></td>
                                        <td>{{ ucfirst($transaksi->payment_type) ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pembayaran</strong></td>
                                        <td>{{ $transaksi->paid_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama Penerima</strong></td>
                                        <td>{{ $transaksi->nama_penerima }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat Pengiriman</strong></td>
                                        <td>{{ $transaksi->alamat_pengiriman }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    @elseif($transaksi->status == 'pending')
                    <!-- Pending State -->
                    <div class="mb-4">
                        <div class="circle-warning mb-3">
                            <i class="fas fa-clock fa-3x text-white"></i>
                        </div>
                        <h2 class="text-warning">Menunggu Pembayaran</h2>
                        <p class="text-muted">Silakan selesaikan pembayaran Anda</p>
                    </div>
                    
                    <div class="alert alert-warning mb-4">
                        <h5>Order ID: {{ $transaksi->order_id }}</h5>
                        <p class="mb-0">Total: <strong>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></p>
                        <p class="mb-0">Batas Waktu: <strong>{{ $transaksi->expired_at->format('d M Y H:i') }}</strong></p>
                    </div>
                    
                    @else
                    <!-- Failed/Expired State -->
                    <div class="mb-4">
                        <div class="circle-danger mb-3">
                            <i class="fas fa-times fa-3x text-white"></i>
                        </div>
                        <h2 class="text-danger">Pembayaran {{ ucfirst($transaksi->status) }}</h2>
                        <p class="text-muted">Transaksi Anda tidak dapat diproses</p>
                    </div>
                    
                    <div class="alert alert-danger mb-4">
                        <h5>Order ID: {{ $transaksi->order_id }}</h5>
                        <p class="mb-0">Status: <strong>{{ strtoupper($transaksi->status) }}</strong></p>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('app') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                        
                        <a href="{{ route('customer.checkout.invoice', $transaksi->order_id) }}" class="btn btn-primary">
                            <i class="fas fa-file-invoice me-1"></i> Lihat Invoice
                        </a>
                        
                        @if($transaksi->status == 'pending')
                        <a href="{{ route('customer.checkout.payment', $transaksi->order_id) }}" class="btn btn-success">
                            <i class="fas fa-redo me-1"></i> Coba Bayar Lagi
                        </a>
                        @endif
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="text-start">
                        <h6>Panduan Selanjutnya:</h6>
                        <ul>
                            <li>Invoice telah dikirim ke email Anda</li>
                            <li>Pesanan akan diproses dalam 1x24 jam</li>
                            <li>Untuk pertanyaan, hubungi customer service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.circle-success, .circle-warning, .circle-danger {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.circle-success { background-color: #28a745; }
.circle-warning { background-color: #ffc107; }
.circle-danger { background-color: #dc3545; }
.card {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: none;
}
</style>
@endsection
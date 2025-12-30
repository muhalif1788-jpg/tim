@extends('layouts.customer')

@section('title', 'Detail Pesanan #' . $transaction->order_id)

@section('styles')
<style>
    .order-detail-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    .order-timeline {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
        position: relative;
    }
    
    .timeline-step {
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .timeline-dot {
        width: 20px;
        height: 20px;
        background: #e0e6ed;
        border-radius: 50%;
        margin: 0 auto 10px;
        position: relative;
        z-index: 2;
    }
    
    .timeline-step.active .timeline-dot {
        background: #294066;
        border: 3px solid #FFD700;
    }
    
    .timeline-step.completed .timeline-dot {
        background: #28a745;
    }
    
    .order-summary {
        background: #f8fafc;
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="order-detail-page">
    <div class="page-header">
        <a href="{{ route('orders.index') }}" class="back-link">
            <i data-feather="arrow-left"></i> Kembali ke Pesanan
        </a>
        <h1>Detail Pesanan #{{ $transaction->order_id }}</h1>
        <div class="order-status-badge status-{{ $transaction->status }}">
            Status: {{ strtoupper($transaction->status) }}
        </div>
    </div>

    <!-- Timeline Status -->
    <div class="order-timeline">
        @php
            $steps = [
                'pending' => 'Menunggu Pembayaran',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai'
            ];
            
            $currentStep = array_search($transaction->status, array_keys($steps));
        @endphp
        
        @foreach($steps as $key => $label)
        <div class="timeline-step 
            {{ $key === $transaction->status ? 'active' : '' }}
            {{ array_search($key, array_keys($steps)) < $currentStep ? 'completed' : '' }}">
            <div class="timeline-dot"></div>
            <div class="timeline-label">{{ $label }}</div>
            @if($key === 'pending')
                <div class="timeline-date">
                    {{ $transaction->created_at->format('d M Y, H:i') }}
                </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Order Items -->
    <div class="order-details-section">
        <h2><i data-feather="package"></i> Detail Produk</h2>
        
        <div class="order-items-table">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $detail)
                    <tr>
                        <td>
                            <div class="product-info">
                                <span class="product-name">
                                    {{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}
                                </span>
                            </div>
                        </td>
                        <td>Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <h2><i data-feather="file-text"></i> Ringkasan Pesanan</h2>
        
        <div class="summary-grid">
            <div class="summary-info">
                <h3>Informasi Pengiriman</h3>
                <p><strong>Nama Penerima:</strong> {{ $transaction->nama_penerima }}</p>
                <p><strong>Telepon:</strong> {{ $transaction->telepon_penerima }}</p>
                <p><strong>Alamat:</strong> {{ $transaction->alamat_pengiriman }}</p>
                @if($transaction->catatan)
                    <p><strong>Catatan:</strong> {{ $transaction->catatan }}</p>
                @endif
            </div>
            
            <div class="summary-total">
                <h3>Rincian Pembayaran</h3>
                <div class="total-item">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="total-item">
                    <span>Biaya Pengiriman</span>
                    <span>Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                </div>
                <div class="total-item">
                    <span>Biaya Admin</span>
                    <span>Rp {{ number_format($transaction->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <div class="total-item grand-total">
                    <span><strong>Total Pembayaran</strong></span>
                    <span><strong>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Actions -->
    <div class="order-actions">
        @if($transaction->status === 'pending')
            <a href="{{ route('customer.checkout.payment') }}" class="btn-primary">
                <i data-feather="credit-card"></i> Bayar Sekarang
            </a>
        @endif
        
        @if($transaction->status === 'shipped')
            <a href="{{ route('orders.track', $transaction->id) }}" class="btn-secondary">
                <i data-feather="truck"></i> Lacak Pengiriman
            </a>
        @endif
        
        <a href="{{ route('orders.invoice', $transaction->id) }}" 
           target="_blank" class="btn-outline">
            <i data-feather="file-text"></i> Lihat Invoice
        </a>
        
        @if(in_array($transaction->status, ['pending', 'processing']))
            <button onclick="confirmCancel('{{ $transaction->id }}')" class="btn-danger">
                <i data-feather="x-circle"></i> Batalkan Pesanan
            </button>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    feather.replace();
    
    function confirmCancel(orderId) {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/customer/orders/${orderId}/cancel`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message,
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>
@endsection
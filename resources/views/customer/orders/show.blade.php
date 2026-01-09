@extends('layouts.customer')

@section('title', 'Detail Pesanan #' . $transaction->order_id)

@section('styles')
<style>
    .order-detail-page { max-width: 1000px; margin: 0 auto; padding: 30px 20px; }
    
    /* Timeline Modern */
    .order-timeline {
        display: flex; justify-content: space-between; margin: 40px 0;
        position: relative; padding: 0 10px;
    }
    .order-timeline::before {
        content: ''; position: absolute; top: 10px; left: 0; right: 0;
        height: 2px; background: #e0e6ed; z-index: 0;
    }
    .timeline-step { text-align: center; position: relative; z-index: 1; flex: 1; }
    .timeline-dot {
        width: 20px; height: 20px; background: #fff; border: 2px solid #e0e6ed;
        border-radius: 50%; margin: 0 auto 10px; transition: all 0.3s;
    }
    .timeline-step.active .timeline-dot { background: #294066; border-color: #FFD700; transform: scale(1.2); }
    .timeline-step.completed .timeline-dot { background: #28a745; border-color: #28a745; }
    .timeline-label { font-size: 12px; font-weight: 600; color: #64748b; }

    /* Info Cards */
    .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
    .info-card { background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
    
    .order-items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .order-items-table th { text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; color: #475569; }
    .order-items-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
    
    .grand-total { background: #f1f5f9; padding: 15px; border-radius: 8px; margin-top: 10px; border: 1px solid #cbd5e1; }
    .btn-actions { display: flex; gap: 12px; margin-top: 30px; flex-wrap: wrap; }
    
    /* Status Badge */
    .badge { padding: 6px 16px; border-radius: 50px; font-size: 12px; font-weight: bold; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dcfce7; color: #166534; }
    .status-shipped { background: #dbeafe; color: #1e40af; }
    .status-completed, .status-selesai { background: #dcfce7; color: #155724; }
</style>
@endsection

@section('content')
<div class="order-detail-page">
    <div class="page-header">
        <a href="{{ route('orders.index') }}" class="back-link" style="text-decoration: none; color: #64748b;">
            <i data-feather="arrow-left" style="width: 16px;"></i> Kembali ke Pesanan
        </a>
        <h1 style="margin: 15px 0 5px;">Detail Pesanan #{{ $transaction->order_id }}</h1>
        <span class="badge status-{{ $transaction->status }}">
            {{ strtoupper($transaction->status) }}
        </span>
    </div>

    <div class="order-timeline">
        @php
            $steps = [
                'pending' => 'Menunggu Pembayaran',
                'processing' => 'Diproses',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai'
            ];
            $statusKeys = array_keys($steps);
            $currentStepIndex = array_search($transaction->status, $statusKeys);
        @endphp
        
        @foreach($steps as $key => $label)
            @php 
                $index = array_search($key, $statusKeys);
                $isCompleted = ($index < $currentStepIndex) || ($transaction->status === 'completed' || $transaction->status === 'selesai');
                $isActive = ($key === $transaction->status);
            @endphp
            <div class="timeline-step {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}">
                <div class="timeline-dot"></div>
                <div class="timeline-label">{{ $label }}</div>
            </div>
        @endforeach
    </div>

    <div class="info-card">
        <h3><i data-feather="package"></i> Detail Produk</h3>
        <table class="order-items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $detail)
                <tr>
                    <td><strong>{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</strong></td>
                    <td>Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $detail->jumlah }}</td>
                    <td style="text-align: right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="summary-grid">
        <div class="info-card">
            <h3><i data-feather="map-pin"></i> Informasi Pengiriman</h3>
            <div style="margin-top: 15px; line-height: 1.8;">
                <p><strong>Nama Penerima:</strong> {{ $transaction->nama_penerima }}</p>
                <p><strong>Telepon:</strong> {{ $transaction->telepon_penerima }}</p>
                <p><strong>Alamat:</strong> {{ $transaction->alamat_pengiriman }}</p>
                @if($transaction->catatan)
                    <p><strong>Catatan:</strong> {{ $transaction->catatan }}</p>
                @endif
            </div>
        </div>
        
        <div class="info-card">
            <h3><i data-feather="file-text"></i> Ringkasan Pembayaran</h3>
            <div style="margin-top: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Biaya Pengiriman</span>
                    <span>Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span>Biaya Admin</span>
                    <span>Rp {{ number_format($transaction->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <div class="grand-total" style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1em;">
                    <span>Total Pembayaran</span>
                    <span style="color: #294066;">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-actions">
        @if($transaction->status === 'pending')
            <a href="{{ route('customer.checkout.payment') }}" class="btn" style="background: #28a745; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                <i data-feather="credit-card"></i> Bayar Sekarang
            </a>
            
            <button onclick="confirmCancel('{{ $transaction->id }}')" class="btn" style="background: #dc3545; color: white; padding: 12px 24px; border-radius: 6px; border: none; cursor: pointer;">
                <i data-feather="x-circle"></i> Batalkan Pesanan
            </button>
        @endif

        @if($transaction->status === 'shipped')
            <a href="{{ route('orders.track', $transaction->id) }}" class="btn" style="background: #294066; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none;">
                <i data-feather="truck"></i> Lacak Pengiriman
            </a>
        @endif

        <a href="{{ route('orders.invoice', $transaction->id) }}" target="_blank" class="btn" style="background: #f1f5f9; color: #475569; padding: 12px 24px; border-radius: 6px; text-decoration: none; border: 1px solid #cbd5e1;">
            <i data-feather="download"></i> Download Invoice
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    feather.replace();
    
    function confirmCancel(orderId) {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Pesanan yang dibatalkan tidak dapat diproses lagi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                // Menggunakan form submit manual agar sinkron dengan redirect controller
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/customer/orders/${orderId}/cancel`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
@extends('layouts.customer')

@section('title', 'Pesanan Saya - Abon Sapi')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endsection

@section('content')
<div class="orders-page">
    <div class="page-header">
        <h1><i data-feather="shopping-bag"></i> Pesanan Saya</h1>
        <p>Riwayat dan status pesanan Anda</p>
    </div>

    @if($transactions && $transactions->count() > 0)
        <!-- Filter Tab -->
        <div class="orders-tabs">
            <button class="tab-btn active" data-filter="all">Semua ({{ $transactions->total() }})</button>
            <button class="tab-btn" data-filter="pending">Menunggu Pembayaran</button>
            <button class="tab-btn" data-filter="processing">Diproses</button>
            <button class="tab-btn" data-filter="shipped">Dikirim</button>
            <button class="tab-btn" data-filter="completed">Selesai</button>
        </div>

        <!-- Orders List -->
        <div class="orders-list">
            @foreach($transactions as $transaction)
            <div class="order-card" data-status="{{ $transaction->status }}">
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-id">
                            <h3>Order #{{ $transaction->order_id }}</h3>
                            <span class="order-date">
                                <i data-feather="calendar"></i>
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        
                        <div class="order-status-badge status-{{ $transaction->status }}">
                            @switch($transaction->status)
                                @case('pending')
                                    <i data-feather="clock"></i> Menunggu Pembayaran
                                    @break
                                @case('processing')
                                    <i data-feather="package"></i> Diproses
                                    @break
                                @case('shipped')
                                    <i data-feather="truck"></i> Dikirim
                                    @break
                                @case('completed')
                                    <i data-feather="check-circle"></i> Selesai
                                    @break
                                @case('expired')
                                    <i data-feather="x-circle"></i> Kadaluarsa
                                    @break
                                @case('cancelled')
                                    <i data-feather="x-circle"></i> Dibatalkan
                                    @break
                                @default
                                    <i data-feather="help-circle"></i> {{ $transaction->status }}
                            @endswitch
                        </div>
                    </div>
                    
                    <div class="order-total">
                        <span class="total-label">Total</span>
                        <span class="total-amount">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items">
                    @php
                        $items = $transaction->detailTransaksi ?? [];
                    @endphp
                    
                    @if($items && count($items) > 0)
                        @foreach($items as $item)
                        <div class="order-item">
                            <div class="item-image">
                                @if($item->produk && $item->produk->images && $item->produk->images->first())
                                    <img src="{{ asset('storage/' . $item->produk->images->first()->image_path) }}" 
                                         alt="{{ $item->produk->nama }}">
                                @else
                                    <div class="no-image">
                                        <i data-feather="image"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="item-details">
                                <h4>{{ $item->produk->nama ?? 'Produk Tidak Ditemukan' }}</h4>
                                <div class="item-meta">
                                    <span class="item-price">
                                        Rp {{ number_format($item->harga_saat_ini, 0, ',', '.') }}
                                    </span>
                                    <span class="item-quantity">× {{ $item->jumlah }}</span>
                                    <span class="item-subtotal">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-items">
                            <i data-feather="package"></i>
                            <p>Detail produk tidak tersedia</p>
                        </div>
                    @endif
                </div>

                <!-- Order Actions -->
                <div class="order-actions">
                    @if($transaction->status === 'pending')
                        <a href="{{ route('customer.checkout.payment') }}" class="btn-pay">
                            <i data-feather="credit-card"></i> Bayar Sekarang
                        </a>
                        
                        <button class="btn-cancel" onclick="cancelOrder('{{ $transaction->id }}', '{{ $transaction->order_id }}')">
                            <i data-feather="x-circle"></i> Batalkan
                        </button>
                    @endif
                    
                    @if($transaction->status === 'shipped')
                        <button class="btn-track" onclick="trackOrder('{{ $transaction->id }}')">
                            <i data-feather="truck"></i> Lacak Pengiriman
                        </button>
                    @endif
                    
                    <a href="{{ route('orders.show', $transaction->id) }}" class="btn-detail">
                        <i data-feather="eye"></i> Detail Pesanan
                    </a>
                    
                    @if(in_array($transaction->status, ['completed', 'shipped']))
                        <a href="{{ route('customer.checkout.invoice', $transaction->order_id) }}" 
                           target="_blank" class="btn-invoice">
                            <i data-feather="file-text"></i> Invoice
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="pagination">
            {{ $transactions->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-orders">
            <div class="empty-icon">
                <i data-feather="shopping-bag"></i>
            </div>
            <h3>Belum ada pesanan</h3>
            <p>Mulai belanja dan pesanan Anda akan muncul di sini</p>
            <a href="{{ route('customer.products.index') }}" class="btn-shop">
                <i data-feather="shopping-cart"></i> Mulai Belanja
            </a>
        </div>
    @endif
</div>

<!-- Cancel Order Modal -->
<div class="modal" id="cancelModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Batalkan Pesanan</h3>
            <button class="modal-close" onclick="closeModal()">
                <i data-feather="x"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin membatalkan pesanan <strong id="cancelOrderId"></strong>?</p>
            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeModal()">Batal</button>
                <button class="btn-danger" id="confirmCancel">Ya, Batalkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    feather.replace();
    
    let currentOrderId = null;
    
    // Tab Filter
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            document.querySelectorAll('.order-card').forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    
    // Cancel Order
    function cancelOrder(orderId, orderNumber) {
        currentOrderId = orderId;
        document.getElementById('cancelOrderId').textContent = orderNumber;
        document.getElementById('cancelModal').style.display = 'flex';
    }
    
    // Confirm Cancel
    document.getElementById('confirmCancel').addEventListener('click', function() {
        if (!currentOrderId) return;
        
        fetch(`/customer/orders/${currentOrderId}/cancel`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pesanan berhasil dibatalkan',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            }
            closeModal();
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan',
                icon: 'error'
            });
            closeModal();
        });
    });
    
    // Track Order
    function trackOrder(orderId) {
        Swal.fire({
            title: 'Lacak Pengiriman',
            html: `Fitur pelacakan pengiriman akan segera tersedia<br><br>
                   Untuk informasi pengiriman, silakan hubungi customer service.`,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }
    
    // Modal Functions
    function closeModal() {
        document.getElementById('cancelModal').style.display = 'none';
        currentOrderId = null;
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('cancelModal');
        if (event.target === modal) {
            closeModal();
        }
    });
</script>
@endsection
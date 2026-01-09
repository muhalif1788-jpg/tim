@extends('layouts.customer')

@section('title', 'Pesanan Saya - Abon Sapi')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
<style>
    /* Rating Styles */
    .rating-container { margin-top: 10px; }
    .rating-stars { display: flex; gap: 5px; margin-bottom: 5px; }
    .rating-star { color: #ddd; cursor: pointer; transition: color 0.2s; font-size: 20px; }
    .rating-star.active { color: #ffc107; }
    .btn-rating { 
        background: #f8f9fa; border: 1px solid #dee2e6; color: #6c757d;
        padding: 6px 12px; border-radius: 4px; font-size: 13px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .rating-form { 
        background: #f1f3f5; padding: 15px; border-radius: 8px; 
        margin-top: 10px; border: 1px dashed #adb5bd;
    }
    .rating-form-buttons { display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px; }
    .already-rated { color: #28a745; font-weight: bold; font-size: 13px; display: flex; align-items: center; gap: 5px; margin-top: 5px; }
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; }
    .status-selesai { background: #dcfce7; color: #155724; }
    .status-pending { background: #fef3c7; color: #92400e; }
</style>
@endsection

@section('content')
<div class="orders-page">
    <div class="page-header">
        <h1><i data-feather="shopping-bag"></i> Pesanan Saya</h1>
        <p>Riwayat dan status pesanan Anda</p>
    </div>

    {{-- Pesan Feedback --}}
    @if(session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    @if($transactions && $transactions->count() > 0)
        <div class="orders-list">
            @foreach($transactions as $transaction)
            <div class="order-card" style="background: white; border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                <div class="order-header" style="display: flex; justify-content: space-between; border-bottom: 1px solid #f0f0f0; padding-bottom: 15px;">
                    <div class="order-info">
                        <h3 style="margin: 0; font-size: 16px;">Order #{{ $transaction->order_id }}</h3>
                        <span class="order-date" style="font-size: 12px; color: #888;">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                        <span class="badge status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                    </div>
                    <div class="order-total">
                        <span class="total-amount" style="font-weight: bold; color: #294066;">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="order-items">
                    {{-- Ganti ke 'details' sesuai permintaan --}}
                    @foreach($transaction->details as $item)
                    <div class="order-item" style="border-bottom: 1px solid #f9f9f9; padding: 15px 0; display: flex; justify-content: space-between; align-items: center;">
                        <div class="item-details">
                            {{-- Ganti ke 'nama_produk' sesuai struktur tabel Anda --}}
                            <h4 style="margin: 0 0 5px 0; font-size: 14px;">{{ $item->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</h4>
                            <p style="margin: 0; color: #666; font-size: 13px;">{{ $item->jumlah }} x Rp {{ number_format($item->harga_saat_ini, 0, ',', '.') }}</p>
                            
                            <div class="rating-section" style="margin-top: 10px;">
                                {{-- Hanya jika status 'selesai' atau 'completed' --}}
                                @if(in_array($transaction->status, ['selesai', 'completed']))
                                    @php
                                        $sudahRating = \App\Models\Penilaian::where('user_id', auth()->id())
                                                        ->where('produk_id', $item->produk_id)
                                                        ->exists();
                                    @endphp

                                    @if(!$sudahRating)
                                        <button class="btn-rating" id="btn-show-{{ $item->id }}" onclick="showRatingForm({{ $item->id }})">
                                            <i data-feather="star" style="width: 14px;"></i> Beri Rating
                                        </button>

                                        <div class="rating-form" id="rating-form-{{ $item->id }}" style="display: none;">
                                            <form action="{{ route('customer.penilaian.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="produk_id" value="{{ $item->produk_id }}">
                                                <input type="hidden" name="rating" id="rating-input-{{ $item->id }}" value="" required>

                                                <p style="margin-bottom: 8px; font-size: 12px; font-weight: bold;">Kualitas Produk:</p>
                                                <div class="rating-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span class="rating-star star-icon-{{ $item->id }}" 
                                                              onclick="setRatingValue({{ $item->id }}, {{ $i }})">
                                                            <i data-feather="star"></i>
                                                        </span>
                                                    @endfor
                                                </div>

                                                <div class="rating-form-buttons">
                                                    <button type="button" class="btn-secondary" onclick="hideRatingForm({{ $item->id }})" style="background: none; border: 1px solid #ccc; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Batal</button>
                                                    <button type="submit" class="btn-primary" style="background:#28a745; color:white; border:none; padding: 5px 15px; border-radius:4px; cursor:pointer; font-weight: bold;">Kirim</button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <div class="already-rated">
                                            <i data-feather="check-circle" style="width: 14px;"></i> Sudah Dinilai
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        
                        <a href="{{ route('orders.show', $transaction->id) }}" style="text-decoration: none; font-size: 13px; color: #294066; font-weight: bold;"> Detail > </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination" style="margin-top: 20px;">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="empty-orders" style="text-align:center; padding: 80px 20px; background: white; border-radius: 12px;">
            <i data-feather="shopping-bag" style="width:64px; height:64px; color:#ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #333;">Belum ada pesanan</h3>
            <p style="color: #888; margin-bottom: 25px;">Sepertinya Anda belum melakukan transaksi apapun.</p>
            <a href="{{ url('/products') }}" class="btn-primary" style="background: #294066; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: bold;">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    feather.replace();

    function showRatingForm(itemId) {
        document.getElementById(`rating-form-${itemId}`).style.display = 'block';
        document.getElementById(`btn-show-${itemId}`).style.display = 'none';
    }

    function hideRatingForm(itemId) {
        document.getElementById(`rating-form-${itemId}`).style.display = 'none';
        document.getElementById(`btn-show-${itemId}`).style.display = 'inline-flex';
    }

    function setRatingValue(itemId, val) {
        document.getElementById(`rating-input-${itemId}`).value = val;

        const stars = document.querySelectorAll(`.star-icon-${itemId}`);
        stars.forEach((star, index) => {
            const svg = star.querySelector('svg');
            if (index < val) {
                star.classList.add('active');
                svg.style.fill = '#ffc107';
                svg.style.color = '#ffc107';
            } else {
                star.classList.remove('active');
                svg.style.fill = 'none';
                svg.style.color = '#ddd';
            }
        });
    }
</script>
@endsection
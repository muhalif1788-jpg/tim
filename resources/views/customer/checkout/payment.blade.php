@extends('layouts.customer')

@section('content')
<div class="container payment-container">
    <div class="payment-card">
        <div class="payment-header">
            <h2>Pembayaran</h2>
            <p class="payment-note">
                <i class="fas fa-info-circle"></i> 
                Jika pembayaran dibatalkan atau gagal, produk akan tetap tersimpan di keranjang belanja Anda.
            </p>
        </div>
        
        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Order ID:</span>
                <span class="detail-value">{{ $orderId }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total:</span>
                <span class="detail-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="payment-divider"></div>
        
        <div class="payment-actions">
            <button id="pay-button" class="btn-pay">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
            </button>
            <button onclick="window.history.back()" class="btn-cancel">
                <i class="fas fa-times"></i> Batalkan Pembayaran
            </button>
            <p class="back-to-cart">
                <a href="{{ route('cart.index') }}">
                    <i class="fas fa-shopping-cart"></i> Kembali ke Keranjang
                </a>
            </p>
        </div>
        
        <form action="{{ route('customer.checkout.finish', ['orderId' => $orderId]) }}" id="submit_form" method="POST">
            @csrf
            <input type="hidden" name="json" id="json_callback">
        </form>
    </div>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
<script type="text/javascript">
    console.log('Snap Token:', '{{ $snapToken }}');
    
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            },
            onPending: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            },
            onError: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            }
        });
    };
</script>

<style>
    /* Styling untuk halaman pembayaran */
    .payment-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .payment-card {
        width: 100%;
        max-width: 450px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .payment-header {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .payment-header h2 {
        color: #333;
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 10px 0;
    }
    
    .payment-note {
        color: #666;
        font-size: 14px;
        margin: 0;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid #4CAF50;
    }
    
    .payment-note i {
        color: #4CAF50;
        margin-right: 5px;
    }
    
    .payment-details {
        margin-bottom: 25px;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .detail-label {
        color: #666;
        font-size: 16px;
    }
    
    .detail-value {
        color: #333;
        font-size: 16px;
        font-weight: 500;
    }
    
    .payment-divider {
        height: 1px;
        background-color: #e0e0e0;
        margin: 25px 0;
    }
    
    .payment-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-pay {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-pay:hover {
        background-color: #45a049;
    }
    
    .btn-cancel {
        background-color: #f8f9fa;
        color: #666;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease, color 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-cancel:hover {
        background-color: #e9ecef;
        color: #333;
    }
    
    .back-to-cart {
        text-align: center;
        margin-top: 10px;
    }
    
    .back-to-cart a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s ease;
    }
    
    .back-to-cart a:hover {
        color: #4CAF50;
    }
    
    /* Responsif untuk mobile */
    @media (max-width: 480px) {
        .payment-card {
            padding: 20px;
        }
        
        .payment-header h2 {
            font-size: 22px;
        }
        
        .payment-note {
            font-size: 13px;
            padding: 8px;
        }
        
        .detail-label, .detail-value {
            font-size: 15px;
        }
        
        .btn-pay, .btn-cancel {
            padding: 14px 18px;
            font-size: 15px;
        }
    }
</style>
@endsection
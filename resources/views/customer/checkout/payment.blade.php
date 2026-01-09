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
                <span class="detail-value text-primary fw-bold">{{ $orderId }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total:</span>
                <span class="detail-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Metode Pengiriman:</span>
                <span class="detail-value text-capitalize">{{ $transaksi->metode_pengiriman == 'delivery' ? 'Delivery (Dikirim)' : 'Pick Up (Ambil Sendiri)' }}</span>
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

{{-- Memanggil Script Midtrans Snap --}}
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(e){
        e.preventDefault();
        
        // Add loading state
        const payButton = this;
        const originalText = payButton.innerHTML;
        payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuka Pembayaran...';
        payButton.classList.add('loading');
        payButton.disabled = true;
        
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                console.log('Success:', result);
                payButton.innerHTML = originalText;
                payButton.classList.remove('loading');
                payButton.disabled = false;
                send_callback_to_server(result);
            },
            onPending: function(result){
                console.log('Pending:', result);
                payButton.innerHTML = originalText;
                payButton.classList.remove('loading');
                payButton.disabled = false;
                send_callback_to_server(result);
            },
            onError: function(result){
                console.log('Error:', result);
                payButton.innerHTML = originalText;
                payButton.classList.remove('loading');
                payButton.disabled = false;
                send_callback_to_server(result);
            },
            onClose: function(){
                /* User menutup popup tanpa menyelesaikan pembayaran */
                payButton.innerHTML = originalText;
                payButton.classList.remove('loading');
                payButton.disabled = false;
                alert('Anda menutup jendela pembayaran sebelum selesai.');
            }
        });
    };

    function send_callback_to_server(result) {
        document.getElementById('json_callback').value = JSON.stringify(result);
        document.getElementById('submit_form').submit();
    }
</script>

<style>
    /* Reset and Base */
    .payment-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }
    
    /* Card Styling */
    .payment-card {
        width: 100%;
        max-width: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 40px;
        border: 1px solid #e9ecef;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }
    
    /* Header */
    .payment-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .payment-header h2 {
        color: #212529;
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 15px;
        letter-spacing: -0.5px;
    }
    
    .payment-note {
        color: #5c636a;
        font-size: 14px;
        line-height: 1.5;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-top: 10px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    
    .payment-note i {
        color: #6c757d;
        margin-top: 2px;
        flex-shrink: 0;
    }
    
    /* Details Section */
    .payment-details {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .detail-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .detail-row:first-child {
        padding-top: 0;
    }
    
    .detail-label {
        color: #6c757d;
        font-size: 15px;
        font-weight: 500;
    }
    
    .detail-value {
        color: #212529;
        font-size: 16px;
        font-weight: 600;
        text-align: right;
    }
    
    .detail-value.text-primary {
        color: #294066;
        font-weight: 700;
        font-size: 17px;
        letter-spacing: 0.5px;
    }
    
    .detail-value.text-capitalize {
        text-transform: capitalize;
        background: #e7f1ff;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 14px;
        color: #294066;
        white-space: nowrap;
    }
    
    /* Divider */
    .payment-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #dee2e6, transparent);
        margin: 30px 0;
    }
    
    /* Action Buttons */
    .payment-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #294066 0%, #1a2d4d 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 18px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 4px 15px rgba(41, 64, 102, 0.2);
        letter-spacing: 0.5px;
    }
    
    .btn-pay:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(41, 64, 102, 0.3);
        background: linear-gradient(135deg, #1a2d4d 0%, #294066 100%);
    }
    
    .btn-pay:active {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(41, 64, 102, 0.25);
    }
    
    .btn-cancel {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
        border-radius: 12px;
        padding: 15px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-cancel:hover {
        background: #fff5f5;
        border-color: #c82333;
        color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.1);
    }
    
    /* Back to Cart Link */
    .back-to-cart {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }
    
    .back-to-cart a {
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s ease;
        padding: 8px 16px;
        border-radius: 8px;
    }
    
    .back-to-cart a:hover {
        color: #294066;
        background: #f8f9fa;
        text-decoration: none;
    }
    
    .back-to-cart i {
        font-size: 13px;
    }
    
    /* Responsive */
    @media (max-width: 576px) {
        .payment-container {
            padding: 15px;
            min-height: 70vh;
        }
        
        .payment-card {
            padding: 25px;
            border-radius: 16px;
        }
        
        .payment-header h2 {
            font-size: 24px;
        }
        
        .payment-details {
            padding: 20px;
        }
        
        .detail-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        
        .detail-label {
            font-size: 14px;
        }
        
        .detail-value {
            font-size: 15px;
            text-align: left;
            width: 100%;
        }
        
        .btn-pay,
        .btn-cancel {
            padding: 16px;
            font-size: 15px;
        }
    }
    
    /* Animation for loading state */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .loading {
        animation: pulse 1.5s infinite;
        pointer-events: none;
    }
    
    /* Spinner animation */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection
@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Progress Bar -->
    <div class="checkout-progress mb-4 mb-lg-5">
        <div class="progress-steps">
            <div class="step active">
                <div class="step-icon">1</div>
                <div class="step-label">Keranjang</div>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-icon">2</div>
                <div class="step-label">Checkout</div>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-icon">3</div>
                <div class="step-label">Pembayaran</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Form Checkout - Kiri untuk desktop, atas untuk mobile -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="checkout-card h-100">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Data Penerima & Pengiriman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_penerima" class="form-label fw-medium">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="nama_penerima" name="nama_penerima" 
                                    value="{{ Auth::user()->name }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="no_telepon" class="form-label fw-medium">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="no_telepon" name="no_telepon" 
                                    value="{{ Auth::user()->telepon ?? '' }}" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label d-block fw-semibold mb-3">Metode Pengiriman <span class="text-danger">*</span></label>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check shipping-method" name="metode_pengiriman" id="delivery" value="delivery" checked required>
                                    <label class="btn btn-outline-primary w-100 py-3 d-flex align-items-center" for="delivery">
                                        <i class="fas fa-truck fs-5 me-2"></i>
                                        <span class="fw-medium">Delivery (Kirim)</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check shipping-method" name="metode_pengiriman" id="pickup" value="pickup" required>
                                    <label class="btn btn-outline-primary w-100 py-3 d-flex align-items-center" for="pickup">
                                        <i class="fas fa-store fs-5 me-2"></i>
                                        <span class="fw-medium">Pick Up (Ambil Sendiri)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4" id="section-alamat">
                            <label for="alamat" class="form-label fw-medium">Alamat Pengiriman <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-lg" id="alamat" name="alamat" rows="3" required>{{ Auth::user()->alamat ?? '' }}</textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label for="catatan" class="form-label fw-medium">Catatan (Opsional)</label>
                            <textarea class="form-control form-control-lg" id="catatan" name="catatan" rows="2" placeholder="Contoh: Pagar warna hitam"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan - Kanan untuk desktop, bawah untuk mobile -->
        <div class="col-lg-4">
            <div class="summary-card h-100">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <!-- Daftar Produk -->
                    <div class="product-list mb-4">
                        @foreach($carts as $cart)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <div class="fw-medium mb-1">{{ $cart->produk->nama }}</div>
                                <div class="small text-muted">
                                    {{ $cart->quantity }} × Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="fw-semibold text-nowrap ms-2">
                                Rp {{ number_format($cart->produk->harga * $cart->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Garis pemisah -->
                    <hr class="my-4 border-2">
                    
                    <!-- Rincian Biaya -->
                    <div class="cost-summary mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Biaya Pengiriman</span>
                            <span id="display-ongkir" class="fw-semibold text-primary">
                                Rp {{ number_format($biaya_pengiriman, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Biaya Layanan</span>
                            <span class="fw-medium">Rp {{ number_format($biaya_admin, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <div class="text-center">
                            <div class="small text-muted mb-1">TOTAL PEMBAYARAN</div>
                            <div class="h4 fw-bold text-primary mb-0" id="display-total">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="action-buttons mt-auto">
                        <button type="submit" form="checkout-form" class="btn btn-primary w-100 py-3 mb-3 fw-bold btn-payment">
                            <i class="fas fa-lock me-2"></i> BAYAR SEKARANG
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Progress Bar */
.checkout-progress {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1.25rem 2rem;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.step-icon {
    width: 40px;
    height: 40px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    color: #6c757d;
    margin-bottom: 8px;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.step.active .step-icon {
    background: #294066;
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(41, 64, 102, 0.2);
}

.step-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
    white-space: nowrap;
}

.step.active .step-label {
    color: #294066;
    font-weight: 600;
}

.step-divider {
    width: 80px;
    height: 2px;
    background: linear-gradient(to right, #294066 50%, #e9ecef 50%);
    margin: 0 15px;
    position: relative;
    top: -20px;
}

/* Cards - Equal Height */
.checkout-card,
.summary-card {
    border: 1px solid #e0e0e0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    background: white;
    display: flex;
    flex-direction: column;
}

.h-100 {
    height: 100%;
}

.checkout-card .card-body,
.summary-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 1.5rem;
}

.card-header h5 {
    color: #212529;
    font-size: 1.1rem;
}

.card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Form Styles */
.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control,
.form-control-lg {
    border: 1.5px solid #dee2e6;
    border-radius: 10px;
    padding: 0.875rem 1rem;
    font-size: 15px;
    transition: all 0.2s;
}

.form-control:focus,
.form-control-lg:focus {
    border-color: #294066;
    box-shadow: 0 0 0 4px rgba(41, 64, 102, 0.1);
    outline: none;
}

/* Shipping Method Buttons */
.shipping-method + .btn-outline-primary {
    border: none;
    background: white;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.shipping-method + .btn-outline-primary:hover {

}

.shipping-method:checked + .btn-outline-primary {
    margin: 8px;
}

/* Product List */
.product-list {
    flex: 1;
    max-height: 220px;
    overflow-y: auto;
    padding-right: 8px;
    margin-bottom: 1.5rem;
}

.product-list::-webkit-scrollbar {
    width: 6px;
}

.product-list::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 10px;
}

.product-list::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 10px;
}

.product-list::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

/* Summary Styles */
.cost-summary {
    font-size: 15px;
}

.cost-summary > div {
    padding: 0.5rem 0;
}

/* Total Section */
.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e0e0e0;
}

.text-primary {
    color: #294066 !important;
}

.h4 {
    font-size: 1.5rem;
}

/* Buttons */
.btn-primary {
    background: #294066;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    padding: 1rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #1a2d4d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(41, 64, 102, 0.2);
}

.btn-outline-secondary {
    border: 1.5px solid #dee2e6;
    color: #495057;
    border-radius: 12px;
    font-weight: 500;
    padding: 1rem;
    transition: all 0.2s;
}

.btn-outline-secondary:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    color: #495057;
}

.action-buttons {
    margin-top: auto;
}

/* Desktop Layout (≥ 992px) */
@media (min-width: 992px) {
    .row {
        display: flex;
        align-items: stretch;
    }
    
    .col-lg-8 {
        padding-right: 1.5rem;
    }
    
    .col-lg-4 {
        position: sticky;
        top: 20px;
        height: fit-content;
    }
    
    .checkout-progress {
        padding: 1.5rem 2.5rem;
    }
    
    .step-divider {
        width: 100px;
    }
    
    /* Equal height cards */
    .checkout-card,
    .summary-card {
        min-height: 650px;
    }
    
    /* Ensure form fills height */
    #checkout-form {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* Make sure form content fills available space */
    #checkout-form > div:not(.action-buttons) {
        flex: 0 0 auto;
    }
}

/* Mobile Layout (< 992px) */
@media (max-width: 991px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .checkout-progress {
        margin: 0 -15px 1.5rem;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .step-divider {
        width: 40px;
    }
    
    .col-lg-8 {
        margin-bottom: 1.5rem;
    }
    
    .checkout-card,
    .summary-card {
        min-height: auto;
    }
}

/* Small Mobile (< 576px) */
@media (max-width: 575px) {
    .container {
        padding-left: 12px;
        padding-right: 12px;
    }
    
    .checkout-progress {
        padding: 1rem;
    }
    
    .step-icon {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    
    .step-label {
        font-size: 12px;
    }
    
    .step-divider {
        width: 30px;
        margin: 0 10px;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .btn {
        padding: 0.875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const paymentBtn = document.querySelector('.btn-payment');
    const radios = document.querySelectorAll('.shipping-method');
    const sectionAlamat = document.getElementById('section-alamat');
    const inputAlamat = document.getElementById('alamat');
    
    // Element Display Harga
    const displayOngkir = document.getElementById('display-ongkir');
    const displayTotal = document.getElementById('display-total');
    
    // Data dari Server
    const subtotal = {{ $subtotal }};
    const biayaAdmin = {{ $biaya_admin }};
    const ongkirTetap = {{ $biaya_pengiriman }};

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Logic Switch Metode Pengiriman
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            let currentOngkir = 0;
            
            if (this.value === 'delivery') {
                currentOngkir = ongkirTetap;
                displayOngkir.textContent = formatRupiah(ongkirTetap);
                sectionAlamat.style.display = 'block';
                inputAlamat.setAttribute('required', 'required');
            } else {
                currentOngkir = 0;
                displayOngkir.textContent = 'Gratis';
                sectionAlamat.style.display = 'none';
                inputAlamat.removeAttribute('required');
            }
            
            const totalBaru = subtotal + currentOngkir + biayaAdmin;
            displayTotal.textContent = formatRupiah(totalBaru);
        });
    });

    // Validasi & Loading Submit
    form.addEventListener('submit', function() {
        paymentBtn.classList.add('loading');
        paymentBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        paymentBtn.disabled = true;
    });
});
</script>
@endsection
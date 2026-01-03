@extends('layouts.customer')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="checkout-container">
                <!-- Progress Indicator -->
                <div class="checkout-progress mb-5">
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

                <div class="row g-4">
                    <!-- Data Penerima -->
                    <div class="col-lg-8">
                        <div class="card checkout-card">
                            <div class="card-header">
                                <h4 class="mb-0">Data Penerima</h4>
                            </div>
                            <div class="card-body">
                                <!-- PERBAIKAN: Tambahkan ID pada form -->
                                <form action="{{ route('customer.checkout.store') }}" method="POST" 
                                      class="checkout-form" id="checkout-form">
                                    @csrf
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_penerima" class="form-label">Nama Penerima *</label>
                                                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" 
                                                    value="{{ Auth::user()->name }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="no_telepon" class="form-label">No. Telepon *</label>
                                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                                    value="{{ Auth::user()->telepon ?? '' }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mt-3">
                                        <label for="alamat" class="form-label">Alamat Pengiriman *</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ Auth::user()->alamat ?? '' }}</textarea>
                                    </div>
                                    
                                    <div class="form-group mt-3">
                                        <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
                                        <small class="text-muted">Contoh: Tinggal di lantai 3, warna rumah biru, dll.</small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ringkasan Pesanan -->
                    <div class="col-lg-4">
                        <div class="card summary-card">
                            <div class="card-header">
                                <h4 class="mb-0">Ringkasan Pesanan</h4>
                            </div>
                            <div class="card-body">
                                <!-- Daftar Produk -->
                                <div class="product-list mb-3">
                                    @foreach($carts as $cart)
                                    <div class="product-item">
                                        <div class="product-info">
                                            <div class="product-name">{{ $cart->produk->nama }}</div>
                                            <div class="product-details">
                                                {{ $cart->quantity }} x Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="product-total">
                                            Rp {{ number_format($cart->produk->harga * $cart->quantity, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- Divider -->
                                <hr class="summary-divider">
                                
                                <!-- Ringkasan Biaya -->
                                <div class="cost-summary">
                                    <div class="cost-item">
                                        <span class="cost-label">Subtotal</span>
                                        <span class="cost-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="cost-item">
                                        <span class="cost-label">Biaya Pengiriman</span>
                                        <span class="cost-value">Rp {{ number_format($biaya_pengiriman, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="cost-item">
                                        <span class="cost-label">Biaya Admin</span>
                                        <span class="cost-value">Rp {{ number_format($biaya_admin, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <!-- Divider -->
                                <hr class="summary-divider">
                                
                                <!-- Total Section -->
                                <div class="total-section">
                                    <div class="total-header">
                                        <span class="total-label">Total</span>
                                    </div>
                                    <div class="total-amount">
                                        <span class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="card-footer bg-white action-buttons">
                                <div class="action-buttons-container">
                                    <!-- PERBAIKAN: Tombol sudah terhubung dengan form ID -->
                                    <button type="submit" form="checkout-form" class="btn btn-payment">
                                        <i class="fas fa-lock me-2"></i>
                                        <span class="btn-text">Lanjut ke Pembayaran</span>
                                    </button>
                                    
                                    <a href="{{ route('cart.index') }}" class="btn btn-back">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        <span class="btn-text">Kembali ke Keranjang</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* [CSS tetap sama, tidak perlu perubahan] */
/* Base Styles */
.checkout-container {
    padding: 0 15px;
}

/* Cards */
.checkout-card,
.summary-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.checkout-card .card-header,
.summary-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
}

.summary-card .card-header h4 {
    color: #294066; 
}

.checkout-card .card-body,
.summary-card .card-body {
    padding: 1.5rem;
}

/* Total Section */
.total-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.25rem;
    margin: 1.5rem 0;
    text-align: center;
}

.total-header {
    margin-bottom: 0.75rem;
}

.total-label {
    color: #6c757d;
    font-size: 1rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.total-amount {
    margin-top: 0.5rem;
}

.total-value {
    color: #294066;
    font-size: 1.75rem;
    font-weight: 700;
    display: block;
}

/* Action Buttons */
.action-buttons {
    border-top: 1px solid #e9ecef;
    padding: 1.5rem;
    background: #fff;
}

.action-buttons-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
}

.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.875rem 1.25rem;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    min-height: 52px;
    width: 100%;
}

.btn-payment {
    background: #294066;
    color: white;
    order: 1;
}

.btn-payment:hover {
    background: #1a2d4d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(41, 64, 102, 0.2);
}

.btn-back {
    background: transparent;
    color: #6c757d;
    border: 2px solid #dee2e6 !important;
    order: 2;
}

.btn-back:hover {
    background: #f8f9fa;
    border-color: #294066 !important;
    color: #294066;
}

.btn i {
    font-size: 1rem;
    flex-shrink: 0;
}

.btn-text {
    flex-grow: 1;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Responsive Design */
@media (min-width: 992px) {
    .action-buttons-container {
        gap: 0.875rem;
    }
    
    .btn {
        min-height: 56px;
        font-size: 1.05rem;
    }
    
    .total-value {
        font-size: 2rem;
    }
}

@media (max-width: 991px) and (min-width: 768px) {
    .action-buttons-container {
        gap: 0.75rem;
    }
    
    .btn {
        min-height: 50px;
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
    }
    
    .total-value {
        font-size: 1.5rem;
    }
}

@media (max-width: 767px) and (min-width: 577px) {
    .action-buttons-container {
        flex-direction: column;
        gap: 0.625rem;
    }
    
    .btn {
        min-height: 48px;
        font-size: 0.9rem;
        padding: 0.7rem 0.875rem;
    }
    
    .total-value {
        font-size: 1.4rem;
    }
    
    .btn i {
        margin-right: 0.4rem;
        font-size: 0.9rem;
    }
    
    .btn-text {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .action-buttons {
        padding: 1.25rem;
    }
    
    .action-buttons-container {
        gap: 0.5rem;
    }
    
    .btn {
        min-height: 46px;
        font-size: 0.875rem;
        padding: 0.625rem 0.75rem;
        width: 100%;
    }
    
    .total-value {
        font-size: 1.3rem;
    }
    
    .btn i {
        margin-right: 0.375rem;
        font-size: 0.875rem;
        width: 16px;
        text-align: center;
    }
    
    .btn-text {
        font-size: 0.875rem;
    }
}

@media (max-width: 375px) {
    .btn {
        min-height: 44px;
        font-size: 0.85rem;
        padding: 0.5rem 0.625rem;
    }
    
    .btn i {
        margin-right: 0.25rem;
        font-size: 0.85rem;
    }
    
    .btn-text {
        font-size: 0.85rem;
    }
    
    .total-value {
        font-size: 1.2rem;
    }
    
    .btn span:not(.btn-text) {
        display: none;
    }
}

/* Progress Indicator */
.checkout-progress {
    padding: 0 20px;
}

.progress-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
    border: 2px solid #e9ecef;
}

.step.active .step-icon {
    background: #294066;
    color: white;
    border-color: #294066;
}

.step-label {
    margin-top: 8px;
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
    min-width: 80px;
}

.step.active .step-label {
    color: #294066;
    font-weight: 600;
}

.step-divider {
    width: 100px;
    height: 2px;
    background: #e9ecef;
    margin: 0 10px;
}

/* Form Validation Styles */
.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

/* Loading state */
.btn.loading {
    position: relative;
    color: transparent;
    pointer-events: none;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
    left: calc(50% - 10px);
    top: calc(50% - 10px);
}

.btn-back.loading::after {
    border: 2px solid rgba(41, 64, 102, 0.3);
    border-top-color: #294066;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Focus states */
.btn:focus {
    outline: 3px solid rgba(41, 64, 102, 0.3);
    outline-offset: 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // PERBAIKAN: Form sudah punya ID 'checkout-form'
    const form = document.getElementById('checkout-form');
    const paymentBtn = document.querySelector('.btn-payment');
    
    if (paymentBtn && form) {
        paymentBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah submit default dulu
            
            console.log('Tombol pembayaran diklik');
            
            // Validasi form
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let invalidFields = [];
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    invalidFields.push(field.previousElementSibling?.textContent || field.name);
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                alert('Harap lengkapi semua field yang wajib diisi:\n' + invalidFields.join('\n'));
                return;
            }
            
            console.log('Form valid, mengirim...');
            
            // Tampilkan loading state
            this.classList.add('loading');
            this.disabled = true;
            this.innerHTML = '<span class="btn-text">Memproses...</span>';
            
            // Submit form setelah validasi
            setTimeout(() => {
                form.submit();
            }, 500);
            
            // Reset loading state setelah 10 detik (fallback)
            setTimeout(() => {
                this.classList.remove('loading');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-lock me-2"></i><span class="btn-text">Lanjut ke Pembayaran</span>';
            }, 10000);
        });
    } else {
        console.error('Form atau tombol tidak ditemukan');
        if (!form) console.error('Form dengan ID checkout-form tidak ditemukan');
        if (!paymentBtn) console.error('Tombol .btn-payment tidak ditemukan');
    }
    
    // Phone number formatting
    const phoneInput = document.getElementById('no_telepon');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 4) {
                    value = value;
                } else if (value.length <= 8) {
                    value = value.replace(/(\d{4})(\d{0,4})/, '$1-$2');
                } else {
                    value = value.replace(/(\d{4})(\d{4})(\d{0,})/, '$1-$2-$3');
                }
            }
            e.target.value = value;
        });
    }
    
    // Auto resize textarea
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
    
    // Debug: Cek apakah form dan tombol terhubung
    console.log('Form ditemukan:', !!form);
    console.log('Tombol ditemukan:', !!paymentBtn);
    
    if (form && paymentBtn) {
        console.log('Tombol form attribute:', paymentBtn.getAttribute('form'));
        console.log('Form ID:', form.id);
    }
});
</script>
@endsection
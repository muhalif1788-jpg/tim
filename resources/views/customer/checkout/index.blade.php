<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kedai Pesisir</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .checkout-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .checkout-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .checkout-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .checkout-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .item-price {
            color: #667eea;
            font-weight: 600;
        }
        
        .item-quantity {
            color: #718096;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .summary-row.total {
            border-top: 2px solid #e2e8f0;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d3748;
        }
        
        .btn {
            display: inline-block;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-secondary:hover {
            background: #cbd5e0;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #667eea;
            text-decoration: none;
            margin-bottom: 20px;
        }
        
        .back-link i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back to Cart -->
        <a href="{{ route('cart.index') }}" class="back-link">
            <i data-feather="arrow-left"></i> Kembali ke Keranjang
        </a>
        
        <!-- Header -->
        <div class="checkout-header">
            <h1>Checkout</h1>
            <p>Lengkapi data dan konfirmasi pembelian Anda</p>
        </div>
        
        <!-- Alert Messages -->
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="checkout-grid">
            <!-- Left Column: Form & Items -->
            <div>
                <!-- Form Data Pengiriman -->
                <div class="checkout-section">
                    <h2 class="section-title">Data Pengiriman</h2>
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label">Nama Penerima *</label>
                            <input type="text" name="nama_penerima" class="form-control" 
                                   value="{{ Auth::user()->name }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea name="alamat" class="form-control" rows="3" required 
                                      placeholder="Jl. Contoh No. 123, Kota, Kode Pos"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon *</label>
                            <input type="text" name="no_telepon" class="form-control" 
                                   placeholder="0812-3456-7890" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="2" 
                                      placeholder="Contoh: Tinggal di depan warung biru"></textarea>
                        </div>
                    </form>
                </div>
                
                <!-- Daftar Produk -->
                <div class="checkout-section">
                    <h2 class="section-title">Produk yang Dipesan</h2>
                    
                    @foreach($carts as $cart)
                    <div class="cart-item">
                        @if($cart->produk->gambar)
                            <img src="{{ asset('storage/' . $cart->produk->gambar) }}" 
                                 alt="{{ $cart->produk->nama_produk }}" class="item-image">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" 
                                 alt="Default" class="item-image">
                        @endif
                        
                        <div class="item-details">
                            <div class="item-name">{{ $cart->produk->nama_produk }}</div>
                            <div class="item-price">
                                Rp {{ number_format($cart->produk->harga, 0, ',', '.') }}
                            </div>
                            <div class="item-quantity">
                                Jumlah: {{ $cart->quantity }} pcs
                            </div>
                        </div>
                        
                        <div class="item-total">
                            <strong>Rp {{ number_format($cart->produk->harga * $cart->quantity, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Column: Ringkasan Pembayaran -->
            <div>
                <div class="checkout-section">
                    <h2 class="section-title">Ringkasan Pembayaran</h2>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Biaya Pengiriman</span>
                        <span>Rp {{ number_format($biaya_pengiriman, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Biaya Admin</span>
                        <span>Rp {{ number_format($biaya_admin, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Metode Pembayaran (placeholder) -->
                    <div style="margin: 25px 0; padding: 15px; background: #f7fafc; border-radius: 8px;">
                        <strong>Metode Pembayaran:</strong>
                        <p style="margin-top: 8px; color: #718096; font-size: 0.9rem;">
                            Pembayaran dilakukan via transfer setelah checkout.
                            Instruksi pembayaran akan dikirim via WhatsApp/Email.
                        </p>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <button type="submit" form="checkoutForm" class="btn btn-primary">
                        <i data-feather="credit-card"></i> Konfirmasi & Bayar
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                        <i data-feather="shopping-cart"></i> Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        feather.replace();
        
        // Validasi form sebelum submit
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const nama = document.querySelector('input[name="nama_penerima"]').value;
            const alamat = document.querySelector('textarea[name="alamat"]').value;
            const telepon = document.querySelector('input[name="no_telepon"]').value;
            
            if (!nama || !alamat || !telepon) {
                e.preventDefault();
                alert('Harap lengkapi semua data yang wajib diisi!');
                return false;
            }
            
            // Konfirmasi
            if (!confirm('Apakah Anda yakin ingin melanjutkan checkout?')) {
                e.preventDefault();
                return false;
            }
            
            // Tampilkan loading
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<span>Memproses...</span>';
            btn.disabled = true;
        });
    </script>
</body>
</html>
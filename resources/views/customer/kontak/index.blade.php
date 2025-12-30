@extends('layouts.customer')

@section('title', 'Kontak Kami - Kedai Pesisir')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
@endsection

@section('content')
<!-- Kontak Hero Section -->
<section class="kontak-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">HUBUNGI KAMI</h1>
            <p class="hero-subtitle">Kami Siap Melayani Anda dengan Sepenuh Hati</p>
            <p class="hero-description">Punya pertanyaan, masukan, atau ingin memesan secara langsung? Jangan ragu untuk menghubungi kami.</p>
        </div>
    </div>
</section>

<!-- Kontak Content -->
<section class="kontak-content">
    <div class="container">
        <div class="kontak-grid">
            <!-- Info Kontak -->
            <div class="kontak-info">
                <div class="info-card">
                    <div class="info-header">
                        <h2><i data-feather="info"></i> Informasi Kontak</h2>
                        <p>Berbagai cara untuk menghubungi kami</p>
                    </div>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon">
                                <i data-feather="phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Telepon</h3>
                                <p>+62 21 1234 5678</p>
                                <small>Senin - Jumat, 08:00 - 17:00 WIB</small>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i data-feather="mail"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>info@kedaipesisir.com</p>
                                <p>order@kedaipesisir.com</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i data-feather="map-pin"></i>
                            </div>
                            <div class="info-content">
                                <h3>Alamat</h3>
                                <p>Jl. Pesisir Indah No. 123</p>
                                <p>Kec. Parepare, Kota Parepare</p>
                                <p>Sulawesi Selatan 91123</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i data-feather="clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Jam Operasional</h3>
                                <p><strong>Senin - Jumat:</strong> 08:00 - 17:00</p>
                                <p><strong>Sabtu:</strong> 08:00 - 15:00</p>
                                <p><strong>Minggu & Hari Libur:</strong> Tutup</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-media">
                        <h3>Ikuti Kami</h3>
                        <div class="social-icons">
                            <a href="#" class="social-icon" title="Instagram">
                                <i data-feather="instagram"></i>
                            </a>
                            <a href="#" class="social-icon" title="Facebook">
                                <i data-feather="facebook"></i>
                            </a>
                            <a href="#" class="social-icon" title="WhatsApp">
                                <i data-feather="message-circle"></i>
                            </a>
                            <a href="#" class="social-icon" title="Twitter">
                                <i data-feather="twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Kontak -->
            <div class="kontak-form">
                <div class="form-card">
                    <div class="form-header">
                        <h2><i data-feather="send"></i> Kirim Pesan</h2>
                        <p>Isi formulir di bawah ini untuk menghubungi kami</p>
                    </div>
                    
                    <form action="#" method="POST" class="contact-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">
                                <i data-feather="user"></i> Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" required 
                                   placeholder="Masukkan nama lengkap Anda"
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <i data-feather="mail"></i> Alamat Email
                            </label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="contoh@email.com"
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">
                                <i data-feather="phone"></i> Nomor Telepon
                            </label>
                            <input type="tel" id="phone" name="phone" 
                                   placeholder="0812-3456-7890"
                                   class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">
                                <i data-feather="file-text"></i> Subjek
                            </label>
                            <select id="subject" name="subject" class="form-control" required>
                                <option value="" selected disabled>Pilih subjek pesan</option>
                                <option value="order">Pemesanan Produk</option>
                                <option value="inquiry">Pertanyaan Produk</option>
                                <option value="complaint">Keluhan & Saran</option>
                                <option value="partnership">Kerjasama & Partnership</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">
                                <i data-feather="message-square"></i> Pesan
                            </label>
                            <textarea id="message" name="message" rows="6" required 
                                      placeholder="Tulis pesan Anda di sini..."
                                      class="form-control"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn-submit">
                                <i data-feather="send"></i> Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Peta Lokasi -->
        <div class="lokasi-section">
            <div class="section-header">
                <h2><i data-feather="map"></i> Lokasi Kami</h2>
                <p>Temukan toko fisik kami di sini</p>
            </div>
            
            <div class="map-container">
                <!-- Google Maps Embed -->
                <div class="map-wrapper">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.933573342254!2d119.650111315727!3d-4.010837996909952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d95b8e6d74c5f0f%3A0x7a7f9e3a4b6b4b4b!2sParepare%2C%20Kota%20Parepare%2C%20Sulawesi%20Selatan!5e0!3m2!1sid!2sid!4v1636512345678!5m2!1sid!2sid" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        class="google-map">
                    </iframe>
                    
                    <div class="map-info">
                        <div class="map-info-item">
                            <i data-feather="navigation"></i>
                            <div>
                                <h4>Petunjuk Arah</h4>
                                <p>Dari Bandara Sultan Hasanuddin: 3 jam perjalanan</p>
                            </div>
                        </div>
                        <div class="map-info-item">
                            <i data-feather="car"></i>
                            <div>
                                <h4>Parkir</h4>
                                <p>Area parkir luas tersedia untuk pelanggan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="section-header">
                <h2><i data-feather="help-circle"></i> Pertanyaan yang Sering Diajukan</h2>
                <p>Temukan jawaban atas pertanyaan umum</p>
            </div>
            
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Bagaimana cara memesan produk?</span>
                        <i data-feather="chevron-down" class="faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Anda dapat memesan produk melalui website kami dengan menambahkan produk ke keranjang dan melakukan checkout. Atau langsung hubungi kami via WhatsApp di +62 812-3456-7890.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Berapa lama waktu pengiriman?</span>
                        <i data-feather="chevron-down" class="faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Waktu pengiriman bervariasi tergantung lokasi: <br>
                        - Dalam kota Parepare: 1-2 hari kerja<br>
                        - Sulawesi Selatan: 2-3 hari kerja<br>
                        - Luar pulau: 3-7 hari kerja</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Apakah tersedia pengiriman same-day?</span>
                        <i data-feather="chevron-down" class="faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Ya, untuk area Kota Parepare kami menyediakan layanan same-day delivery untuk pemesanan sebelum jam 14:00 WITA dengan biaya tambahan Rp 15.000.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Bagaimana metode pembayaran yang tersedia?</span>
                        <i data-feather="chevron-down" class="faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Kami menerima pembayaran via:<br>
                        - Transfer Bank (BCA, BRI, Mandiri, BNI)<br>
                        - E-Wallet (OVO, GoPay, Dana, ShopeePay)<br>
                        - QRIS<br>
                        - Cash on Delivery (COD) untuk area tertentu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    feather.replace();
    
    // FAQ Toggle Functionality
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', () => {
            const faqItem = button.parentElement;
            const answer = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            
            // Toggle active class
            faqItem.classList.toggle('active');
            
            // Toggle answer display
            if (faqItem.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.style.maxHeight = '0';
                icon.style.transform = 'rotate(0deg)';
            }
            
            // Close other FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== faqItem && item.classList.contains('active')) {
                    item.classList.remove('active');
                    const otherAnswer = item.querySelector('.faq-answer');
                    const otherIcon = item.querySelector('.faq-icon');
                    otherAnswer.style.maxHeight = '0';
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
    
    // Form Submission Alert
    document.querySelector('.contact-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Pesan Terkirim!',
            text: 'Terima kasih telah menghubungi kami. Kami akan membalas pesan Anda dalam 1x24 jam.',
            icon: 'success',
            confirmButtonColor: '#294066',
            confirmButtonText: 'OK'
        }).then(() => {
            // Reset form
            this.reset();
        });
    });
</script>
@endsection
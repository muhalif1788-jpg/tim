{{-- resources/views/layouts/customer.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Abon Sapi') - Cita Rasa yang Tak Terlupakan</title>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap CSS (untuk form checkout) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ url('/') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <img src="{{ asset('images/logo.png') }}" alt="LOGO" class="logo-img">
                        <div class="logo-text">
                            <span class="logo-main">Predict &</span>
                            <span class="logo-sub">Selenpleapnya</span>
                        </div>
                    </a>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                        
                        <!-- Auth Logic untuk Produk Link -->
                        @auth
                            <li><a href="{{ route('customer.products.index') }}" class="nav-link {{ request()->is('customer/products*') ? 'active' : '' }}">Produk</a></li>
                        @else
                            <li><a href="#produk" class="nav-link">Produk</a></li>
                        @endauth
                        
                        <li><a href="#tentang" class="nav-link">Tentang Kami</a></li>
                        <li><a href="#kontak" class="nav-link">Kontak</a></li>

                        <!-- Navbar Keranjang -->
                        @auth
                            <li><a href="{{ route('cart.index') }}" class="nav-link">
                                <i data-feather="shopping-cart"></i>
                                Keranjang
                                @if(isset($cartCount) && $cartCount > 0)
                                    <span class="cart-badge">{{ $cartCount }}</span>
                                @endif
                            </a></li>
                        @else
                            <li>
                                <button class="nav-link-btn" onclick="showLoginAlert('mengakses keranjang')">
                                    <i data-feather="shopping-cart"></i>
                                </button>
                            </li>
                        @endauth
                        
                        <!-- Auth Logic untuk Login/Logout -->
                        @auth
                            <li class="nav-auth">
                                <span class="user-welcome">
                                    <i data-feather="user"></i>
                                    Hi, {{ Auth::user()->name }}
                                </span>
                            </li>
                            <li class="nav-auth">
                                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                    @csrf
                                    <button type="submit" class="btn-logout">
                                        <i data-feather="log-out"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="nav-auth">
                                <a href="{{ route('login') }}" class="btn-login">
                                    <i data-feather="log-in"></i>
                                    Login
                                </a>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container py-4">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer" id="kontak">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ABON SAPI</h3>
                    <p>Kedai dengan sejuta cita rasa yang tidak terlupakan</p>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>Email: info@abonsapi.com</p>
                    <p>Telp: (021) 1234-5678</p>
                </div>
                <div class="footer-section">
                    <h3>Alamat</h3>
                    <p>Jl. Contoh No. 123<br>Jakarta, Indonesia</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Abon Sapi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Feather Icons
        feather.replace();
        
        // Fungsi untuk show login alert
        function showLoginAlert(action) {
            Swal.fire({
                title: 'Login Diperlukan',
                html: `Anda perlu login untuk <strong>${action}</strong>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#294066',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/produk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kontak.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ url('/dashboard') }}" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <img src="{{ asset('images/logo.png') }}" alt="LOGO" class="logo-img">
                        <div class="logo-text">
                            <span class="logo-main">ABON</span>
                            <span class="logo-sub">UMMI</span>
                        </div>
                    </a>
                </div>
                <nav class="nav">
                    <ul class="nav-list">
                        <!-- Dashboard/Home -->
                        @auth
                            <li>
                                <a href="{{ url('/dashboard') }}" 
                                class="nav-link {{ request()->is('dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ url('/') }}" 
                                class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                                    Home
                                </a>
                            </li>
                        @endauth
                        
                        <!-- Produk -->
                        @auth
                            <li>
                                <a href="{{ route('customer.products.index') }}" 
                                class="nav-link {{ request()->is('customer/products*') || request()->routeIs('customer.products*') ? 'active' : '' }}">
                                    Produk
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('products.public') }}" 
                                class="nav-link {{ request()->is('products*') || request()->routeIs('products.public') ? 'active' : '' }}">
                                    Produk
                                </a>
                            </li>
                        @endauth
                        
                        

                        <!-- Navbar Keranjang -->
                        @auth
                            <li>
                                <a href="{{ route('cart.index') }}" 
                                class="nav-link {{ request()->is('cart*') || request()->routeIs('cart.*') ? 'active' : '' }}">
                                    <i data-feather="shopping-cart"></i>
                                    Keranjang
                                    @php
                                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                                    @endphp
                                    @if($cartCount > 0)
                                        <span class="cart-badge">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @else
                            <li>
                                <button class="nav-link-btn" onclick="showLoginAlert('mengakses keranjang')">
                                    <i data-feather="shopping-cart"></i>
                                </button>
                            </li>
                        @endauth

                        <!-- Tentang Kami -->
                        <li>
                            <a href="{{ route('tentang') }}" 
                            class="nav-link {{ request()->is('tentang*') || request()->routeIs('tentang*') ? 'active' : '' }}">
                                Tentang Kami
                            </a>
                        </li>
                        
                        <!-- Kontak -->
                        <li>
                            <a href="{{ url('/kontak') }}" 
                            class="nav-link {{ request()->is('kontak*') ? 'active' : '' }}">
                                Kontak
                            </a>
                        </li>

                        <!-- Profil Dropdown -->
                        @auth
                            <li class="profile-dropdown-container">
                                <button class="profile-dropdown-btn" id="profileDropdownBtn">
                                    <div class="profile-avatar">
                                        @if(Auth::user()->profile_picture)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                                 alt="{{ Auth::user()->name }}" class="profile-img">
                                        @else
                                            <div class="avatar-placeholder">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="profile-name">{{ Auth::user()->name }}</span>
                                    <i data-feather="chevron-down" class="dropdown-icon"></i>
                                </button>
                                
                                <div class="profile-dropdown-menu" id="profileDropdownMenu">
                                    <!-- Header Profil -->
                                    <div class="profile-menu-header">
                                        <div class="profile-menu-avatar">
                                            @if(Auth::user()->profile_picture)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                                     alt="{{ Auth::user()->name }}">
                                            @else
                                                <div class="menu-avatar-placeholder">
                                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="profile-menu-info">
                                            <h4>{{ Auth::user()->name }}</h4>
                                            <p>{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="profile-menu-divider"></div>
                                    
                                    <!-- Menu Items -->
                                    <a href="{{ route('profile.index') }}" class="profile-menu-item">
                                        <i data-feather="user"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    
                                    <a href="{{ route('orders.index') }}" class="profile-menu-item">
                                        <i data-feather="shopping-bag"></i>
                                        <span>Pesanan Saya</span>
                                        @php
                                            $pendingOrders = \App\Models\Transaksi::where('user_id', auth()->id())
                                                ->where('status', 'pending')
                                                ->count();
                                        @endphp
                                        @if($pendingOrders > 0)
                                            <span class="menu-badge">{{ $pendingOrders }}</span>
                                        @endif
                                    </a>
                                    
                                    <div class="profile-menu-divider"></div>
                                    
                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                                        @csrf
                                        <button type="button" class="profile-menu-item logout-btn" id="logoutBtn">
                                            <i data-feather="log-out"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @else
                            <li class="login-btn-container">
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
                    <p>Email: abonummi@gmail.com</p>
                    <p>Telp: (021) 1234-5678</p>
                </div>
                <div class="footer-section">
                    <h3>Alamat</h3>
                    <p>Jl. Keterampilan, Cappagalung, Kec.Bacukiki Barat<br>Parepare, Indonesia</p>
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
        
        // Profile Dropdown Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdownMenu');
            const logoutBtn = document.getElementById('logoutBtn');
            
            // Toggle dropdown
            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                    dropdownBtn.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                        dropdownBtn.classList.remove('active');
                    }
                });
                
                // Close dropdown on menu item click
                dropdownMenu.querySelectorAll('.profile-menu-item').forEach(item => {
                    item.addEventListener('click', function() {
                        dropdownMenu.classList.remove('show');
                        dropdownBtn.classList.remove('active');
                    });
                });
            }
            
            // Logout confirmation
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: 'Apakah Anda yakin ingin keluar?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#294066',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Keluar',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logoutForm').submit();
                        }
                    });
                });
            }
            
            // Auto-dismiss alerts after 5 seconds
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
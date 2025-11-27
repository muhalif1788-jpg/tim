<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - Abon Sapi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #dc2626;
            --primary-dark: #b91c1c;
            --secondary: #f59e0b;
            --accent: #16a34a;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            border: 1px solid #d1d5db;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-secondary:hover {
            background-color: #f9fafb;
            transform: translateY(-1px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link:hover:not(.active) {
            background-color: #374151;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white shadow-lg">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Admin Panel</h2>
                        <p class="text-gray-400 text-xs">Abon Sapi Premium</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 
                          {{ request()->is('admin/dashboard') || request()->is('admin') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.kategori.index') }}"
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 
                          {{ request()->is('admin/kategori*') ? 'active' : '' }}">
                    <i class="fas fa-list w-5"></i>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.produk.index') }}"
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 
                          {{ request()->is('admin/produk*') ? 'active' : '' }}">
                    <i class="fas fa-box w-5"></i>
                    <span>Produk</span>
                </a>

                <a href="{{ route('admin.transaksi.index') }}"
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 
                          {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span>Transaksi</span>
                </a>

                <a href="{{ route('admin.user.index') }}"
                   class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 
                          {{ request()->is('admin/user*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span>User</span>
                </a>

                <div class="pt-4 mt-4 border-t border-gray-700">
                    <a href="{{ route('home') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg text-yellow-300 hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-store w-5"></i>
                        <span>Kembali ke Toko</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Top Navigation dengan User Dropdown -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-chart-line text-red-500 mr-2"></i>
                            @yield('page-title', 'Dashboard Admin')
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="btn-secondary relative">
                                <i class="fas fa-bell"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">
                                    {{ $unreadNotifications ?? 0 }}
                                </span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button class="btn-secondary flex items-center space-x-2" id="user-menu-button">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-red-500 text-sm"></i>
                                </div>
                                <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-10 border border-gray-200">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                                    Login sebagai: <strong>{{ Auth::user()->role ?? 'admin' }}</strong>
                                </div>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="px-6 py-4">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            {{ session('success') }}
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            {{ session('error') }}
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <div class="p-6">
                @yield('content') <!-- HANYA SATU KALI -->
            </div>
        </main>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
@extends('layouts.customer')

@section('title', 'Profil Saya - Abon Sapi')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-header">
        <div class="profile-avatar-large">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                     alt="{{ Auth::user()->name }}" class="avatar-img">
            @else
                <div class="avatar-initials">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <button class="avatar-change-btn" onclick="document.getElementById('avatarInput').click()">
                <i data-feather="camera"></i>
            </button>
            <input type="file" id="avatarInput" accept="image/*" style="display: none;">
        </div>
        
        <div class="profile-info">
            <h1>{{ Auth::user()->name }}</h1>
            <p class="user-email">{{ Auth::user()->email }}</p>
            <p class="member-since">Member sejak {{ Auth::user()->created_at->format('d F Y') }}</p>
        </div>
    </div>

    <div class="profile-content">
        <!-- Informasi Akun -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i data-feather="user"></i> Informasi Akun</h2>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" class="profile-form" id="profileForm">
                @csrf
                @method('PUT')
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" 
                               class="form-control" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" value="{{ Auth::user()->email }}" 
                               class="form-control" disabled>
                        <small class="form-text">Email tidak dapat diubah</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ Auth::user()->phone ?? '' }}"
                               class="form-control" placeholder="0812-3456-7890">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="form-control" 
                                  placeholder="Masukkan alamat lengkap">{{ Auth::user()->address ?? '' }}</textarea>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i data-feather="save"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn-secondary" onclick="resetForm()">
                        <i data-feather="refresh-cw"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistik -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i data-feather="bar-chart-2"></i> Statistik</h2>
            </div>
            
            <div class="stats-grid">
                @php
                    $totalOrders = \App\Models\Transaksi::where('user_id', auth()->id())->count();
                    $pendingOrders = \App\Models\Transaksi::where('user_id', auth()->id())
                        ->where('status', 'pending')->count();
                    $cartItems = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') ?? 0;
                @endphp
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i data-feather="shopping-bag"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $totalOrders }}</div>
                        <div class="stat-label">Total Pesanan</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i data-feather="clock"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $pendingOrders }}</div>
                        <div class="stat-label">Pesanan Pending</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i data-feather="shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $cartItems }}</div>
                        <div class="stat-label">Item di Keranjang</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i data-feather="star"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Ulasan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keamanan Akun -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i data-feather="shield"></i> Keamanan Akun</h2>
            </div>
            
            <div class="security-actions">
                <a href="{{ route('password.change') }}" class="security-btn">
                    <i data-feather="lock"></i>
                    <div>
                        <h3>Ubah Password</h3>
                        <p>Perbarui password akun Anda</p>
                    </div>
                    <i data-feather="chevron-right" class="arrow"></i>
                </a>
                
                <div class="security-btn">
                    <i data-feather="bell"></i>
                    <div>
                        <h3>Notifikasi</h3>
                        <p>Kelola preferensi notifikasi</p>
                    </div>
                    <i data-feather="chevron-right" class="arrow"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    feather.replace();
    
    // Form submission with SweetAlert
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Apakah Anda yakin ingin menyimpan perubahan profil?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#294066',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Harap tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                this.submit();
            }
        });
    });
    
    // Reset form
    function resetForm() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'Semua perubahan yang belum disimpan akan hilang',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#294066',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('profileForm').reset();
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Form telah direset',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }
    
    // Avatar change
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            Swal.fire({
                title: 'Ubah Foto Profil?',
                text: 'Foto profil akan diperbarui',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#294066',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for avatar upload
                    const formData = new FormData();
                    formData.append('avatar', this.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    // Upload avatar
                    fetch('{{ route("profile.avatar") }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Foto profil berhasil diubah',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengupload',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    });
</script>
@endsection
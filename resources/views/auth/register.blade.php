<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kedai Pesisir</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <h2>Daftar Akun Baru</h2>
                <p>Bergabung dengan Kedai Pesisir</p>
            </div>

            <!-- Form Container -->
            <div class="auth-form">
                @if(session('success'))
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="flash-message flash-error">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Nama -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   required autofocus placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   required placeholder="contoh@email.com">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <div class="input-with-icon">
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="0812-3456-7890">
                        </div>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password <span class="required">*</span></label>
                        <div class="password-container">
                            <div class="input-with-icon">
                                <input type="password" id="password" name="password" 
                                       required placeholder="Minimal 8 karakter">
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            <!-- Password Strength Meter -->
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthFill"></div>
                                </div>
                                <div class="strength-text" id="strengthText">Kekuatan password</div>
                            </div>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   required placeholder="Ulangi password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="passwordConfirmationIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <div class="input-with-icon">
                            <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        </div>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="terms-agreement">
                        <div class="terms-checkbox">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a> Kedai Pesisir.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-redirect">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-6">
            <p>&copy; 2024 Kedai Pesisir. Semua hak dilindungi.</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password Strength Checker
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = '';
            let color = '';
            let width = '0%';

            // Check length
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            // Check character types
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Determine strength level
            if (password.length === 0) {
                text = 'Kekuatan password';
                color = '#e5e7eb';
                width = '0%';
            } else if (strength <= 2) {
                text = 'Lemah';
                color = '#ef4444';
                width = '33%';
            } else if (strength <= 4) {
                text = 'Cukup';
                color = '#f59e0b';
                width = '66%';
            } else {
                text = 'Kuat';
                color = '#10b981';
                width = '100%';
            }

            // Update display
            strengthFill.style.width = width;
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms');
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan Konfirmasi Password tidak cocok!');
                document.getElementById('password_confirmation').focus();
                return false;
            }
            
            if (!terms.checked) {
                e.preventDefault();
                alert('Anda harus menyetujui Syarat & Ketentuan untuk mendaftar.');
                terms.focus();
                return false;
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                } else if (value.length <= 10) {
                    value = value.substring(0, 3) + '-' + value.substring(3, 6) + '-' + value.substring(6);
                } else {
                    value = value.substring(0, 3) + '-' + value.substring(3, 7) + '-' + value.substring(7, 11);
                }
            }
            
            e.target.value = value;
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Abon Sapi</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <!-- Left Side - Login Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-store text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Abon Sapi Premium</h2>
                    <p class="mt-2 text-gray-600">Silakan login ke akun Anda</p>
                </div>

                <!-- Login Form -->
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <!-- Flash Messages -->
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input id="email" name="email" type="email" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="email@example.com"
                                   value="{{ old('email') }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password dengan Toggle Visibility -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Masukkan password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition duration-200 cursor-pointer"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                                Ingat saya
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-blue-500 hover:text-blue-600 transition duration-200">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:-translate-y-0.5">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </div>
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-4">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-medium text-blue-500 hover:text-blue-600 transition duration-200">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Right Side - Brand Info -->
            <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-50 to-blue-100 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <i class="fas fa-utensils text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Kedai Pesisir</h3>
                </div>
                
                <div class="text-center mb-8">
                    <p class="text-gray-700 leading-relaxed">
                        Kedai pesisir Abon UmmI menghadirkan abon yang tidak hanya lezat tetapi juga bergizi. 
                        Terbuat dari bahan-bahan pilihan terbaik, kami menghadirkan cita rasa autentik 
                        dan keseimbangan nutrisi di setiap sajian.
                    </p>
                </div>
                
                <div class="mt-auto">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 text-center">Contact Information</h4>
                    <div class="space-y-2 text-center">
                        <p class="text-gray-700 flex items-center justify-center">
                            <i class="fas fa-phone text-blue-500 mr-2"></i>
                            +62 0000000000
                        </p>
                        <p class="text-gray-700 flex items-center justify-center">
                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                            alif@mail.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Info -->
        <div class="mt-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Demo Login:</h4>
            <div class="text-xs text-gray-600 space-y-1">
                <p><strong>Admin:</strong> admin@abonsapi.com / password</p>
                <p><strong>Customer:</strong> customer@example.com / password</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-6">
            <p>&copy; 2024 Abon Sapi Premium. All rights reserved.</p>
        </div>
    </div>

    <!-- JavaScript untuk Toggle Password -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Password Visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Ubah icon
                if (type === 'text') {
                    passwordIcon.classList.remove('fa-eye');
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    passwordIcon.classList.remove('fa-eye-slash');
                    passwordIcon.classList.add('fa-eye');
                }
            });

            // Form validation feedback
            const formInputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-500');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-500');
                });
            });
        });
    </script>
</body>
</html>
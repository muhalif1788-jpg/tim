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
<body>
    <div class="max-w-4xl w-full mx-4">
        <div class="flex flex-col md:flex-row login-container bg-white">
            <!-- Left Side - Login Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <div class="mx-auto w-20 h-20 logo-circle rounded-full flex items-center justify-center mb-4">
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
                        <div class="flash-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative input-container">
                            <input id="email" name="email" type="email" required 
                                   class="form-input appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none transition duration-200"
                                   placeholder="email@example.com"
                                   value="{{ old('email') }}">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative input-container">
                            <input id="password" name="password" type="password" required 
                                   class="form-input appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none transition duration-200"
                                   placeholder="Masukkan password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-lock input-icon"></i>
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
                                   class="remember-checkbox h-4 w-4 focus:ring-red-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-red-500 hover:text-red-600 transition duration-200">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="btn-primary group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-sign-in-alt text-red-300 group-hover:text-red-400 transition duration-200"></i>
                            </span>
                            Login
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="register-link font-medium">
                                Daftar di sini
                            </a>
                        </p>
                    </div>

                    <!-- Divider -->
                    <div class="divider">
                        <span class="text-sm">Atau lanjutkan dengan</span>
                    </div>

                    <!-- Social Login -->
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="social-btn w-full inline-flex justify-center py-2 px-4 border rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 focus:outline-none">
                            <i class="fab fa-google text-red-500 mr-2"></i>
                            Google
                        </button>
                        <button type="button" class="social-btn w-full inline-flex justify-center py-2 px-4 border rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 focus:outline-none">
                            <i class="fab fa-facebook text-blue-600 mr-2"></i>
                            Facebook
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Side - Brand Info -->
            <div class="w-full md:w-1/2 bg-red-50 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-md">
                        <i class="fas fa-utensils text-red-500 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Kedai Peisir</h3>
                </div>
                
                <div class="text-center mb-8">
                    <p class="text-gray-700 leading-relaxed">
                        Kedai pesisi Abon UmmI menghadirkan abon yang tidak hanya lezat tetapi juga bergizi. 
                        Terbuat dari bahan-bahan pilihan terbaik, kami menghadirkan cita rasa autentik 
                        dan keseimbangan nutrisi di setiap sajian.
                    </p>
                </div>
                
                <div class="mt-auto">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 text-center">Contact Information</h4>
                    <div class="space-y-2 text-center">
                        <p class="text-gray-700 flex items-center justify-center">
                            <i class="fas fa-phone text-red-500 mr-2"></i>
                            +62 0000000000
                        </p>
                        <p class="text-gray-700 flex items-center justify-center">
                            <i class="fas fa-envelope text-red-500 mr-2"></i>
                            alif@mail.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Info -->
        <div class="demo-info mt-6 p-4 rounded-lg">
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
    <!-- Scripts -->
    <script src="auth.js"></script>
</body>
</html>
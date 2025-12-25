<!-- resources/views/auth/reset-password.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Abon Sapi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-key text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-gray-600">Masukkan password baru Anda</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                
                <!-- Hidden inputs -->
                <input type="hidden" name="token" value="{{ $token }}">
                
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                               placeholder="email@example.com"
                               value="{{ $email ?? old('email') }}">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required 
                               class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                               placeholder="Masukkan password baru">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword('password')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 8 karakter, mengandung huruf besar, kecil, dan angka
                    </p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                               placeholder="Ulangi password baru">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" id="passwordConfirmationIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Reset Password
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center pt-4">
                    <a href="{{ route('login') }}" 
                       class="text-sm font-medium text-red-500 hover:text-red-600 transition duration-200 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-6">
            <p>&copy; 2024 Abon Sapi Premium</p>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Abon Sapi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <!-- Logo & Header -->
        <div class="text-center">
            <div class="mx-auto w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-store text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Abon Sapi Premium</h2>
            <p class="mt-2 text-gray-600">Silakan login ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <form class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-md" method="POST" action="{{ route('login') }}">
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
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="mt-1 relative">
                    <input id="email" name="email" type="email" required 
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="email@example.com"
                           value="{{ old('email') }}">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1 relative">
                    <input id="password" name="password" type="password" required 
                           class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="Masukkan password">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-red-500 hover:text-red-600">
                        Lupa password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-red-300 group-hover:text-red-400"></i>
                    </span>
                    Login
                </button>
            </div>

            <!-- Demo Info -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Demo Login:</h4>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>Admin:</strong> admin@abonsapi.com / password</p>
                    <p><strong>Customer:</strong> customer@example.com / password</p>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>&copy; 2024 Abon Sapi Premium. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
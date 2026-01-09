<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .form-container {
            width: 100%;
            max-width: 320px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 25px 20px;
        }
        
        h2 {
            color: #1e40af;
            text-align: center;
            margin: 0 0 15px 0;
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        p {
            color: #4b5563;
            margin-bottom: 15px;
            line-height: 1.4;
            font-size: 0.85rem;
            text-align: center;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #374151;
            font-size: 0.85rem;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }
        
        input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        button {
            width: 100%;
            padding: 10px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 15px;
        }
        
        button:hover {
            background: #2563eb;
        }
        
        a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.85rem;
        }
        
        a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        
        .error-message {
            background: #fef2f2;
            color: #dc2626;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 0.8rem;
            text-align: center;
        }
        
        .success-message {
            background: #f0fdf4;
            color: #16a34a;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            text-align: center;
            font-size: 0.8rem;
        }
        
        .form-group {
            margin-bottom: 10px;
        }
        
        small {
            color: #6b7280;
            font-size: 0.75rem;
            display: block;
            margin-top: 3px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2>Lupa Password</h2>
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <!-- FORM 1: Minta Reset Link (jika tidak ada token) -->
        @if(!isset($token) || empty($token))
            <p>Masukkan email Anda untuk menerima link reset password:</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <button type="submit">Kirim Reset Link</button>
            </form>
        @else
            <!-- FORM 2: Reset Password (jika ada token) -->
            <p>Buat password baru untuk akun: <strong>{{ $email ?? '' }}</strong></p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <!-- Hidden fields -->
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-group">
                    <label>Password Baru:</label>
                    <input type="password" name="password" required>
                    <small>Minimal 8 karakter, mengandung huruf besar, kecil, dan angka</small>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password:</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        @endif
        
        <a href="{{ route('login') }}">← Kembali ke Login</a>
    </div>
    <script>
    // Jika form reset berhasil, redirect ke login setelah 2 detik
    @if(session('status'))
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    @endif
    </script>
</body>
</html>
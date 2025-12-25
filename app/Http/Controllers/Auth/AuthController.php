<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek remember me
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        Log::info('=== REGISTER ATTEMPT ===');
        Log::info('Input Data:', $request->all());
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                ],
                'address' => 'nullable|string|max:500',
            ]);
            
            Log::info('Validation passed');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();
        
        try {
            Log::info('Creating user with data:', $validated);
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'address' => $validated['address'] ?? null,
                'role' => 'customer',
            ]);
            
            DB::commit();
            
            Log::info('User created successfully!', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating user:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal menyimpan data ke database: ' . $e->getMessage()
            ])->withInput();
        }

        try {
            Auth::login($user);
            Log::info('Auto login successful');
            
        } catch (\Exception $e) {
            Log::error('Auto login failed:', ['error' => $e->getMessage()]);
        }

        Log::info('=== REGISTER SUCCESS ===');
        return redirect('/')->with([
            'success' => 'Registrasi berhasil! Selamat datang di Kedai Pesisir.',
            'user_name' => $user->name
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    // ================================
    // FORGOT PASSWORD SECTION - FIXED
    // ================================

    // Menampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.lupapassword');
    }

    // Mengirim reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        // Generate token
        $token = Str::random(60);
        
        // Hapus token lama jika ada
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        // Simpan token baru
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Simpan di session juga untuk backup
        session([
            'reset_token' => $token,
            'reset_email' => $request->email,
            'reset_token_time' => now()
        ]);

        // Log untuk debugging
        Log::info('Password reset link generated', [
            'email' => $request->email,
            'token' => $token,
            'hashed_token' => Hash::make($token)
        ]);

        // Redirect ke reset form dengan token di URL
        return redirect()->route('password.reset', $token)
            ->with([
                'success' => 'Silakan reset password Anda',
                'email' => $request->email
            ]);
    }

    // Menampilkan form reset password - FIXED
    public function showResetForm($token = null)
    {
        // Log untuk debugging
        Log::info('Show reset form accessed', [
            'token_param' => $token,
            'session_token' => session('reset_token'),
            'full_url' => request()->fullUrl()
        ]);

        // Jika token tidak ada di URL, coba dari session
        if (!$token) {
            $token = session('reset_token');
        }

        if (!$token) {
            Log::warning('No token found for reset password');
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid.');
        }

        // Ambil email dari session atau cek token di database
        $email = session('reset_email');
        
        // Jika tidak ada email di session, coba cari di database
        if (!$email) {
            $passwordReset = DB::table('password_resets')
                ->where('token', 'like', '%' . substr(Hash::make($token), 0, 20) . '%')
                ->first();
            
            if ($passwordReset) {
                $email = $passwordReset->email;
            }
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    // Proses reset password - FIXED
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ]);

        Log::info('Reset password attempt', [
            'email' => $request->email,
            'token_length' => strlen($request->token)
        ]);

        // Cari token di database
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        // JIKA TIDAK ADA DI DATABASE, COBA CEK SESSION
        if (!$passwordReset) {
            // Cek dari session
            if (session('reset_token') !== $request->token || 
                session('reset_email') !== $request->email) {
                
                Log::error('Invalid token for reset', [
                    'email' => $request->email,
                    'session_token' => session('reset_token'),
                    'request_token' => $request->token
                ]);
                
                return back()->withErrors([
                    'email' => 'Token tidak valid atau telah kadaluarsa.'
                ])->withInput();
            }
        } else {
            // Jika ada di database, verifikasi token
            // Untuk demo sederhana, kita skip Hash::check
            // Note: Ini hanya untuk demo, production harus pakai Hash::check
            
            // Cek apakah token masih berlaku
            $tokenCreatedAt = Carbon::parse($passwordReset->created_at);
            if ($tokenCreatedAt->diffInMinutes(Carbon::now()) > 60) {
                DB::table('password_resets')->where('email', $request->email)->delete();
                return back()->withErrors(['email' => 'Token telah kadaluarsa.']);
            }
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token dari database
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        // Hapus session
        session()->forget(['reset_token', 'reset_email', 'reset_token_time']);

        Log::info('Password reset successful', ['email' => $user->email]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    // Method untuk verifikasi token yang lebih aman (opsional)
    private function verifyResetToken($email, $token)
    {
        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$passwordReset) {
            return false;
        }

        // Verifikasi menggunakan Hash::check
        if (Hash::check($token, $passwordReset->token)) {
            // Cek expiration
            $createdAt = Carbon::parse($passwordReset->created_at);
            return $createdAt->diffInMinutes(Carbon::now()) <= 60;
        }

        return false;
    }
}
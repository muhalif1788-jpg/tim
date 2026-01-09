<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }
            
            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'Login berhasil! Selamat datang di dashboard Anda.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/')->with([
            'success' => 'Registrasi berhasil! Selamat datang.',
            'user_name' => $user->name
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.lupapassword');
    }

    /**
     * Proses mengirim reset link (STEP 2)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan di sistem.']);
        }

        // 1. Generate token sederhana
        $token = Str::random(64);
        
        // 2. Simpan token ke database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token, // Simpan token plain (sementara)
                'created_at' => Carbon::now()
            ]
        );

        // 3. Redirect langsung ke form reset password dengan token
        return redirect()->route('password.reset', ['token' => $token])
            ->with('email', $request->email)
            ->with('success', 'Silakan buat password baru Anda.');
    }


    public function showResetForm($token = null)
    {
        // Jika tidak ada token, redirect ke form lupa password
        if (!$token) {
            return redirect()->route('password.request')
                ->with('error', 'Token tidak valid.');
        }

        // Cari email berdasarkan token
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        // Jika token tidak ditemukan atau expired (> 60 menit)
        if (!$passwordReset) {
            return redirect()->route('password.request')
                ->with('error', 'Token tidak valid atau telah kadaluarsa.');
        }

        // Cek apakah token masih valid (max 60 menit)
        $tokenTime = Carbon::parse($passwordReset->created_at);
        if ($tokenTime->diffInMinutes(Carbon::now()) > 60) {
            DB::table('password_resets')->where('token', $token)->delete();
            return redirect()->route('password.request')
                ->with('error', 'Token telah kadaluarsa. Silakan request reset password lagi.');
        }

        // Tampilkan view dengan data token dan email
        return view('auth.lupapassword', [
            'token' => $token,
            'email' => $passwordReset->email
        ]);
    }

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

        // 1. Cek token di database
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['token' => 'Token tidak valid.']);
        }

        // 2. Cek expiry token
        $tokenTime = Carbon::parse($passwordReset->created_at);
        if ($tokenTime->diffInMinutes(Carbon::now()) > 60) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['token' => 'Token telah kadaluarsa.']);
        }

        // 3. Update password user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Hapus token dari database setelah digunakan
        DB::table('password_resets')->where('email', $request->email)->delete();

        // 5. Redirect ke login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
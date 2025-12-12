<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

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

        if (Auth::attempt($credentials)) {
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

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // DEBUG: Log semua input
        Log::info('=== REGISTER ATTEMPT ===');
        Log::info('Input Data:', $request->all());
        
        try {
            // 1. Validasi input
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

        // 2. Start database transaction
        DB::beginTransaction();
        
        try {
            Log::info('Creating user with data:', $validated);
            
            // 3. Create user dengan role customer
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
            
            // DEBUG: Cek data yang masuk ke database
            $dbUser = User::find($user->id);
            Log::info('Database record:', $dbUser->toArray());
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating user:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Gagal menyimpan data ke database: ' . $e->getMessage()
            ])->withInput();
        }

        try {
            // 4. Auto login setelah registrasi
            Auth::login($user);
            Log::info('Auto login successful');
            
        } catch (\Exception $e) {
            Log::error('Auto login failed:', ['error' => $e->getMessage()]);
            // Lanjutkan saja tanpa login, user sudah terdaftar
        }

        // 5. Redirect ke home dengan pesan sukses
        Log::info('=== REGISTER SUCCESS ===');
        return redirect('/')->with([
            'success' => 'Registrasi berhasil! Selamat datang di Kedai Pesisir.',
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
}
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        
        // Hitung statistik
        $stats = [
            'total_orders' => Transaksi::where('user_id', $user->id)->count(),
            'pending_orders' => Transaksi::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'cart_items' => Cart::where('user_id', $user->id)->sum('quantity') ?? 0,
        ];
        
        return view('customer.profile.index', compact('user', 'stats'));
    }

    /**
     * Update data profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'address.max' => 'Alamat maksimal 500 karakter',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            
            return redirect()->route('customer.profile.index')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload foto profil
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        try {
            // Hapus foto lama jika ada
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            
            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile_picture = $path;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload',
                'path' => $path
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan form ubah password
     */
    public function showChangePassword()
    {
        return view('customer.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sama',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = Auth::user();
        
        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Password saat ini salah')
                ->withInput();
        }
        
        try {
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            return redirect()->route('customer.profile.index')
                ->with('success', 'Password berhasil diubah!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage())
                ->withInput();
        }
    }
}
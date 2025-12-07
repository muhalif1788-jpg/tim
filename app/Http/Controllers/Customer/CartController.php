<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan cart user yang login
    public function index()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $carts = Cart::with(['produk' => function($query) {
                        $query->with('kategori');
                    }])
                    ->where('user_id', Auth::id())
                    ->get();
        
        $total = 0;
        $total_items = 0;
        
        foreach ($carts as $cart) {
            if ($cart->produk && $cart->produk->status) {
                $total += $cart->produk->harga * $cart->quantity;
                $total_items += $cart->quantity;
            }
        }
        
        return view('customer.cart.index', compact('carts', 'total', 'total_items'));
    }

    // Tambah produk ke cart
   public function store(Request $request)
    {
    // Cek login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
    }

    $request->validate([
        'produk_id' => 'required|exists:produk,id',
        'quantity' => 'nullable|integer|min:1|max:99'
    ]);

    $produk = Produk::where('id', $request->produk_id)
                    ->where('status', true)
                    ->first();

    if (!$produk) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan atau tidak aktif!'
            ], 404);
        }
        return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak aktif!');
    }

    // Cek stok
    if ($produk->stok < ($request->quantity ?? 1)) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk tidak mencukupi! Stok tersedia: ' . $produk->stok
            ], 400);
        }
        return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
    }

    // Cek apakah produk sudah ada di cart
    $existingCart = Cart::where('user_id', Auth::id())
                        ->where('produk_id', $request->produk_id)
                        ->first();

    if ($existingCart) {
        // Update quantity
        $newQuantity = $existingCart->quantity + ($request->quantity ?? 1);
        
        if ($produk->stok < $newQuantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup untuk menambah jumlah!'
                ], 400);
            }
            return redirect()->back()->with('error', 'Stok tidak cukup!');
        }
        
        $existingCart->update(['quantity' => $newQuantity]);
        $message = 'Jumlah produk berhasil ditambahkan!';
    } else {
        // Buat cart baru
        Cart::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'quantity' => $request->quantity ?? 1
        ]);
        $message = 'Produk berhasil ditambahkan ke keranjang!';
    }

    // Hitung total cart
    $cart_count = Cart::where('user_id', Auth::id())->sum('quantity');
    
    // Jika request AJAX, kembalikan JSON
    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $cart_count,
            'total_items' => $cart_count
        ]);
    }
    
    // Jika bukan AJAX, redirect back dengan success message
    return redirect()->back()->with('success', $message);
}
    // Update quantity
    public function update(Request $request, Cart $cart)
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Pastikan cart milik user yang login
        if ($cart->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        // Cek produk
        if (!$cart->produk || !$cart->produk->status) {
            return redirect()->route('cart.index')
                           ->with('error', 'Produk tidak tersedia!');
        }

        // Cek stok
        if ($cart->produk->stok < $request->quantity) {
            return redirect()->back()
                           ->with('error', 'Stok hanya ' . $cart->produk->stok . ' pcs!');
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')
                       ->with('success', 'Jumlah berhasil diubah!');
    }

    // Hapus item dari cart
    public function destroy(Cart $cart)
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Pastikan cart milik user yang login
        if ($cart->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Akses ditolak!');
        }

        $cart->delete();

        return redirect()->route('cart.index')
                       ->with('success', 'Produk dihapus dari keranjang!');
    }

    // Kosongkan cart
    public function clear()
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('cart.index')
                       ->with('success', 'Keranjang berhasil dikosongkan!');
    }

    // Hitung item di cart (untuk badge)
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
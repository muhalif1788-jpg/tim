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
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu!'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // DEBUG: Log request untuk melihat data yang masuk
        \Log::info('Cart Store Request:', $request->all());

        $request->validate([
            'produk_id' => 'required|exists:produk,id', // PERBAIKAN: 'produks' bukan 'produk'
            'quantity' => 'required|integer|min:1|max:99' // PERBAIKAN: 'required' bukan 'nullable'
        ]);

        $produk = Produk::where('id', $request->produk_id)
                        ->where('status', true)
                        ->first();

        if (!$produk) {
            \Log::error('Produk tidak ditemukan: ' . $request->produk_id);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan atau tidak aktif!'
                ], 404);
            }
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau tidak aktif!');
        }

        // Cek stok
        $quantity = $request->quantity;
        if ($produk->stok < $quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok produk tidak mencukupi! Stok tersedia: ' . $produk->stok
                ], 400);
            }
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi! Stok tersedia: ' . $produk->stok);
        }

        // Cek apakah produk sudah ada di cart
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('produk_id', $request->produk_id)
                            ->first();

        if ($existingCart) {
            // Update quantity
            $newQuantity = $existingCart->quantity + $quantity;
            
            if ($produk->stok < $newQuantity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup untuk menambah jumlah!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Stok tidak cukup untuk menambah jumlah!');
            }
            
            $existingCart->update(['quantity' => $newQuantity]);
            $message = 'Jumlah produk berhasil ditambahkan!';
        } else {
            // Buat cart baru
            Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'quantity' => $quantity
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang!';
        }

        // Hitung total item di cart
        $cart_count = Cart::where('user_id', Auth::id())->sum('quantity');
        
        // Debug log
        \Log::info('Cart berhasil ditambahkan:', [
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'quantity' => $quantity,
            'cart_count' => $cart_count
        ]);

        // Response untuk AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cart_count,
                'total_items' => $cart_count
            ]);
        }
        
        // Redirect untuk non-AJAX
        return redirect()->back()->with('success', $message);
    }

    // Update quantity
    public function update(Request $request, Cart $cart)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if ($cart->user_id !== Auth::id()) {
            return redirect()->route('cart.index')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        if (!$cart->produk || !$cart->produk->status) {
            return redirect()->route('cart.index')
                           ->with('error', 'Produk tidak tersedia!');
        }

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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

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
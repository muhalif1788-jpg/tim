<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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
            if ($cart->produk && $cart->produk->status && $cart->produk->stok > 0) {
                $total += $cart->produk->harga * min($cart->quantity, $cart->produk->stok);
                $total_items += min($cart->quantity, $cart->produk->stok);
            }
        }
        
        return view('customer.cart.index', compact('carts', 'total', 'total_items'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu!'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'quantity' => 'required|integer|min:1'
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

        $quantity = $request->quantity;
        
        // Cek stok tersedia
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
            $newQuantity = $existingCart->quantity + $quantity;
            
            // Cek stok untuk jumlah baru
            if ($produk->stok < $newQuantity) {
                $newQuantity = $produk->stok; // Batasi dengan stok maksimum
            }
            
            $existingCart->update(['quantity' => $newQuantity]);
            $message = 'Jumlah produk berhasil ditambahkan!';
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'quantity' => $quantity
            ]);
            $message = 'Produk berhasil ditambahkan ke keranjang!';
        }

        $cart_count = Cart::where('user_id', Auth::id())->sum('quantity');
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $cart_count,
                'total_items' => $cart_count
            ]);
        }
        
        return redirect()->back()->with('success', $message);
    }

    public function update(Request $request, Cart $cart)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu!'
            ], 401);
        }

        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak!'
            ], 403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Periksa stok produk
        $stokTersedia = $cart->produk->stok;
        
        if ($request->quantity > $stokTersedia) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok hanya tersedia ' . $stokTersedia . ' pcs.'
            ], 400);
        }

        // Update quantity
        $cart->update(['quantity' => $request->quantity]);

        // Hitung ulang total cart
        $carts = Cart::with('produk')
                    ->where('user_id', Auth::id())
                    ->get();
        
        $total = 0;
        $total_items = 0;
        
        foreach ($carts as $cartItem) {
            if ($cartItem->produk && $cartItem->produk->status && $cartItem->produk->stok > 0) {
                $total += $cartItem->produk->harga * $cartItem->quantity;
                $total_items += $cartItem->quantity;
            }
        }
        
        // Hitung shipping dan discount
        $shipping = $total > 100000 ? 0 : 15000;
        $discount = $total > 150000 ? $total * 0.1 : 0;
        $grand_total = $total + $shipping - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui',
            'quantity' => $cart->quantity,
            'cart_count' => $total_items,
            'summary' => [
                'subtotal' => $total,
                'shipping' => $shipping,
                'discount' => $discount,
                'grand_total' => $grand_total
            ]
        ]);
    }

    public function destroy(Request $request, Cart $cart)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu!'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if ($cart->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak!'
                ], 403);
            }
            return redirect()->route('cart.index')->with('error', 'Akses ditolak!');
        }

        $cart_id = $cart->id;
        $cart->delete();

        // Hitung ulang setelah delete
        $carts = Cart::where('user_id', Auth::id())->get();
        $cart_count = $carts->sum('quantity');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang!',
                'cart_id' => $cart_id,
                'cart_count' => $cart_count
            ]);
        }

        return redirect()->route('cart.index')
                       ->with('success', 'Produk dihapus dari keranjang!');
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
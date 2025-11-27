<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);
        $total = 0;
        
        foreach($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Produk::find($request->product_id);
        $cart = session()->get('cart', []);

        // Cek jika produk sudah ada di cart
        if(isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->product_id] = [
                "name" => $product->nama,
                "quantity" => $request->quantity,
                "price" => $product->harga,
                "image" => $product->gambar
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Keranjang updated!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan!');
    }
}
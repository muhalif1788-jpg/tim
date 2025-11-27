<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 produk unggulan (produk aktif terbaru)
        $featuredProducts = Produk::with('kategori') // eager loading relation
                                ->latest() // urutkan dari yang terbaru
                                ->take(6)  // ambil 6 produk
                                ->get();
        
        // Jika belum ada produk, berikan array kosong
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = collect([]); // empty collection
        }

        return view('customer.home', compact('featuredProducts'));
    }
}
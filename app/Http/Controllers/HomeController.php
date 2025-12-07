<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 3 produk aktif terbaru
        // Gunakan where('status', true) karena field di database adalah 'status', bukan 'is_active'
        $produk = Produk::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Return view 'home' (bukan 'app')
        return view('app', compact('produk'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil data rekomendasi
        $recommendations = Produk::getRecommendations(4);
        
        // Ambil semua produk - Ubah nama variabel dari $products menjadi $produk
        $produk = Produk::where('status', true)->get(); 
    
        // Kirim ke view dengan nama 'produk'
        return view('app', compact('recommendations', 'produk'));
    }
}
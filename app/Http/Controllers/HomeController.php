<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $produk = Produk::where('status', true)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    
        return view('app', compact('produk'));
    }
}
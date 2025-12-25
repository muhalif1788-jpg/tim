<?php
// app/Http\Controllers\Customer\DashboardController.php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Cart;
use App\Models\Transaksi; // GANTI Order dengan Transaksi
use Illuminate\Support\Facades\Auth;

class DashboardCustomer extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user_id = Auth::id();
        
        // Data dasar dengan Transaksi (bukan Order)
        $data = [
            'cartCount' => $this->getCartCount($user_id),
            'transactionCount' => $this->getActiveTransactionCount($user_id), // Ganti nama
            'productCount' => $this->getAvailableProductCount(),
            'featuredProducts' => $this->getFeaturedProducts(),
            'recentTransactions' => $this->getRecentTransactions($user_id), // Ganti nama
        ];
        
        return view('customer.dashboard', $data);
    }
    
    // Helper methods - UPDATE
    private function getCartCount($user_id)
    {
        return Cart::where('user_id', $user_id)->sum('quantity');
    }
    
    private function getActiveTransactionCount($user_id)
    {
        return Transaksi::where('user_id', $user_id)
            ->whereIn('status', ['pending', 'processing', 'paid']) // Sesuaikan dengan status di Transaksi
            ->count();
    }
    
    private function getAvailableProductCount()
    {
        return Produk::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->count();
    }
    
    private function getFeaturedProducts()
    {
        return Produk::where('status', 'aktif')
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
    }
    
    private function getRecentTransactions($user_id)
    {
        return Transaksi::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }
}
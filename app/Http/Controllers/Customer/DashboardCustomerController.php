<?php
// app/Http\Controllers\Customer\DashboardController.php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Cart;
use App\Models\Transaksi; 
use Illuminate\Support\Facades\Auth;

class DashboardCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $recommendations = Produk::getRecommendations(4);
        $user_id = Auth::id();
        
        // Data dasar dengan Transaksi
        $data = [
            'recommendations' => $recommendations,
            'cartCount' => $this->getCartCount($user_id),
            'transactionCount' => $this->getActiveTransactionCount($user_id), 
            'productCount' => $this->getAvailableProductCount(),
            'featuredProducts' => $this->getFeaturedProducts(),
            'recentTransactions' => $this->getRecentTransactions($user_id), 
        ];
        
        // Debug: Cek data produk
        // dd($data['featuredProducts']); // Uncomment untuk debug
        
        return view('customer.dashboard', $data);
    }
    
    // Helper methods - FIXED untuk boolean status
    private function getCartCount($user_id)
    {
        return Cart::where('user_id', $user_id)->sum('quantity');
    }
    
    private function getActiveTransactionCount($user_id)
    {
        return Transaksi::where('user_id', $user_id)
            ->whereIn('status', ['pending', 'processing', 'paid']) 
            ->count();
    }
    
    private function getAvailableProductCount()
    {
        return Produk::where('status', true) // Boolean true, bukan string 'aktif'
            ->where('stok', '>', 0)
            ->count();
    }
    
    private function getFeaturedProducts()
    {
        return Produk::active()
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
    }
    
    private function getRecentTransactions($user_id)
    {
        return Transaksi::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }
}
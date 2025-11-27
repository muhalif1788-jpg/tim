<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk statistik cards
        $totalProduk = Produk::count();
        $totalKategori = Kategori::count();
        $totalTransaksi = Transaksi::count();
        $totalUser = User::count();

        // Total pendapatan - tanpa status
        $totalPendapatan = Transaksi::sum('total_harga');
        
        // Pendapatan bulan ini
        $pendapatanBulanIni = Transaksi::whereMonth('created_at', Carbon::now()->month)
            ->sum('total_harga');
            
        // Pendapatan bulan lalu
        $pendapatanBulanLalu = Transaksi::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->sum('total_harga');

        $growthPendapatan = $pendapatanBulanLalu > 0 
            ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100 
            : 0;

        // Pesanan Terbaru
        $pesananTerbaru = Transaksi::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Untuk produk terpopuler, kita perlu relasi yang sesuai
        $produkPopuler = Produk::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalTransaksi', 
            'totalUser',
            'totalPendapatan',
            'pendapatanBulanIni',
            'growthPendapatan',
            'pesananTerbaru',
            'produkPopuler'
        ));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        // Statistik utama
        $stats = [
            'total' => Transaksi::count(),
            'pending' => Transaksi::where('status', 'pending')->count(),
            'success' => Transaksi::where('status', 'success')->count(),
            'failed' => Transaksi::where('status', 'failed')->count(),
            'today' => Transaksi::whereDate('created_at', today())->count(),
            'revenue' => Transaksi::where('status', 'success')->sum('total_harga'),
        ];
        
        // Statistik per status
        $statusStats = Transaksi::select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        // Transaksi terbaru
        $recentTransactions = Transaksi::with('user')
            ->latest()
            ->limit(5)
            ->get();
            
        // Statistik harian (7 hari terakhir)
        $dailyStats = Transaksi::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(CASE WHEN status = "success" THEN total_harga ELSE 0 END) as revenue')
            )
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Jumlah user
        $userCount = User::where('role', 'customer')->count();
        
        return view('admin.dashboard', compact(
            'stats', 
            'statusStats', 
            'recentTransactions',
            'dailyStats',
            'userCount'
        ));
    }
    
    /**
     * Menampilkan semua transaksi (INDEX)
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['user'])
            ->latest();
        
        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('nama_penerima', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->paginate(10);
        
        // Hitung statistik untuk filter
        $totalTransactions = Transaksi::count();
        $totalRevenue = Transaksi::where('status', 'success')->sum('total_harga');
        $pendingCount = Transaksi::where('status', 'pending')->count();
        
        return view('admin.transactions.index', compact(
            'transactions', 
            'totalTransactions',
            'totalRevenue',
            'pendingCount'
        ));
    }
    
    /**
     * Menampilkan detail transaksi (SHOW)
     */
    public function show($id)
    {
        $transaction = Transaksi::with(['user', 'details.produk'])
            ->findOrFail($id);
            
        return view('admin.transactions.show', compact('transaction'));
    }
    
    /**
     * Update status transaksi
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $transaction = Transaksi::findOrFail($id);
        
        DB::transaction(function () use ($transaction, $request) {
            $oldStatus = $transaction->status;
            $newStatus = $request->status;
            
            $transaction->update([
                'status' => $newStatus,
                'admin_notes' => $request->notes,
                'updated_by' => auth()->id()
            ]);
            
            // Log perubahan status (buat model TransactionLog jika perlu)
            // \App\Models\TransactionLog::create([...])
            
            // Update stock jika status berubah jadi success
            if ($newStatus == 'success' && $oldStatus != 'success') {
                foreach ($transaction->details as $detail) {
                    if ($detail->produk) {
                        $detail->produk->decrement('stok', $detail->jumlah);
                    }
                }
            }
        });
        
        return back()->with('success', 'Status transaksi berhasil diperbarui!');
    }
}
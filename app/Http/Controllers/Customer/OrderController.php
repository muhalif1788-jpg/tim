<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan semua riwayat transaksi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Transaksi::where('user_id', $user->id)
            ->with(['details.produk'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Search berdasarkan order_id
        if ($request->has('search')) {
            $query->where('order_id', 'like', '%' . $request->search . '%');
        }
        
        $transactions = $query->paginate(10);
        
        // Hitung total per status
        $statusCounts = [
            'all' => Transaksi::where('user_id', $user->id)->count(),
            'pending' => Transaksi::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'processing' => Transaksi::where('user_id', $user->id)
                ->where('status', 'processing')->count(),
            'shipped' => Transaksi::where('user_id', $user->id)
                ->where('status', 'shipped')->count(),
            'completed' => Transaksi::where('user_id', $user->id)
                ->where('status', 'completed')->count(),
            'cancelled' => Transaksi::where('user_id', $user->id)
                ->where('status', 'cancelled')->count(),
        ];
        
        return view('customer.orders.index', compact('transactions', 'statusCounts'));
    }

    /**
     * Menampilkan detail transaksi
     */
    public function show($id)
    {
        $transaction = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['details.produk', 'user'])
            ->firstOrFail();
        
        return view('customer.orders.show', compact('transaction'));
    }

    /**
     * Menampilkan invoice
     */
    public function invoice($id)
    {
        $transaction = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['details.produk', 'user'])
            ->firstOrFail();
        
        return view('customer.orders.invoice', compact('transaction'));
    }

    /**
     * Batalkan pesanan
     */
    public function cancel(Request $request, $id)
    {
        $transaction = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending') // Hanya bisa batalkan yang pending
            ->firstOrFail();
        
        try {
            $transaction->update([
                'status' => 'cancelled',
                'expired_at' => now(),
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan'
                ]);
            }
            
            return redirect()->route('customer.orders.index')
                ->with('success', 'Pesanan berhasil dibatalkan');
                
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Lacak pengiriman
     */
    public function track($id)
    {
        $transaction = Transaksi::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'shipped') // Hanya yang sudah dikirim
            ->firstOrFail();
        
        // Di sini bisa integrasi dengan API kurir
        // Untuk sekarang, tampilkan info dasar
        
        $trackingInfo = [
            'order_id' => $transaction->order_id,
            'status' => 'Dalam Pengiriman',
            'estimated_delivery' => $transaction->delivered_at?->format('d M Y') ?? '-',
            'shipping_address' => $transaction->alamat_pengiriman,
            'recipient' => $transaction->nama_penerima,
            'phone' => $transaction->telepon_penerima,
        ];
        
        return view('customer.orders.track', compact('transaction', 'trackingInfo'));
    }
}
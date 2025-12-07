<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout
     */
    public function index()
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Ambil data cart dengan produk
        $carts = Cart::with(['produk' => function($query) {
                        $query->where('status', true);
                    }])
                    ->where('user_id', Auth::id())
                    ->get()
                    ->filter(function($cart) {
                        return $cart->produk !== null; // Hanya produk yang masih ada
                    });

        // Validasi cart
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Keranjang belanja Anda kosong!');
        }

        // Cek stok
        foreach ($carts as $cart) {
            if ($cart->produk->stok < $cart->quantity) {
                return redirect()->route('cart.index')
                               ->with('error', 
                                   'Stok ' . $cart->produk->nama_produk . ' tidak mencukupi! 
                                    Stok tersedia: ' . $cart->produk->stok);
            }
        }

        // Hitung total
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->produk->harga * $cart->quantity;
        }

        // Biaya (contoh)
        $biaya_pengiriman = 10000; // Rp 10,000
        $biaya_admin = 2000;       // Rp 2,000
        $total = $subtotal + $biaya_pengiriman + $biaya_admin;

        return view('customer.checkout.index', compact(
            'carts', 
            'subtotal', 
            'biaya_pengiriman', 
            'biaya_admin', 
            'total'
        ));
    }

    /**
     * Proses checkout
     */
    public function store(Request $request)
    {
        // Cek login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Validasi
        $request->validate([
            'nama_penerima' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Mulai transaction untuk konsistensi data
        DB::beginTransaction();

        try {
            // Ambil cart user
            $carts = Cart::with('produk')
                        ->where('user_id', Auth::id())
                        ->get();

            // Validasi cart kosong
            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')
                               ->with('error', 'Keranjang belanja kosong!');
            }

            // Validasi stok dan hitung total
            $subtotal = 0;
            $items = [];
            
            foreach ($carts as $cart) {
                // Cek produk masih ada
                if (!$cart->produk) {
                    throw new \Exception('Produk ' . $cart->produk_id . ' tidak ditemukan!');
                }

                // Cek stok
                if ($cart->produk->stok < $cart->quantity) {
                    throw new \Exception(
                        'Stok ' . $cart->produk->nama_produk . ' tidak mencukupi! 
                         Stok tersedia: ' . $cart->produk->stok . ', 
                         Yang dibutuhkan: ' . $cart->quantity
                    );
                }

                // Hitung subtotal
                $subtotal += $cart->produk->harga * $cart->quantity;

                // Simpan item untuk ditampilkan
                $items[] = [
                    'nama' => $cart->produk->nama_produk,
                    'quantity' => $cart->quantity,
                    'harga' => $cart->produk->harga,
                    'subtotal' => $cart->produk->harga * $cart->quantity,
                ];

                // Kurangi stok produk
                $cart->produk->decrement('stok', $cart->quantity);
            }

            // Hitung total dengan biaya tambahan
            $biaya_pengiriman = 10000;
            $biaya_admin = 2000;
            $total = $subtotal + $biaya_pengiriman + $biaya_admin;

            // 📝 DISINI: Jika nanti mau simpan ke database Order
            // $order = Order::create([
            //     'user_id' => Auth::id(),
            //     'nama_penerima' => $request->nama_penerima,
            //     'alamat' => $request->alamat,
            //     'no_telepon' => $request->no_telepon,
            //     'catatan' => $request->catatan,
            //     'subtotal' => $subtotal,
            //     'biaya_pengiriman' => $biaya_pengiriman,
            //     'biaya_admin' => $biaya_admin,
            //     'total' => $total,
            //     'status' => 'pending',
            // ]);

            // Kosongkan cart
            Cart::where('user_id', Auth::id())->delete();

            // Commit transaction
            DB::commit();

            // Data untuk ditampilkan di invoice
            $invoiceData = [
                'nomor' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'tanggal' => now()->format('d/m/Y H:i'),
                'nama_penerima' => $request->nama_penerima,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
                'catatan' => $request->catatan,
                'items' => $items,
                'subtotal' => $subtotal,
                'biaya_pengiriman' => $biaya_pengiriman,
                'biaya_admin' => $biaya_admin,
                'total' => $total,
            ];

            // Tampilkan invoice
            return view('customer.checkout.invoice', compact('invoiceData'))
                   ->with('success', 'Checkout berhasil! Terima kasih telah berbelanja.');

        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();
            
            return redirect()->route('checkout.index')
                           ->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan invoice setelah checkout
     */
    public function invoice($invoiceNumber)
    {
        // Ini contoh, nanti bisa diisi dengan data dari database
        return view('customer.checkout.invoice', [
            'invoiceNumber' => $invoiceNumber,
            'message' => 'Fitur invoice lengkap akan tersedia setelah ada model Order.'
        ]);
    }
}
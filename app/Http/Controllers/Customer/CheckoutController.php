<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $carts = Cart::with('produk')
            ->where('user_id', Auth::id())
            ->get()
            ->filter(fn ($cart) => $cart->produk);

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $subtotal = $carts->sum(fn ($cart) => $cart->produk->harga * $cart->quantity);
        $biaya_pengiriman = 10000;
        $biaya_admin = 2000;
        $total = $subtotal + $biaya_pengiriman + $biaya_admin;

        return view('customer.checkout.index', compact(
            'carts', 'subtotal', 'biaya_pengiriman', 'biaya_admin', 'total'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'nullable',
            'alamat' => 'nullable',
            'no_telepon' => 'nullable',
            'catatan' => 'nullable',
        ]);

        $user = Auth::user();
        $carts = Cart::with('produk')->where('user_id', $user->id)->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $subtotal = $carts->sum(fn ($cart) => $cart->produk->harga * $cart->quantity);
        $biaya_pengiriman = 10000;
        $biaya_admin = 2000;
        $total = $subtotal + $biaya_pengiriman + $biaya_admin;

        // Simpan data keranjang ke session sebagai backup
        $cartBackup = $carts->map(function ($cart) {
            return [
                'id' => $cart->id,
                'produk_id' => $cart->produk_id,
                'quantity' => $cart->quantity,
            ];
        })->toArray();

        session([
            'checkout_data' => [
                'nama' => $request->nama_penerima ?? $user->name,
                'alamat' => $request->alamat ?? $user->alamat ?? '',
                'telepon' => $request->no_telepon ?? $user->telepon ?? '',
                'catatan' => $request->catatan,
                'subtotal' => $subtotal,
                'biaya_pengiriman' => $biaya_pengiriman,
                'biaya_admin' => $biaya_admin,
                'total' => $total,
                'email' => $user->email,
            ],
            'cart_items' => $carts->map(function ($cart) {
                return [
                    'produk_id' => $cart->produk_id,
                    'produk_nama' => $cart->produk->nama ?? 'Unknown Product',
                    'harga' => $cart->produk->harga ?? 0,
                    'quantity' => $cart->quantity,
                    'subtotal' => ($cart->produk->harga ?? 0) * $cart->quantity,
                ];
            })->toArray(),
            'cart_backup' => $cartBackup // Simpan backup keranjang
        ]);

        return redirect()->route('customer.checkout.payment');
    }

    public function payment()
    {
        if (!session()->has('checkout_data') || !session()->has('cart_items')) {
            return redirect()->route('customer.checkout.index')->with('error', 'Data checkout tidak lengkap.');
        }

        $checkout = session('checkout_data');
        $user = Auth::user();

        try {
            // 1. Generate Order ID
            $orderId = 'ORDER-' . date('YmdHis') . '-' . rand(1000, 9999);
            
            // 2. Save to database
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'subtotal' => $checkout['subtotal'],
                'biaya_pengiriman' => $checkout['biaya_pengiriman'],
                'biaya_admin' => $checkout['biaya_admin'],
                'total_harga' => $checkout['total'],
                'nama_penerima' => $checkout['nama'],
                'telepon_penerima' => $checkout['telepon'],
                'alamat_pengiriman' => $checkout['alamat'],
                'catatan' => $checkout['catatan'],
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            // Simpan detail transaksi
            $cartItems = session('cart_items', []);
            
            DB::beginTransaction();
            try {
                foreach ($cartItems as $item) {
                    DetailTransaksi::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $item['produk_id'],
                        'harga_saat_ini' => $item['harga'],
                        'jumlah' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception('Gagal menyimpan detail transaksi: ' . $e->getMessage());
            }

            // 3. Prepare Midtrans parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $checkout['total'],
                ],
                'customer_details' => [
                    'first_name' => $checkout['nama'],
                    'email' => $user->email,
                    'phone' => $checkout['telepon'],
                ],
                'callbacks' => [
                    'finish' => url("/checkout/finish/{$orderId}"),
                    'error' => url('/checkout/error'),
                ]
            ];

            // 4. Get Server Key from .env
            $serverKey = env('MIDTRANS_SERVER_KEY');
            
            if (empty($serverKey)) {
                throw new \Exception('Server Key tidak ditemukan di .env');
            }

            // 5. Generate Snap Token
            $authString = base64_encode($serverKey . ':');
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization: Basic ' . $authString
                ],
                CURLOPT_SSL_VERIFYPEER => false,
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 201) {
                throw new \Exception("Midtrans API Error: HTTP {$httpCode} - " . $response);
            }
            
            $result = json_decode($response);
            $snapToken = $result->token;
            
            // 6. Update transaction with token
            $transaksi->update(['snap_token' => $snapToken]);
            
            // 7. TIDAK ADA PENGHAPUSAN KERANJANG DI SINI!
            // Keranjang akan dihapus nanti di method finish() jika pembayaran sukses
            
            // 8. Show payment page
            return view('customer.checkout.payment', [
                'snapToken' => $snapToken,
                'orderId' => $orderId,
                'transaksi' => $transaksi,
                'clientKey' => env('MIDTRANS_CLIENT_KEY')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    public function finish(Request $request, $orderId)
    {
        // Pastikan ada data JSON dari Midtrans
        if (!$request->has('json') || empty($request->json)) {
            // Jika tidak ada data JSON, mungkin user membatalkan manual
            $this->restoreCartFromBackup();
            return redirect()->route('cart.index')
                ->with('error', 'Pembayaran dibatalkan. Produk telah dikembalikan ke keranjang.');
        }
        
        // Parse callback dari Midtrans
        $json = json_decode($request->json);
        
        if (!$json || !isset($json->transaction_status)) {
            // Data JSON tidak valid
            $this->restoreCartFromBackup();
            return redirect()->route('cart.index')
                ->with('error', 'Data pembayaran tidak valid. Produk telah dikembalikan ke keranjang.');
        }
        
        $transaksi = Transaksi::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Update status transaksi berdasarkan callback
        if ($json->transaction_status == 'capture' || $json->transaction_status == 'settlement') {
            // Pembayaran sukses
            $transaksi->update([
                'status' => 'success',
                'payment_type' => $json->payment_type ?? null,
                'transaction_id' => $json->transaction_id ?? null,
                'transaction_time' => $json->transaction_time ?? null,
                'fraud_status' => $json->fraud_status ?? null
            ]);
            
            // HAPUS KERANJANG HANYA JIKA PEMBAYARAN SUKSES
            Cart::where('user_id', Auth::id())->delete();
            
            // Hapus session checkout
            session()->forget(['checkout_data', 'cart_items', 'cart_backup']);
            
            return redirect()->route('customer.checkout.invoice', ['orderId' => $orderId])
                ->with('success', 'Pembayaran berhasil!');
                
        } elseif ($json->transaction_status == 'pending') {
            // Pembayaran pending
            $transaksi->update([
                'status' => 'pending',
                'payment_type' => $json->payment_type ?? null
            ]);
            
            return redirect()->route('customer.checkout.pending')
                ->with('info', 'Pembayaran masih dalam proses.');
                
        } elseif ($json->transaction_status == 'cancel' || $json->transaction_status == 'expire') {
            // Pembayaran dibatalkan atau expired
            $transaksi->update([
                'status' => 'failed',
                'payment_type' => $json->payment_type ?? null,
                'transaction_id' => $json->transaction_id ?? null,
            ]);
            
            // Kembalikan keranjang dari backup
            $this->restoreCartFromBackup();
            
            return redirect()->route('cart.index')
                ->with('error', 'Pembayaran dibatalkan atau telah kedaluwarsa. Produk telah dikembalikan ke keranjang.');
                
        } else {
            // Pembayaran gagal (deny, failure)
            $transaksi->update([
                'status' => 'failed',
                'payment_type' => $json->payment_type ?? null,
                'transaction_id' => $json->transaction_id ?? null,
            ]);
            
            // Kembalikan keranjang dari backup
            $this->restoreCartFromBackup();
            
            return redirect()->route('cart.index')
                ->with('error', 'Pembayaran gagal. Produk telah dikembalikan ke keranjang.');
        }
    }

    // Method untuk mengembalikan keranjang dari backup
    private function restoreCartFromBackup()
    {
        $user = Auth::user();
        
        // Cek apakah user sudah login
        if (!$user) {
            return;
        }
        
        // Cek apakah keranjang sudah kosong
        $existingCartCount = Cart::where('user_id', $user->id)->count();
        
        // Jika keranjang kosong dan ada backup, restore
        if ($existingCartCount == 0 && session()->has('cart_backup')) {
            $cartBackup = session('cart_backup');
            
            foreach ($cartBackup as $item) {
                // Cek apakah produk sudah ada di keranjang
                $existingItem = Cart::where('user_id', $user->id)
                    ->where('produk_id', $item['produk_id'])
                    ->first();
                
                if (!$existingItem) {
                    Cart::create([
                        'user_id' => $user->id,
                        'produk_id' => $item['produk_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }
        }
        
        // Hapus session backup
        session()->forget(['checkout_data', 'cart_items', 'cart_backup']);
    }

    public function pending()
    {
        return view('customer.checkout.pending')
            ->with('info', 'Menunggu konfirmasi pembayaran.');
    }

    public function invoice($orderId)
    {
        $transaksi = Transaksi::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->with(['details.produk'])
            ->firstOrFail();
        
        return view('customer.checkout.invoice', compact('transaksi'));
    }

    public function error()
    {
        // Restore keranjang jika ada di halaman error
        $this->restoreCartFromBackup();
        
        return view('customer.checkout.error')
            ->with('error', 'Terjadi kesalahan saat pembayaran. Produk telah dikembalikan ke keranjang.');
    }
}
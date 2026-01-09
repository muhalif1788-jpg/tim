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
        
        // Default awal tampilan (Delivery)
        $biaya_pengiriman = 10000; 
        $biaya_admin = 2000;
        $total = $subtotal + $biaya_pengiriman + $biaya_admin;

        return view('customer.checkout.index', compact(
            'carts', 'subtotal', 'biaya_pengiriman', 'biaya_admin', 'total'
        ));
    }

    /**
     * Memproses Input Form Checkout ke Session
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'metode_pengiriman' => 'required|in:delivery,pickup',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $carts = Cart::with('produk')->where('user_id', $user->id)->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $subtotal = $carts->sum(fn ($cart) => $cart->produk->harga * $cart->quantity);
        
        // Logika Biaya Pengiriman
        $biaya_pengiriman = ($request->metode_pengiriman === 'delivery') ? 10000 : 0;
        $biaya_admin = 2000;
        $total = $subtotal + $biaya_pengiriman + $biaya_admin;

        // Backup data keranjang untuk keperluan restore jika bayar gagal
        $cartBackup = $carts->map(fn($cart) => [
            'id' => $cart->id,
            'produk_id' => $cart->produk_id,
            'quantity' => $cart->quantity,
        ])->toArray();

        session([
            'checkout_data' => [
                'nama' => $request->nama_penerima,
                'alamat' => $request->alamat,
                'telepon' => $request->no_telepon,
                'metode_pengiriman' => $request->metode_pengiriman,
                'catatan' => $request->catatan,
                'subtotal' => $subtotal,
                'biaya_pengiriman' => $biaya_pengiriman,
                'biaya_admin' => $biaya_admin,
                'total' => $total,
                'email' => $user->email,
            ],
            'cart_items' => $carts->map(fn($cart) => [
                'produk_id' => $cart->produk_id,
                'produk_nama' => $cart->produk->nama_produk, // PERBAIKAN: ganti dari 'nama' ke 'nama_produk'
                'harga' => $cart->produk->harga,
                'quantity' => $cart->quantity,
                'subtotal' => $cart->produk->harga * $cart->quantity,
            ])->toArray(),
            'cart_backup' => $cartBackup
        ]);

        return redirect()->route('customer.checkout.payment');
    }

    /**
     * Membuat Transaksi di DB & Mendapatkan Snap Token Midtrans
     */
    public function payment()
    {
        if (!session()->has('checkout_data')) {
            return redirect()->route('customer.checkout.index')->with('error', 'Sesi kedaluwarsa.');
        }

        $checkout = session('checkout_data');
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $orderId = 'ABON-' . date('YmdHis') . '-' . rand(1000, 9999);
            
            // 1. Simpan Transaksi Ke Database
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'subtotal' => $checkout['subtotal'],
                'biaya_pengiriman' => $checkout['biaya_pengiriman'],
                'biaya_admin' => $checkout['biaya_admin'],
                'total_harga' => $checkout['total'],
                'metode_pengiriman' => $checkout['metode_pengiriman'],
                'nama_penerima' => $checkout['nama'],
                'telepon_penerima' => $checkout['telepon'],
                'alamat_pengiriman' => $checkout['alamat'],
                'catatan' => $checkout['catatan'],
                'status' => 'pending',
                'expired_at' => now()->addHours(24),
            ]);

            // 2. Simpan Detail Produk
            foreach (session('cart_items') as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'harga_saat_ini' => $item['harga'],
                    'jumlah' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // 3. Siapkan Item Details untuk Midtrans (PERBAIKAN DI SINI)
            $item_details = [];
            $calculated_total = 0; // Untuk memastikan perhitungan benar

            foreach (session('cart_items') as $item) {
                // PERBAIKAN 1: Pastikan nama tidak null/kosong
                $product_name = !empty($item['produk_nama']) ? $item['produk_nama'] : 'Produk ' . $item['produk_id'];
                
                $item_detail = [
                    'id' => (string)$item['produk_id'],
                    'price' => (int)$item['harga'],
                    'quantity' => (int)$item['quantity'],
                    'name' => substr($product_name, 0, 50), // Max 50 karakter
                ];
                
                $item_details[] = $item_detail;
                $calculated_total += ($item['harga'] * $item['quantity']);
            }

            // PERBAIKAN 2: Pastikan biaya pengiriman dan admin dimasukkan sebagai item
            if ($checkout['biaya_pengiriman'] > 0) {
                $item_details[] = [
                    'id' => 'SHIPPING',
                    'price' => (int)$checkout['biaya_pengiriman'],
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman',
                ];
                $calculated_total += $checkout['biaya_pengiriman'];
            }

            $item_details[] = [
                'id' => 'ADMIN',
                'price' => (int)$checkout['biaya_admin'],
                'quantity' => 1,
                'name' => 'Biaya Layanan',
            ];
            $calculated_total += $checkout['biaya_admin'];

            // PERBAIKAN 3: Pastikan gross_amount sama dengan jumlah item_details
            // Gunakan calculated_total untuk memastikan kesamaan
            $gross_amount = $calculated_total;

            // Debug log untuk memeriksa perhitungan
            Log::info('Midtrans Calculation', [
                'checkout_total' => $checkout['total'],
                'calculated_total' => $calculated_total,
                'gross_amount' => $gross_amount,
                'item_count' => count($item_details)
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $gross_amount, // PERBAIKAN: gunakan calculated total
                ],
                'item_details' => $item_details,
                'customer_details' => [
                    'first_name' => $checkout['nama'],
                    'email' => $user->email,
                    'phone' => $checkout['telepon'],
                    'shipping_address' => [
                        'first_name' => $checkout['nama'],
                        'phone' => $checkout['telepon'],
                        'address' => $checkout['alamat'],
                        'city' => 'Unknown',
                        'postal_code' => '00000',
                        'country_code' => 'IDN',
                    ],
                ],
                'callbacks' => [
                    'finish' => url("/checkout/finish/{$orderId}"),
                    'error' => url('/checkout/error'),
                ]
            ];

            // 4. Request Snap Token ke Midtrans
            $serverKey = env('MIDTRANS_SERVER_KEY');
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
            $result = json_decode($response);
            curl_close($ch);

            if (!isset($result->token)) {
                // Log error response dari Midtrans
                Log::error('Midtrans Error Response: ' . $response);
                throw new \Exception("Midtrans Error: " . $response);
            }

            $transaksi->update(['snap_token' => $result->token]);
            
            DB::commit();

            return view('customer.checkout.payment', [
                'snapToken' => $result->token,
                'orderId' => $orderId,
                'transaksi' => $transaksi,
                'clientKey' => env('MIDTRANS_CLIENT_KEY')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Callback setelah dari Midtrans
     */
    public function finish(Request $request, $orderId)
    {
        $transaksi = Transaksi::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$request->has('json')) {
            $this->restoreCartFromBackup();
            return redirect()->route('cart.index')->with('error', 'Pembayaran dibatalkan.');
        }

        $json = json_decode($request->json);
        
        // Cek status sukses
        if (isset($json->transaction_status) && in_array($json->transaction_status, ['capture', 'settlement'])) {
            $transaksi->update([
                'status' => 'success',
                'payment_type' => $json->payment_type ?? null,
                'transaction_id' => $json->transaction_id ?? null,
                'paid_at' => now(),
            ]);

            // Sukses = Hapus Keranjang & Session
            Cart::where('user_id', Auth::id())->delete();
            session()->forget(['checkout_data', 'cart_items', 'cart_backup']);

            return redirect()->route('customer.checkout.invoice', $orderId)->with('success', 'Pembayaran Berhasil!');
        }

        // Jika statusnya pending (misal bayar via Alfamart/VA belum dibayar)
        if (isset($json->transaction_status) && $json->transaction_status == 'pending') {
            Cart::where('user_id', Auth::id())->delete(); // Tetap hapus keranjang karena sudah jadi pesanan
            session()->forget(['checkout_data', 'cart_items', 'cart_backup']);
            return redirect()->route('customer.history')->with('info', 'Silakan selesaikan pembayaran Anda.');
        }

        // Gagal/Batal
        $this->restoreCartFromBackup();
        return redirect()->route('cart.index')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    /**
     * Restore Keranjang Belanja jika Batal
     */
    private function restoreCartFromBackup()
    {
        $user = Auth::user();
        if ($user && session()->has('cart_backup')) {
            foreach (session('cart_backup') as $item) {
                Cart::updateOrCreate(
                    ['user_id' => $user->id, 'produk_id' => $item['produk_id']],
                    ['quantity' => $item['quantity']]
                );
            }
        }
        session()->forget(['checkout_data', 'cart_items', 'cart_backup']);
    }

    public function invoice($orderId)
    {
        $transaksi = Transaksi::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->with(['details.produk'])
            ->firstOrFail();
        
        return view('customer.checkout.invoice', compact('transaksi'));
    }
}
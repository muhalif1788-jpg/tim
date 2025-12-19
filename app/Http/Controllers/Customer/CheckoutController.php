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
            // ⚠️ PASTIKAN semua data ada
            return [
                'produk_id' => $cart->produk_id,
                'produk_nama' => $cart->produk->nama ?? 'Unknown Product',
                'harga' => $cart->produk->harga ?? 0,
                'quantity' => $cart->quantity,
                'subtotal' => ($cart->produk->harga ?? 0) * $cart->quantity,
                'produk_data' => $cart->produk, // Simpan objek lengkap jika perlu
            ];
        })->toArray()
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

        // ⚠️ FIX: SIMPAN DETAIL PRODUK
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

        // 5. Generate Snap Token using CURL
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
        
        // 7. Clear cart
        Cart::where('user_id', $user->id)->delete();
        session()->forget(['checkout_data', 'cart_items']);
        
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

    public function finish($orderId)
    {
        $transaksi = Transaksi::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('customer.checkout.finish', compact('transaksi'));
    }

    public function error()
    {
        return view('customer.checkout.error')->with('error', 'Terjadi kesalahan saat pembayaran.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // ✅ IMPORT YANG BENAR
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with('details.produk')->orderByDesc('id')->get(); // ✅ orderByDesc('id_transaksi')
        $produks = Produk::all();

        return view('admin.transaksi.index', compact('transaksi', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
            'produk_id.*' => 'exists:produk,id',
            'jumlah.*' => 'integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            $total_harga = 0;
            $detailData = [];

            foreach ($request->produk_id as $key => $produk_id) {
                $produk = Produk::findOrFail($produk_id);
                $jumlah = $request->jumlah[$key];
                $subtotal = $produk->harga * $jumlah;
                $total_harga += $subtotal;

                $detailData[] = [
                    'id_produk' => $produk_id,
                    'jumlah' => $jumlah,
                    'sub_total' => $subtotal
                ];
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'nama_pembeli' => $request->nama_pembeli,
                'tanggal' => $request->tanggal,
                'total_harga' => $total_harga
            ]);

            // Simpan detail transaksi & update stok
            foreach ($detailData as $detail) {
                DetailTransaksi::create([
                    'id' => $transaksi->id_transaksi,
                    'id_produk' => $detail['id_produk'],
                    'jumlah' => $detail['jumlah'],
                    'sub_total' => $detail['sub_total']
                ]);

                Produk::find($detail['id_produk'])->decrement('stok', $detail['jumlah']);
            }
        });

        return redirect()->route('admin.transaksi.index') // ✅ admin.transaksi.index
            ->with('success', 'Transaksi berhasil disimpan!');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('details.produk')->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $transaksi = Transaksi::with('details.produk')->findOrFail($id);

            // Kembalikan stok
            foreach ($transaksi->details as $detail) {
                $detail->produk->increment('stok', $detail->jumlah);
            }

            $transaksi->details()->delete();
            $transaksi->delete();
        });

        return redirect()->route('admin.transaksi.index') // ✅ admin.transaksi.index
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
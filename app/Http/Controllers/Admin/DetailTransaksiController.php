<?php
namespace App\Http\Controllers\Admin;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DetailTransaksiController extends Controller
{
    public function store(Request $request, $id_transaksi)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request, $id_transaksi) {

            $produk = Produk::findOrFail($request->id_produk);

            $sub_total = $produk->harga * $request->jumlah;

            DetailTransaksi::create([
                'id_transaksi' => $id_transaksi,
                'id_produk' => $request->id_produk,
                'jumlah' => $request->jumlah,
                'sub_total' => $sub_total,
            ]);

            // Kurangi stok
            $produk->stok -= $request->jumlah;
            $produk->save();

            // Update total transaksi
            $transaksi = Transaksi::findOrFail($id_transaksi);
            $transaksi->total_harga = $transaksi->details()->sum('sub_total');
            $transaksi->save();
        });

        return redirect()->route('transaksi.show', $id_transaksi)
                         ->with('success', 'Produk berhasil ditambahkan ke transaksi');
    }


    public function update(Request $request, $id_detail)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $id_detail) {

            $detail = DetailTransaksi::findOrFail($id_detail);
            $produk = $detail->produk;

            $jumlah_lama = $detail->jumlah;
            $jumlah_baru = $request->jumlah;
            $selisih = $jumlah_baru - $jumlah_lama;

            // Update stok
            $produk->stok -= $selisih;
            $produk->save();

            // Update detail
            $detail->jumlah = $jumlah_baru;
            $detail->sub_total = $produk->harga * $jumlah_baru;
            $detail->save();

            // Update total transaksi
            $transaksi = $detail->transaksi;
            $transaksi->total_harga = $transaksi->details()->sum('sub_total');
            $transaksi->save();
        });

        $transaksi = DetailTransaksi::find($id_detail)->transaksi;

        return redirect()->route('transaksi.show', $transaksi->id_transaksi)
                         ->with('success', 'Jumlah produk berhasil diperbarui.');
    }


    public function destroy($id_detail)
    {
        $id_transaksi = null; // supaya bisa dipakai di luar transaction

        DB::transaction(function () use ($id_detail, &$id_transaksi) {

            $detail = DetailTransaksi::findOrFail($id_detail);
            $id_transaksi = $detail->id_transaksi;

            // Kembalikan stok
            $produk = $detail->produk;
            $produk->stok += $detail->jumlah;
            $produk->save();

            // Hapus detail
            $detail->delete();

            // Update total transaksi
            $transaksi = Transaksi::findOrFail($id_transaksi);
            $transaksi->total_harga = $transaksi->details()->sum('sub_total');
            $transaksi->save();
        });

        return redirect()->route('transaksi.show', $id_transaksi)
                         ->with('success', 'Detail transaksi berhasil dihapus.');
    }
}

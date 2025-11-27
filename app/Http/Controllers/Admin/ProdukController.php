<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProdukController extends Controller
{
    public function index()
    {
        // Ambil semua produk beserta kategori (relasi)
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('admin.produk.index', compact('produks','kategoris'));
    }

    public function create()
    {
        // Ambil kategori untuk dropdown form tambah
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        // Ambil semua kategori untuk dropdown form edit
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori',
        ]);

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}

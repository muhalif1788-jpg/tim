<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        // Ambil semua produk (termasuk non-aktif & stok 0)
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('admin.produk.index', compact('produks','kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris')); // ✅ Fixed: 'kategoris'
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'kategori_id' => 'required|exists:kategori,id', // ✅ Fixed: 'kategori_id'
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ]);

        // Handle gambar upload
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'satuan' => $request->satuan,
            'kategori_id' => $request->kategori_id, // ✅ Fixed: 'kategori_id'
            'gambar' => $gambarPath,
            'status' => $request->boolean('status')
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'required|integer|min:0',
            'satuan' => 'required|string',
            'kategori_id' => 'required|exists:kategori,id', // ✅ Fixed: 'kategori_id'
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'satuan' => $request->satuan,
            'kategori_id' => $request->kategori_id, // ✅ Fixed: 'kategori_id'
            'status' => $request->boolean('status')
        ];

        // Handle gambar update
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        // Delete image if exists
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
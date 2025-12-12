<?php

namespace App\Http\Controllers\Admin;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori_id = $request->input('kategori_id');
        $status = $request->input('status');
        
        $produks = Produk::with('kategori')
                    ->when($search, function($query) use ($search) {
                        return $query->where('nama_produk', 'like', "%{$search}%")
                                    ->orWhere('deskripsi', 'like', "%{$search}%");
                    })
                    ->when($kategori_id, function($query) use ($kategori_id) {
                        return $query->where('kategori_id', $kategori_id);
                    })
                    ->when($status !== null, function($query) use ($status) {
                        return $query->where('status', $status);
                    })
                    ->latest()
                    ->paginate(10);
        
        $kategoris = Kategori::all();
        
        return view('admin.produk.index', compact('produks', 'kategoris', 'search', 'kategori_id', 'status'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'satuan' => 'nullable|string|max:20'
        ]);

        $data = $request->only([
            'nama_produk', 'kategori_id', 'deskripsi', 
            'harga', 'stok', 'berat', 'status', 'satuan'
        ]);

        // Default value untuk status
        $data['status'] = $request->has('status') ? true : false;

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            $data['gambar'] = $gambarPath;
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Produk $produk)
    {
        return view('admin.produk.show', compact('produk'));
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
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'berat' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'satuan' => 'nullable|string|max:20'
        ]);

        $data = $request->only([
            'nama_produk', 'kategori_id', 'deskripsi',
            'harga', 'stok', 'berat', 'satuan'
        ]);

        // Status
        $data['status'] = $request->has('status') ? true : false;

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            
            $gambarPath = $request->file('gambar')->store('produk', 'public');
            $data['gambar'] = $gambarPath;
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        // Hapus gambar jika ada
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
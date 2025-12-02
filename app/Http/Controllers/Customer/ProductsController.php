<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        // Hanya produk yang status aktif dan stok tersedia
        $query = Produk::with('kategori')
            ->where('status', true)
            ->where('stok', '>', 0);

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'harga_terendah':
                $query->orderBy('harga', 'asc');
                break;
            case 'harga_tertinggi':
                $query->orderBy('harga', 'desc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $produk = $query->paginate(12);
        $kategoris = Kategori::all();

        return view('customer.products', compact('produk', 'kategoris'));
    }

    /**
     * Display single product detail untuk customer
     */
    public function show($id)
    {
        $produk = Produk::with('kategori')
            ->where('status', true)
            ->where('stok', '>', 0)
            ->findOrFail($id);

        // Produk terkait (dari kategori yang sama)
        $relatedProducts = Produk::with('kategori')
            ->where('status', true)
            ->where('stok', '>', 0)
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return view('customer.products', compact('produk', 'relatedProducts'));
    }

    /**
     * Search produk untuk customer
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $produk = Produk::with('kategori')
            ->where('status', true)
            ->where('stok', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('nama_produk', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%");
            })
            ->paginate(12);

        $kategoris = Kategori::all();

        return view('customer.products', compact('produk', 'kategoris', 'query'));
    }
}
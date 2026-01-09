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
        $recommendations = Produk::getRecommendations(4);
        // Hanya produk yang status aktif dan stok tersedia
        $query = Produk::with('kategori', 'penilaian')
            ->where('status', true)
            ->where('stok', '>', 0);

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Search - PERBAIKAN: Tambahkan parameter search ke query string
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

        return view('products.index', compact('produk', 'kategoris', 'recommendations'));
    }

    /**
     * Display single product detail untuk customer
     */
    public function show($id)
    {
        $produk = Produk::with('kategori', 'penilaian.user')
            ->where('status', true)
            ->where('stok', '>', 0)
            ->findOrFail($id);

        // Produk terkait (dari kategori yang sama)
        $relatedProducts = Produk::with('kategori', 'penilaian')
            ->where('status', true)
            ->where('stok', '>', 0)
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return view('products.show', compact('produk', 'relatedProducts'));
    }


    public function search(Request $request)
    {
        // Redirect ke index dengan parameter search
        return redirect()->route('customer.products.index', [
            'search' => $request->get('q')
        ]);
    }
}
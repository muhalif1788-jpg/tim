<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = Auth::id();

        $sudahBeli = Transaksi::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereHas('details', function ($query) use ($request) {
                $query->where('produk_id', $request->produk_id);
            })->exists();

        if (!$sudahBeli) {
            return redirect()->back()->with('error', 'Anda harus membeli produk ini terlebih dahulu sebelum memberikan penilaian.');
        }

        $sudahRating = Penilaian::where('user_id', $userId)
            ->where('produk_id', $request->produk_id)
            ->exists();

        if ($sudahRating) {
            return redirect()->back()->with('error', 'Anda sudah memberikan penilaian untuk produk ini.');
        }

        Penilaian::create([
            'produk_id' => $request->produk_id,
            'user_id' => $userId,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Penilaian Anda sangat berharga.');
    }

    public function destroy($id)
    {
        $penilaian = Penilaian::findOrFail($id);

        if (Auth::id() !== $penilaian->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }

        $penilaian->delete();
        return redirect()->back()->with('success', 'Penilaian berhasil dihapus.');
    }
}
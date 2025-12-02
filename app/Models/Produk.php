<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id';
    public $timestamps = true;

    // ✅ SEMUA FIELD BARU
    protected $fillable = [
        'nama_produk',
        'deskripsi',      // ✅ BARU
        'gambar',         // ✅ BARU  
        'harga',
        'stok',
        'berat',          // ✅ BARU
        'status',         // ✅ BARU
        'satuan',         // ✅ BARU
        'kategori_id'  
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'berat' => 'integer',  // ✅ BARU
        'status' => 'boolean'  // ✅ BARU
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // ✅ ACCESSOR BARU
    public function getDeskripsiPendekAttribute()
    {
        if (empty($this->deskripsi)) {
            return 'Deskripsi tidak tersedia';
        }
        return strlen($this->deskripsi) > 100 
            ? substr($this->deskripsi, 0, 100) . '...' 
            : $this->deskripsi;
    }

    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/produk/' . $this->gambar);
        }
        return asset('images/default-product.jpg');
    }

    public function getBeratKgAttribute()
    {
        return $this->berat / 1000;
    }

    public function getHargaFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getIsAvailableAttribute()
    {
        return $this->status && $this->stok > 0;
    }
}
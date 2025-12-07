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

    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'deskripsi',
        'gambar',
        'harga',
        'stok',
        'berat',
        'status',
        'satuan'
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'berat' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Accessor untuk harga format Rupiah
    public function getHargaRpAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Nonaktif';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->status) {
            return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>';
        }
        return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>';
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope untuk produk berdasarkan kategori
    public function scopeByKategori($query, $kategori_id)
    {
        return $query->where('kategori_id', $kategori_id);
    }

    public function carts()
    {
    return $this->hasMany(Cart::class, 'id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id'; // sesuai migration
    public $timestamps = true;

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'kategori_id'  
    ];

    // Relasi: setiap produk punya satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }
}

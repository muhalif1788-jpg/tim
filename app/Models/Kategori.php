<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori'; 

    protected $primaryKey = 'id';   // ini sesuai migration → $table->id();
    public $timestamps = true;      // migration pakai timestamps()

    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'id');
    }
}

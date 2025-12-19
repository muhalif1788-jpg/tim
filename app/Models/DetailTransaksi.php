<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi'; // ⚠️ Nama tabel singular
    
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'harga_saat_ini',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga_saat_ini' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
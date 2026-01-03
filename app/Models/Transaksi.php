<?php
// app/Models/Transaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    
    protected $fillable = [
        'user_id',
        'order_id',
        'snap_token',
        'payment_type',
        'bank',
        'va_number',
        'transaction_id',
        'subtotal',
        'biaya_pengiriman',
        'biaya_admin',
        'total_harga',
        'nama_penerima',
        'telepon_penerima',
        'alamat_pengiriman',
        'catatan',
        'status',
        'paid_at',
        'expired_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'biaya_pengiriman' => 'decimal:2',
        'biaya_admin' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'delivered_at' => 'datetime',
        'nama_penerima' => 'encrypted',
        'telepon_penerima' => 'encrypted',
        'alamat_pengiriman' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

  
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
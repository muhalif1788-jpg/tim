<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produk;


class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'produk_id',
        'quantity'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // Accessor untuk subtotal (harga * quantity)
    public function getSubtotalAttribute()
    {
        if ($this->produk) {
            return $this->produk->harga * $this->quantity;
        }
        return 0;
    }

    // Accessor untuk subtotal format Rupiah
    public function getSubtotalRpAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    // Scope untuk cart user tertentu
    public function scopeByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    // Scope untuk produk tertentu
    public function scopeByProduk($query, $produk_id)
    {
        return $query->where('produk_id', $produk_id);
    }
}
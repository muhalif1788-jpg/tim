<?php
// app/Models/Transaksi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi'; // Specify table name

    protected $fillable = [
        'user_id',
        'total_harga'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Jika ada relasi dengan items transaksi
    //public function items()
    //{
        // Sesuaikan dengan struktur Anda
        //return $this->hasMany(TransaksiItem::class);
    //}
}
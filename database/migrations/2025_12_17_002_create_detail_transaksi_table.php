<?php
// database/migrations/xxxx_create_detail_transaksi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id');
            $table->unsignedBigInteger('produk_id');
            
            // Hanya simpan data yang bisa berubah
            $table->decimal('harga_saat_ini', 15, 2); // harga produk saat checkout
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            
            // Indexes
            $table->index('transaksi_id');
            $table->index('produk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
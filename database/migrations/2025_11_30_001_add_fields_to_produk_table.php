<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // ✅ 5 FIELD SEKALIGUS
            $table->text('deskripsi')->nullable()->after('nama_produk');
            $table->string('gambar')->nullable()->after('deskripsi');
            $table->integer('berat')->default(0)->after('stok');
            $table->boolean('status')->default(true)->after('berat');
            $table->string('satuan')->default('pcs')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn([
                'deskripsi', 
                'gambar', 
                'berat', 
                'status', 
                'satuan'
            ]);
        });
    }
};
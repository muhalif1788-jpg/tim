<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeskripsiToKategoriTable extends Migration
{
    public function up()
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama_kategori');
        });
    }

    public function down()
    {
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
}
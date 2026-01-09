<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->string('metode_pengiriman')->default('delivery')->after('total_harga');

            if (!Schema::hasColumn('transaksi', 'biaya_pengiriman')) {
                $table->decimal('biaya_pengiriman', 12, 2)->default(0)->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('metode_pengiriman');
            
        });
    }
};
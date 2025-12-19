<?php
// database/migrations/2025_12_17_create_transaksi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke user (customer)
            $table->unsignedBigInteger('user_id');
            
            // MIDTRANS FIELDS (WAJIB)
            $table->string('order_id')->unique(); // ORDER-20251217-xxxx
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable(); // bank_transfer, qris, etc
            $table->string('bank')->nullable(); // bca, bni, mandiri
            $table->string('va_number')->nullable(); // virtual account
            $table->string('transaction_id')->nullable(); // dari Midtrans
            
            // ORDER DETAILS
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('biaya_pengiriman', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            
            // CUSTOMER INFO (bisa dari user atau input manual)
            $table->string('nama_penerima');
            $table->string('telepon_penerima');
            $table->text('alamat_pengiriman');
            $table->text('catatan')->nullable();
            
            // STATUS
            $table->enum('status', [
                'pending', 'processing', 'success', 'failed', 
                'expired', 'canceled', 'refunded'
            ])->default('pending');
            
            // TIMESTAMPS
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            // FOREIGN KEYS
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // INDEXES
            $table->index('order_id');
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            // Kolom ini boleh kosong (nullable) karena transaksi beli biasa tidak punya link ini
            $table->foreignId('transaksi_keluar_id')->nullable()->constrained('transaksi_keluar')->onDelete('set null')->after('rab_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->dropForeign(['transaksi_keluar_id']);
            $table->dropColumn('transaksi_keluar_id');
        });
    }
};

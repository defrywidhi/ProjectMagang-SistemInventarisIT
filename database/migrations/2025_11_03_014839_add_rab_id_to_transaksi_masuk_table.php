<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            // Tambahkan kolom rab_id setelah supplier_id
            // Boleh kosong (nullable) karena tidak semua barang masuk berasal dari RAB
            // Constrained ke tabel 'rabs'
            // onDelete('set null') artinya jika RAB induknya dihapus, set rab_id di sini jadi null (tapi data transaksi tetap ada)
            $table->foreignId('rab_id')->nullable()->constrained('rabs')->onDelete('set null')->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            // Ini adalah kebalikan dari up(), untuk rollback
            $table->dropForeign(['rab_id']);
            $table->dropColumn('rab_id');
        });
    }
};

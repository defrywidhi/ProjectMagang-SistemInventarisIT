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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_it_id')->constrained('barang_it');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->integer('jumlah_masuk');
            $table->date('tanggal_masuk');
            $table->bigInteger('harga_satuan')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk');
    }
};

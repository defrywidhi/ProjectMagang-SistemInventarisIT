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
        Schema::create('barang_it', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris');
            $table->string('nama_barang');
            $table->string('merk')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->enum('kondisi', ['Baru','Bekas','Rusak'])->default('Baru');
            $table->string('lokasi_penyimpanan')->nullable();
            $table->string('gambar_barang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_it');
    }
};

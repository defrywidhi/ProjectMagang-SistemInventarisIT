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
        Schema::create('rab_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rabs')->onDelete('cascade');
            $table->foreignId('barang_it_id')->nullable()->constrained('barang_it')->onDelete('cascade'); 
            $table->string('nama_barang_custom')->nullable();
            $table->string('foto_custom')->nullable();
            $table->string('nama_barang_diajukan');
            $table->integer('jumlah');
            $table->decimal('perkiraan_harga_satuan', 15, 2);
            $table->decimal('ongkir', 15, 2)->default(0);
            $table->decimal('asuransi', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_details');
    }
};

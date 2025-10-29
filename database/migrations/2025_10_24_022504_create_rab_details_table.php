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
            $table->string('nama_barang_diajukan');
            $table->integer('jumlah')->unsigned()->default(1);
            $table->bigInteger('perkiraan_harga_satuan')->default(0);
            $table->bigInteger('ongkir')->default(0);
            $table->bigInteger('asuransi')->default(0);
            $table->bigInteger('total_harga')->default(0);
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

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
        Schema::create('stok_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_opname_id')->constrained('stok_opnames')->onDelete('cascade');
            $table->foreignId('barang_it_id')->constrained('barang_it');
            $table->integer('stok_sistem')->unsigned()->default(0);
            $table->integer('stok_fisik')->unsigned()->default(0);
            $table->integer('selisih')->signed()->default(0);
            $table->text('keterangan_item')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opname_details');
    }
};

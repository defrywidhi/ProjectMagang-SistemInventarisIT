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
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rab')->unique();
            $table->string('judul');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->date('tanggal_dibuat');
            $table->date('tanggal_disetujui')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('catatan_approval')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};

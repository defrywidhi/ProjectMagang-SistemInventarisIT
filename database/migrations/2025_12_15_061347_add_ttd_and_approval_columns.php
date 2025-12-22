<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom TTD di tabel USERS
        Schema::table('users', function (Blueprint $table) {
            $table->string('ttd')->nullable()->after('password'); // Path file gambar
        });

        // 2. Update tabel RABS untuk Multi-Level Approval
        Schema::table('rabs', function (Blueprint $table) {
            // Hapus kolom lama yang single approval (biar bersih)
            // Pastikan backup data dulu kalau ini aplikasi live
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['approved_by', 'tanggal_disetujui']);

            // Tambah kolom Approval Manager
            $table->foreignId('manager_id')->nullable()->after('catatan_approval')->constrained('users');
            $table->dateTime('manager_at')->nullable()->after('manager_id');

            // Tambah kolom Approval Direktur
            $table->foreignId('direktur_id')->nullable()->after('manager_at')->constrained('users');
            $table->dateTime('direktur_at')->nullable()->after('direktur_id');
        });

        // 3. Ubah ENUM Status (Draft, Menunggu Manager, Menunggu Direktur, Disetujui, Ditolak)
        // Karena mengubah ENUM di MySQL agak ribet, kita pakai Raw Query biar aman.
        // PENTING: Sesuaikan nama tabel 'rabs' dan kolom 'status' jika beda.
        DB::statement("ALTER TABLE rabs MODIFY COLUMN status ENUM('Draft', 'Menunggu Manager', 'Menunggu Direktur', 'Disetujui', 'Ditolak') DEFAULT 'Draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ttd');
        });

        Schema::table('rabs', function (Blueprint $table) {
            // Kembalikan ke settingan lama (opsional, sesuaikan kebutuhan rollback)
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['direktur_id']);
            $table->dropColumn(['manager_id', 'manager_at', 'direktur_id', 'direktur_at']);
        });
    }
};
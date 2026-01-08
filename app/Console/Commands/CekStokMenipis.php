<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BarangIT; // Butuh ini
use Illuminate\Support\Facades\Mail; // Butuh ini untuk kirim email

class CekStokMenipis extends Command
{
    /**
     * "Nama Panggilan" untuk perintah ini.
     */
    protected $signature = 'app:cek-stok-menipis'; // Kita beri nama ini

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Mengecek barang IT yang stoknya di bawah minimum dan mengirim notifikasi email';

    /**
     * "Otak" yang akan dieksekusi.
     */
    public function handle()
    {
        $this->info('Mulai mengecek stok menipis...'); // Pesan di terminal

        // 1. Cari semua barang yang stoknya kritis
        // Kita juga panggil relasi 'kategori'-nya
        $barangKritis = BarangIT::with('kategori')
                            ->whereColumn('stok', '<', 'stok_minimum')
                            ->get();

        // 2. Cek apakah ada barang kritis
        if ($barangKritis->isNotEmpty()) {

            $this->warn('Ditemukan ' . $barangKritis->count() . ' barang dengan stok kritis. Mengirim email...');

            // 3. Kirim email (Untuk sekarang, kita kirim ke email admin. Nanti kita siapkan .env)
            $emailTujuan = 'admin@rumahsakit.com'; // Nanti ganti dengan emailmu untuk tes
            $dataEmail = ['barangs' => $barangKritis];

            Mail::send('emails.stok-menipis', $dataEmail, function($message) use ($emailTujuan) {
                $message->to($emailTujuan)
                        ->subject('PERINGATAN: Stok Barang Menipis - Sistem Inventory');
            });

            $this->info('Email peringatan berhasil dikirim ke ' . $emailTujuan);

        } else {
            $this->info('Stok aman. Tidak ada email yang dikirim.');
        }

        return 0;
    }
}
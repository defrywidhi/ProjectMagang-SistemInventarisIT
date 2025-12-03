<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ReminderStokOpname extends Command
{
    /**
     * Nama panggilan perintah.
     */
    protected $signature = 'app:reminder-stok-opname';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Mengirim email pengingat jadwal stok opname bulanan';

    /**
     * Eksekusi perintah.
     */
    public function handle()
    {
        $this->info('Mengirim reminder stok opname...');

        // 1. Cari siapa yang harus dikirimi email (Auditor & Manajer)
        // Kita pakai jurus Spatie: User::role([...])
        // Kirim ke Auditor (pemeriksa utama) dan Admin (backup/pelaksana)
        $penerima = User::role(['auditor', 'admin'])->get();

        if ($penerima->isEmpty()) {
            $this->info('Tidak ada user Auditor atau Manajer. Skip.');
            return 0;
        }

        // 2. Kirim email ke setiap orang
        foreach ($penerima as $user) {
            Mail::send('emails.reminder-stok-opname', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('PENGINGAT: Jadwal Stok Opname Bulanan');
            });

            $this->info("Email terkirim ke: {$user->email}");
            sleep(2);
        }

        $this->info('Selesai.');
        return 0;
    }
}

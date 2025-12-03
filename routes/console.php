<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


/**
 * Mendaftarkan jadwal (scheduler) aplikasi.
 */
Schedule::command('app:cek-stok-menipis')->dailyAt('01:00');
// ---- TAMBAHKAN BARIS INI ----
// Jalankan setiap bulan, pada tanggal 1, jam 09:00 pagi
Schedule::command('app:reminder-stok-opname')->monthlyOn(1, '09:00');
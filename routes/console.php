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
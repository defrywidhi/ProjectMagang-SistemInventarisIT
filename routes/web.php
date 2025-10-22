<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangITController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;



Route::resource('kategori', KategoriController::class)->middleware('auth');
Route::resource('supplier', SupplierController::class)->middleware('auth');
Route::resource('barang', BarangITController::class)->middleware('auth');
Route::resource('transaksi-masuk', TransaksiMasukController::class)->middleware('auth');
Route::resource('transaksi-keluar', TransaksiKeluarController::class)->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

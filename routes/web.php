<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangITController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\DashboardController;
use App\Models\RabDetail;
use App\Http\Controllers\StokOpnameController;

Route::resource('kategori', KategoriController::class)->middleware('auth');
Route::resource('supplier', SupplierController::class)->middleware('auth');
Route::resource('barang', BarangITController::class)->middleware('auth');
Route::resource('transaksi-masuk', TransaksiMasukController::class)->middleware('auth');
Route::resource('transaksi-keluar', TransaksiKeluarController::class)->middleware('auth');
Route::resource('rab', RabController::class)->middleware('auth');
Route::post('/rab/{rab}/details', [RabController::class, 'storeDetail'])->name('rab.details.store')->middleware('auth');
Route::delete('/rab/details/{rab_detail}', [RabController::class, 'destroyDetail'])->name('rab.details.destroy')->middleware('auth');
Route::get('/rab/details/{rab_detail}/edit', [RabController::class, 'editDetail'])->name('rab.details.edit')->middleware('auth');
Route::put('/rab/details/{rab_detail}', [RabController::class, 'updateDetail'])->name('rab.details.update')->middleware('auth');
Route::post('/rab/{rab}/ajukan', [RabController::class, 'ajukanApproval'])->name('rab.ajukan')->middleware('auth');
Route::post('/rab/{rab}/approve', [RabController::class, 'approveRAB'])->name('rab.approve')->middleware('auth');
Route::post('/rab/{rab}/reject', [RabController::class, 'rejectRAB'])->name('rab.reject')->middleware('auth');
Route::get('/rab/{rab}/get-details', [RabController::class, 'getRabDetailsJson'])->name('rab.getDetailsJson')->middleware('auth');
Route::get('/stok-opname', [StokOpnameController::class, 'index'])->name('stok-opname.index')->middleware('auth');
Route::get('/stok-opname/create', [StokOpnameController::class, 'create'])->name('stok-opname.create')->middleware('auth');
Route::post('/stok-opname', [StokOpnameController::class, 'store'])->name('stok-opname.store')->middleware('auth');
Route::get('/stok-opname/{stokOpname}', [StokOpnameController::class, 'show'])->name('stok-opname.show')->middleware('auth');
Route::get('/stok-opname/{stokOpname}/edit', [StokOpnameController::class, 'edit'])->name('stok-opname.edit')->middleware('auth');
Route::put('/stok-opname/{stokOpname}', [StokOpnameController::class, 'update'])->name('stok-opname.update')->middleware('auth');
Route::delete('/stok-opname/{stokOpname}', [StokOpnameController::class, 'destroy'])->name('stok-opname.destroy')->middleware('auth');
Route::put('/stok-opname/{stokOpname}/save-details', [StokOpnameController::class, 'saveDetails'])->name('stok-opname.saveDetails')->middleware('auth');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
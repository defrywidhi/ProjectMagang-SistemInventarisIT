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
use App\Http\Controllers\UserController;

Route::resource('kategori', KategoriController::class)->middleware(['auth', 'role:admin']);
Route::resource('supplier', SupplierController::class)->middleware(['auth', 'role:admin']);
Route::resource('barang', BarangITController::class)->middleware(['auth', 'role:admin']);


Route::resource('transaksi-masuk', TransaksiMasukController::class)->middleware(['auth', 'role:admin']);


Route::resource('transaksi-keluar', TransaksiKeluarController::class)->middleware(['auth', 'role:admin|teknisi']);


Route::resource('rab', RabController::class)->middleware(['auth', 'role:admin|manager']);

Route::post('/rab/{rab}/ajukan', [RabController::class, 'ajukanApproval'])->name('rab.ajukan')->middleware(['auth', 'role:admin|manager']);

Route::post('/rab/{rab}/approve', [RabController::class, 'approveRAB'])->name('rab.approve')->middleware(['auth', 'role:manager']);

Route::post('/rab/{rab}/reject', [RabController::class, 'rejectRAB'])->name('rab.reject')->middleware(['auth', 'role:manager']);

Route::get('/rab/{rab}/get-details', [RabController::class, 'getRabDetailsJson'])->name('rab.getDetailsJson')->middleware(['auth', 'role:admin|manager']);

Route::delete('/rab/details/{rab_detail}', [RabController::class, 'destroyDetail'])->name('rab.details.destroy')->middleware(['auth', 'role:admin|manager']);

Route::get('/rab/details/{rab_detail}/edit', [RabController::class, 'editDetail'])->name('rab.details.edit')->middleware(['auth', 'role:admin|manager']);

Route::put('/rab/details/{rab_detail}', [RabController::class, 'updateDetail'])->name('rab.details.update')->middleware(['auth', 'role:admin|manager']);

Route::post('/rab/{rab}/details', [RabController::class, 'storeDetail'])->name('rab.details.store')->middleware(['auth', 'role:admin|manager']);

Route::get('/stok-opname', [StokOpnameController::class, 'index'])->name('stok-opname.index')->middleware(['auth', 'role:admin|auditor']);

Route::get('/stok-opname/create', [StokOpnameController::class, 'create'])->name('stok-opname.create')->middleware(['auth', 'role:admin|auditor']);

Route::post('/stok-opname', [StokOpnameController::class, 'store'])->name('stok-opname.store')->middleware(['auth', 'role:admin|auditor']);

Route::get('/stok-opname/{stokOpname}', [StokOpnameController::class, 'show'])->name('stok-opname.show')->middleware(['auth', 'role:admin|auditor']);

Route::get('/stok-opname/{stokOpname}/edit', [StokOpnameController::class, 'edit'])->name('stok-opname.edit')->middleware(['auth', 'role:admin|auditor']);

Route::put('/stok-opname/{stokOpname}', [StokOpnameController::class, 'update'])->name('stok-opname.update')->middleware(['auth', 'role:admin|auditor']);

Route::delete('/stok-opname/{stokOpname}', [StokOpnameController::class, 'destroy'])->name('stok-opname.destroy')->middleware(['auth', 'role:admin|auditor']);

Route::put('/stok-opname/{stokOpname}/save-details', [StokOpnameController::class, 'saveDetails'])->name('stok-opname.saveDetails')->middleware(['auth', 'role:admin|auditor']);

Route::resource('users', UserController::class)->middleware(['auth', 'role:admin']);


// Rute untuk export DATA
Route::get('/rab/{rab}/cetak-pdf', [RabController::class, 'cetakPDF'])->name('rab.cetakPDF')->middleware('auth');

Route::get('/barang/export/excel', [BarangITController::class, 'exportExcel'])->name('barang.exportExcel')->middleware(['auth', 'role:admin|manager|auditor']);

Route::get('/transaksi-masuk/export/excel', [TransaksiMasukController::class, 'exportExcel'])->name('transaksi-masuk.exportExcel')->middleware(['auth', 'role:admin|manager|auditor']);

// Rute untuk Cetak Nota Transaksi Masuk
Route::get('/transaksi-masuk/{transaksi_masuk}/cetak', [TransaksiMasukController::class, 'cetakInvoice'])->name('transaksi-masuk.cetakInvoice')->middleware('auth');

// Rute Cetak Bukti Keluar
Route::get('/transaksi-keluar/{transaksi_keluar}/cetak', [TransaksiKeluarController::class, 'cetakBuktiKeluar'])->name('transaksi-keluar.cetakBuktiKeluar')->middleware('auth');

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

require __DIR__ . '/auth.php';

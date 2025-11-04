<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT; // Butuh ini
use App\Models\Rab; // Butuh ini
use App\Models\TransaksiMasuk; // Butuh ini
use Illuminate\Support\Facades\DB; // Butuh ini untuk query canggih

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Aset Barang (jenis barang unik)
        $totalBarang = BarangIT::count();

        // 2. Stok Kritis (barang yg stoknya < stok_minimum)
        $stokKritis = BarangIT::whereColumn('stok', '<', 'stok_minimum')->count();

        // 3. RAB Menunggu Persetujuan
        $rabMenunggu = Rab::where('status', 'Menunggu Approval')->count();

        // 4. Total Transaksi Masuk Bulan Ini
        $transaksiBulanIni = TransaksiMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->sum('jumlah_masuk');

        // 5. Kirim semua data ini ke view dashboard
        return view('dashboard', compact(
            'totalBarang',
            'stokKritis',
            'rabMenunggu',
            'transaksiBulanIni'
        ));
    }
}

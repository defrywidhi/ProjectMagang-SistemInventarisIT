<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT; // Butuh ini
use App\Models\Rab; // Butuh ini
use App\Models\TransaksiMasuk; // Butuh ini
use Illuminate\Support\Facades\DB; // Butuh ini untuk query canggih
use App\Models\Kategori; // Butuh ini



class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Info Box (Ini sudah benar) ---
        $totalBarang = BarangIT::count();
        $stokKritis = BarangIT::whereColumn('stok', '<', 'stok_minimum')->count();
        $rabMenunggu = Rab::where('status', 'Menunggu Approval')->count();
        $transaksiBulanIni = TransaksiMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->sum('jumlah_masuk');

        // --- Data untuk Grafik Pie Kategori (HAPUS ->toJson()) ---
        $dataPie = Kategori::withCount('barangs')->get();
        $pieLabels = $dataPie->pluck('nama_kategori'); // <--- HAPUS .toJson()
        $pieData = $dataPie->pluck('barangs_count'); // <--- HAPUS .toJson()

        // --- Data untuk Grafik Batang Stok (HAPUS ->toJson()) ---
        $dataStok = BarangIT::orderBy('stok', 'desc')->take(5)->get();
        $stokLabels = $dataStok->pluck('nama_barang'); // <--- HAPUS .toJson()
        $stokData = $dataStok->pluck('stok'); // <--- HAPUS .toJson()

        // --- Kirim semua data PHP murni ke view ---
        return view('dashboard', compact(
            'totalBarang',
            'stokKritis',
            'rabMenunggu',
            'transaksiBulanIni',
            'pieLabels',
            'pieData',
            'stokLabels',
            'stokData'
        ));
    }
}

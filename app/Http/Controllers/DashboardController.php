<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangIT;
use App\Models\Rab;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. DATA INFO BOX (ATAS) ---
        $totalBarang = BarangIT::count();
        $stokKritis = BarangIT::whereColumn('stok', '<', 'stok_minimum')->count();
        $rabMenunggu = Rab::where('status', 'Menunggu Approval')->count();
        // Total pengeluaran (belanja) bulan ini
        $pengeluaranBulanIni = TransaksiMasuk::whereMonth('tanggal_masuk', now()->month)
            ->whereYear('tanggal_masuk', now()->year)
            ->select(DB::raw('SUM(jumlah_masuk * harga_satuan) as total'))
            ->value('total');

        // --- 2. DATA GRAFIK TAHUNAN (GARIS) ---
        // Kita butuh array 12 bulan: [10, 20, 5, ...]
        $chartMasuk = $this->getMonthlyStats(new TransaksiMasuk(), 'tanggal_masuk', 'jumlah_masuk');
        $chartKeluar = $this->getMonthlyStats(new TransaksiKeluar(), 'tanggal_keluar', 'jumlah_keluar');

        // --- 3. DATA TOP 5 BARANG KELUAR (PROGRESS BAR) ---
        // Mengambil barang yang paling banyak keluar jumlahnya
        $topBarangKeluar = TransaksiKeluar::select('barang_it_id', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->with('barang_it') // Eager load nama barang
            ->groupBy('barang_it_id')
            ->orderByDesc('total_keluar')
            ->take(5)
            ->get();
        
        // Hitung persentase untuk progress bar (Total keluar item ini / Total keluar semua item)
        $totalSemuaKeluar = TransaksiKeluar::sum('jumlah_keluar');
        // Hindari pembagian dengan nol
        $totalSemuaKeluar = $totalSemuaKeluar > 0 ? $totalSemuaKeluar : 1; 

        // --- 4. DATA FOOTER (RINGKASAN) ---
        // Estimasi Nilai Aset (Stok saat ini * Harga Rata-rata pembelian)
        // Ini hitungan kasar tapi berguna.
        $nilaiAset = 0;
        $barangs = BarangIT::all();
        foreach($barangs as $b) {
            // Cari harga terakhir barang ini dari transaksi masuk
            $lastPrice = TransaksiMasuk::where('barang_it_id', $b->id)->orderBy('tanggal_masuk', 'desc')->value('harga_satuan');
            $nilaiAset += ($b->stok * ($lastPrice ?? 0));
        }
        
        $totalTransaksiTahunIni = TransaksiMasuk::whereYear('tanggal_masuk', now()->year)->count() 
                                + TransaksiKeluar::whereYear('tanggal_keluar', now()->year)->count();


        // --- 5. DATA TERBARU (BAWAH) ---
        $latestKeluar = TransaksiKeluar::with(['barang_it', 'user'])->latest('tanggal_keluar')->take(7)->get();
        $latestMasuk = TransaksiMasuk::with(['barang_it'])->latest('tanggal_masuk')->take(5)->get();


        return view('dashboard', compact(
            'totalBarang', 'stokKritis', 'rabMenunggu', 'pengeluaranBulanIni',
            'chartMasuk', 'chartKeluar',
            'topBarangKeluar', 'totalSemuaKeluar',
            'nilaiAset', 'totalTransaksiTahunIni',
            'latestKeluar', 'latestMasuk'
        ));
    }

    // Helper function untuk mengambil data per bulan (Jan-Des)
    private function getMonthlyStats($model, $dateCol, $sumCol)
    {
        $data = $model->select(
            DB::raw("MONTH($dateCol) as bulan"), 
            DB::raw("SUM($sumCol) as total")
        )
        ->whereYear($dateCol, now()->year)
        ->groupBy('bulan')
        ->pluck('total', 'bulan')
        ->toArray();

        // Isi bulan yang kosong dengan 0
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }
        return $result;
    }
}
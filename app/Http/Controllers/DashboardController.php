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
        $totalBarang = BarangIT::count();
        $stokKritis = BarangIT::whereColumn('stok', '<', 'stok_minimum')->count();
        $rabMenunggu = Rab::whereIn('status', ['Menunggu Manager', 'Menunggu Direktur'])->count();

        $masukBulanIni = TransaksiMasuk::whereMonth('tanggal_masuk', date('m'))
            ->whereYear('tanggal_masuk', date('Y'))->count();
        $keluarBulanIni = TransaksiKeluar::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))->count();
            
        // $totalTransaksiBulanIni = $masukBulanIni + $keluarBulanIni;

        $chartMasuk = $this->getMonthlyStats(new TransaksiMasuk(), 'tanggal_masuk', 'jumlah_masuk');
        $chartKeluar = $this->getMonthlyStats(new TransaksiKeluar(), 'tanggal_keluar', 'jumlah_keluar');
        $topBarangKeluar = TransaksiKeluar::select('barang_it_id', DB::raw('SUM(jumlah_keluar) as total_keluar'))
            ->with('barang_it')
            ->groupBy('barang_it_id')
            ->orderByDesc('total_keluar')
            ->take(5)
            ->get();
        
        $totalSemuaKeluar = TransaksiKeluar::sum('jumlah_keluar');
        $totalSemuaKeluar = $totalSemuaKeluar > 0 ? $totalSemuaKeluar : 1; 


        $barangAktif = BarangIT::where('stok', '>', 0)->count();
        
        $countMasukTahunIni = TransaksiMasuk::whereYear('tanggal_masuk', now()->year)->count();
        $countKeluarTahunIni = TransaksiKeluar::whereYear('tanggal_keluar', now()->year)->count();
        
        $totalTransaksiTahunIni = $countMasukTahunIni + $countKeluarTahunIni;


        $latestKeluar = TransaksiKeluar::with(['barang_it', 'user'])->latest('tanggal_keluar')->take(7)->get();
        $latestMasuk = TransaksiMasuk::with(['barang_it'])->latest('tanggal_masuk')->take(5)->get();


        return view('dashboard', compact(
            'totalBarang', 'stokKritis', 'rabMenunggu',
            'chartMasuk', 'chartKeluar',
            'topBarangKeluar', 'totalSemuaKeluar', 'totalTransaksiTahunIni',
            'latestKeluar', 'latestMasuk', 'masukBulanIni', 'keluarBulanIni', 
            'barangAktif',
        ));
    }

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

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $data[$i] ?? 0;
        }
        return $result;
    }
}
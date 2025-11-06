@extends('layouts.master')

@section('title', 'Dashboard Utama')
@section('content_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Baris untuk Info Box --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalBarang }}</h3>
                    <p>Total Barang</p>
                </div>
                <i class="small-box-icon bi bi-box-seam-fill" aria-hidden="true"></i>
                <a href="{{ route('barang.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $stokKritis }}</h3>
                    <p>Barang Stok Kritis</p>
                </div>
                <i class="small-box-icon bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                <a href="{{ route('barang.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box text-bg-warning text-white">
                <div class="inner">
                    <h3>{{ $rabMenunggu }}</h3>
                    <p>RAB Menunggu Persetujuan</p>
                </div>
                <i class="small-box-icon bi bi-file-earmark-text-fill" aria-hidden="true"></i>
                <a href="{{ route('rab.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $transaksiBulanIni }}</h3>
                    <p>Barang Masuk Bulan Ini</p>
                </div>
                <i class="small-box-icon bi bi-cart-plus-fill" aria-hidden="true"></i>
                <a href="{{ route('transaksi-masuk.index') }}"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Baris untuk Grafik/Charts --}}
    <div class="row">
        {{-- Grafik Pie Kategori --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aset Berdasarkan Kategori</h3>
                </div>
                <div class="card-body">
                    {{-- Ini adalah panggung "kanvas" untuk melukis --}}
                    <canvas id="pieChartKategori" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Grafik Batang Stok Terbanyak --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">5 Barang Stok Terbanyak</h3>
                </div>
                <div class="card-body">
                    <canvas id="barChartStok" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>
                    <p class="card-text">
                        Ini adalah halaman dashboard utama Sistem Inventory Rumah Sakit.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@push('scripts')
{{-- Kita panggil library Chart.js (jika belum ada di adminlte.js) --}}
{{-- AdminLTE 4 biasanya sudah include ini, tapi untuk jaga-jaga: --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Pastikan DOM sudah siap
    document.addEventListener('DOMContentLoaded', function() {

        // ==================
        // 1. "MELUKIS" PIE CHART KATEGORI
        // ==================

        // Ambil "Kanvas" (Panggung) dari HTML
        const pieCanvas = document.getElementById('pieChartKategori').getContext('2d');

        // Ambil "Data Lukisan" (Labels) dari PHP

        const pieLabels = @json($pieLabels ?? []);

        // Ambil "Data Lukisan" (Data) dari PHP
        const pieData = @json($pieData ?? []);

        // Buat palet warna acak (opsional, biar cantik)
        const pieColors = pieData.map(() => `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`);

        // Buat lukisan baru
        new Chart(pieCanvas, {
            type: 'pie', // Tipe lukisannya: Pie
            data: {
                labels: pieLabels, // Judul/label setiap potongan pie
                datasets: [{
                    label: 'Jumlah Barang',
                    data: pieData, // Angka/data setiap potongan pie
                    backgroundColor: pieColors, // Warna setiap potongan
                }]
            },
            options: {
                responsive: true, // Biar bisa menyesuaikan ukuran
                maintainAspectRatio: false,
            }
        });

        // ==================
        // 2. "MELUKIS" BAR CHART STOK
        // ==================

        // Ambil "Kanvas" (Panggung) dari HTML
        const barCanvas = document.getElementById('barChartStok').getContext('2d');

        // Ambil "Data Lukisan" (Labels) dari PHP
        const stokLabels = @json($stokLabels ?? []);

        // Ambil "Data Lukisan" (Data) dari PHP
        const stokData = @json($stokData ?? []);

        // Buat lukisan baru
        new Chart(barCanvas, {
            type: 'bar', // Tipe lukisannya: Batang (Bar)
            data: {
                labels: stokLabels, // Nama barang di sumbu X
                datasets: [{
                    label: 'Jumlah Stok',
                    data: stokData, // Angka/tinggi batang di sumbu Y
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Warna batang
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true // Mulai hitungan dari 0
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda (label 'Jumlah Stok')
                    }
                }
            }
        });
    });
</script>
@endpush
@extends('layouts.master')

@section('title', 'Dashboard Utama')
@section('content_title', 'Dashboard')

@section('content')
<div class="container-fluid">

    {{-- =========================================================== --}}
    {{-- BARIS 1: INFO BOXES (Statistik Utama) --}}
    {{-- =========================================================== --}}
    <div class="row">
        
        {{-- BOX 1: TOTAL ASET BARANG --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box position-relative">
                <span class="info-box-icon text-bg-info shadow-sm">
                    <i class="bi bi-box-seam-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Aset Barang</span>
                    <span class="info-box-number">{{ $totalBarang }} <small>Jenis</small></span>
                    
                    <a href="{{ route('barang.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- BOX 2: STOK KRITIS --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box position-relative">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Stok Kritis</span>
                    <span class="info-box-number">{{ $stokKritis }} <small>Item</small></span>
                    
                    <a href="{{ route('barang.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- BOX 3: RAB MENUNGGU --}}
        <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box position-relative">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">RAB Menunggu</span>
                    <span class="info-box-number">{{ $rabMenunggu }} <small>Dokumen</small></span>
                    
                    <a href="{{ route('rab.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- BOX 4: BARANG MASUK BULAN INI --}}
        <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box position-relative">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Masuk Bulan Ini</span>
                    <span class="info-box-number">{{ $masukBulanIni }} <small>Transaksi</small></span>
                    
                    <a href="{{ route('transaksi-masuk.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        {{-- BOX 5: BARANG KELUAR BULAN INI --}}
        <div class="col-12 col-sm-6 col-md-2">
            <div class="info-box position-relative">
                <span class="info-box-icon text-bg-primary shadow-sm">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Keluar Bulan Ini</span>
                    <span class="info-box-number">{{ $keluarBulanIni }} <small>Transaksi</small></span>
                    
                    <a href="{{ route('transaksi-keluar.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================================================== --}}
    {{-- BARIS 2: MONTHLY RECAP REPORT (Grafik & Progress) --}}
    {{-- =========================================================== --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Statistik Gudang Tahun {{ date('Y') }}</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                            <i class="bi bi-dash-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- CHART (KIRI) - Tetap Sama --}}
                        <div class="col-md-8">
                            <p class="text-center"><strong>Grafik Keluar-Masuk Barang</strong></p>
                            <div class="chart">
                                <canvas id="salesChart" style="height: 180px;"></canvas>
                            </div>
                        </div>

                        {{-- PROGRESS (KANAN) - Tetap Sama --}}
                        <div class="col-md-4">
                            <p class="text-center"><strong>Top 5 Barang Keluar</strong></p>
                            @forelse($topBarangKeluar as $item)
                            @php
                                $persen = $totalSemuaKeluar > 0 ? ($item->total_keluar / $totalSemuaKeluar) * 100 : 0;
                                $colors = ['primary', 'success', 'warning', 'danger', 'info'];
                                $color = $colors[$loop->index % 5];
                            @endphp
                            <div class="progress-group">
                                {{ $item->barang_it->nama_barang ?? 'Item Terhapus' }}
                                <span class="float-end"><b>{{ $item->total_keluar }}</b>/{{ $totalSemuaKeluar }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar text-bg-{{ $color }}" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted">Belum ada data.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- FOOTER SUMMARY (YANG DIUBAH) --}}
                <div class="card-footer">
                    <div class="row">
                        
                        {{-- ITEM 1: (UBAH) BARANG AKTIF --}}
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-end">
                                <span class="description-percentage text-primary">
                                    <i class="bi bi-box-fill"></i> Tersedia
                                </span>
                                <h5 class="description-header">{{ $barangAktif }} Jenis</h5>
                                <span class="description-text">STOK BARANG > 0</span>
                            </div>
                        </div>

                        {{-- ITEM 2: TOTAL TRANSAKSI TAHUNAN --}}
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-end">
                                <span class="description-percentage text-warning">
                                    <i class="bi bi-activity"></i> Sibuk
                                </span>
                                <h5 class="description-header">{{ $totalTransaksiTahunIni }}</h5>
                                <span class="description-text">TOTAL TRANSAKSI (THN)</span>
                            </div>
                        </div>

                        {{-- ITEM 3: TOTAL MASUK --}}
                        <div class="col-sm-3 col-6">
                            <div class="description-block border-end">
                                <span class="description-percentage text-success">
                                    <i class="bi bi-caret-up-fill"></i> In
                                </span>
                                <h5 class="description-header">{{ array_sum($chartMasuk) }}</h5>
                                <span class="description-text">ITEM MASUK (THN)</span>
                            </div>
                        </div>

                        {{-- ITEM 4: TOTAL KELUAR --}}
                        <div class="col-sm-3 col-6">
                            <div class="description-block">
                                <span class="description-percentage text-danger">
                                    <i class="bi bi-caret-down-fill"></i> Out
                                </span>
                                <h5 class="description-header">{{ array_sum($chartKeluar) }}</h5>
                                <span class="description-text">ITEM KELUAR (THN)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================== --}}
    {{-- BARIS 3: AKTIVITAS GUDANG (Latest Orders & Products) --}}
    {{-- =========================================================== --}}
    <div class="row mt-3">

        {{-- KIRI: TABEL TRANSAKSI KELUAR TERAKHIR --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Barang Keluar Terakhir</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th>Teknisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestKeluar as $out)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($out->tanggal_keluar)->format('d M Y') }}</td>
                                    <td>{{ $out->barang_it->nama_barang ?? '-' }}</td>
                                    <td><span class="badge text-bg-danger">- {{ $out->jumlah_keluar }}</span></td>
                                    <td>{{ $out->user->name ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-sm btn-danger float-start">Catat Barang Keluar</a>
                    <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-sm btn-secondary float-end">Lihat Semua</a>
                </div>
            </div>
        </div>

        {{-- KANAN: LIST BARANG MASUK TERAKHIR (Recently Added) --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Barang Masuk Terakhir</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2 mt-2">
                        @forelse($latestMasuk as $in)
                        <li class="item">
                            <div class="product-img">
                                {{-- Gambar Barang (atau default) --}}
                                @if($in->barang_it && $in->barang_it->gambar_barang)
                                <img src="{{ asset('storage/gambar_barang/' . $in->barang_it->gambar_barang) }}" alt="Img" class="img-size-50">
                                @else
                                <img src="{{ asset('dist/assets/img/default-150x150.png') }}" alt="Default" class="img-size-50">
                                @endif
                            </div>
                            <div class="product-info">
                                <a href="{{ route('barang.index') }}" class="product-title">
                                    {{ $in->barang_it->nama_barang ?? 'Item Dihapus' }}
                                    <span class="badge text-bg-success float-end me-2">+{{ $in->jumlah_masuk }}</span>
                                </a>
                                <span class="product-description">
                                    Supplier: {{ $in->supplier->nama_supplier ?? '-' }} <br>
                                    <small class="text-muted"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($in->tanggal_masuk)->diffForHumans() }}</small>
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item p-3 text-center text-muted">Belum ada barang masuk.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('transaksi-masuk.index') }}" class="uppercase">Lihat Semua Transaksi Masuk</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- DATA DARI CONTROLLER ---
        // Kita terima data array PHP dan ubah jadi JSON
        const chartMasuk = @json(array_values($chartMasuk));
        const chartKeluar = @json(array_values($chartKeluar));

        // Label Bulan (Jan - Des)
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // --- KONFIGURASI CHART ---
        const salesChartCanvas = document.getElementById('salesChart').getContext('2d');

        const salesChartData = {
            labels: months,
            datasets: [{
                    label: 'Barang Masuk',
                    backgroundColor: 'rgba(40, 167, 69, 0.9)', // Hijau
                    borderColor: 'rgba(40, 167, 69, 0.8)',
                    data: chartMasuk,
                    fill: false,
                    tension: 0.4 // Biar garisnya agak melengkung (smooth)
                },
                {
                    label: 'Barang Keluar',
                    backgroundColor: 'rgba(220, 53, 69, 0.9)', // Merah
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    data: chartKeluar,
                    fill: false,
                    tension: 0.4
                }
            ]
        };

        const salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            }
        };

        // RENDER CHART
        new Chart(salesChartCanvas, {
            type: 'line',
            data: salesChartData,
            options: salesChartOptions
        });
    });
</script>
@endpush
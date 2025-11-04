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
    {{-- Nanti di sini kita tambahkan Grafik/Charts --}}
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

</div>@endsection
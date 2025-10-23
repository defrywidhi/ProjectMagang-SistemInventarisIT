@extends('layouts.master')

@section('title', 'Dashboard Utama')
@section('content_title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Selamat Datang, {{ Auth::user()->name }}!</h5>

                    <p class="card-text">
                        Ini adalah halaman dashboard utama Sistem Inventory Rumah Sakit. Silakan gunakan menu di samping untuk mengelola data.
                    </p>

                    {{-- Nanti di sini bisa kita tambahkan ringkasan data --}}

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
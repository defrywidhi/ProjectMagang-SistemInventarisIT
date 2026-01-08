@extends('emails.email-layout')

@section('title', 'Reminder Stok Opname')

@section('header-title', '📦 Stok Opname')
@section('header-subtitle', 'Pengingat Bulanan')

@section('content')
    <p class="greeting">Halo {{ $user->name }},</p>
    
    <div class="alert-box alert-warning">
        <strong>⏰ Pengingat Otomatis</strong><br>
        Kita sudah memasuki bulan baru! Sudah waktunya untuk melakukan stok opname.
    </div>

    <div class="content-block">
        <p>Stok opname adalah prosedur penting untuk:</p>
        <ul style="padding-left: 20px; color: #555; margin-top: 10px;">
            <li>✓ Memastikan data sistem sesuai dengan stok fisik</li>
            <li>✓ Mendeteksi selisih atau kehilangan barang</li>
            <li>✓ Menjaga akurasi laporan inventory</li>
            <li>✓ Mencegah kehabisan stok mendadak</li>
        </ul>
    </div>

    <div class="info-box">
        <h3 style="margin-bottom: 15px; color: #667eea;">Informasi Reminder</h3>
        <ul>
            <li><strong>Periode:</strong> {{ now()->format('F Y') }}</li>
            <li><strong>Tanggal:</strong> {{ now()->format('d M Y') }}</li>
            <li><strong>Penanggung Jawab:</strong> {{ $user->name }}</li>
        </ul>
    </div>

    <div class="divider"></div>

    <div class="content-block">
        <p><strong>Langkah-langkah Stok Opname:</strong></p>
        <ol style="padding-left: 20px; color: #555; margin-top: 10px;">
            <li>Login ke sistem dan buka menu Stok Opname</li>
            <li>Buat sesi stok opname baru</li>
            <li>Hitung fisik barang di gudang</li>
            <li>Input hasil perhitungan ke sistem</li>
            <li>Review dan selesaikan sesi</li>
        </ol>
    </div>

    <div style="background-color: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #2196f3;">
        <strong style="color: #1565c0;">💡 Tips:</strong><br>
        <p style="margin-top: 10px; color: #555;">
            Lakukan stok opname di luar jam operasional untuk hasil yang lebih akurat dan menghindari gangguan transaksi.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('stok-opname.index') }}" class="btn">
            📋 Mulai Stok Opname
        </a>
    </div>

    <div class="divider"></div>

    <p style="text-align: center; color: #777; font-size: 13px;">
        Email reminder ini dikirim otomatis setiap awal bulan.<br>
        Untuk mengubah jadwal, hubungi administrator sistem.
    </p>
@endsection
@extends('emails.email-layout')

@section('title', 'Peringatan Stok Menipis')

@section('header-title', '⚠️ Peringatan Stok')
@section('header-subtitle', 'Stok Di Bawah Batas Minimum')

@section('content')
    <p class="greeting">Halo Admin,</p>
    
    <div class="alert-box alert-danger">
        <strong>🚨 Peringatan Penting!</strong><br>
        Sistem mendeteksi ada <strong>{{ count($barangs) }} barang</strong> yang stoknya sudah di bawah batas minimum. Mohon segera lakukan pengadaan untuk menghindari kehabisan stok.
    </div>

    <div class="content-block">
        <p>Daftar barang yang perlu segera di-restock:</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Barang</th>
                <th style="width: 20%;">Kategori</th>
                <th style="width: 20%; text-align: center;">Stok Saat Ini</th>
                <th style="width: 20%; text-align: center;">Stok Minimum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $index => $barang)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $barang->nama_barang }}</strong></td>
                    <td>{{ $barang->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        <span style="background-color: #ffebee; color: #c62828; padding: 4px 12px; border-radius: 12px; font-weight: bold;">
                            {{ $barang->stok }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span style="color: #666;">{{ $barang->stok_minimum }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="content-block">
        <p><strong>Tindakan yang Perlu Dilakukan:</strong></p>
        <ol style="padding-left: 20px; color: #555; margin-top: 10px;">
            <li>Review daftar barang di atas</li>
            <li>Buat RAB untuk pengadaan barang yang diperlukan</li>
            <li>Ajukan ke manajer untuk approval</li>
            <li>Lakukan pembelian setelah RAB disetujui</li>
        </ol>
    </div>

    <div style="background-color: #fff3e0; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ff9800;">
        <strong style="color: #e65100;">💡 Catatan Penting:</strong><br>
        <p style="margin-top: 10px; color: #555;">
            Penundaan pengadaan dapat menyebabkan terhentinya operasional. Segera ambil tindakan untuk memastikan kelancaran bisnis.
        </p>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('rab.create') }}" class="btn">
            📝 Buat RAB Pengadaan
        </a>
    </div>

    <div class="divider"></div>

    <p style="text-align: center; color: #777; font-size: 13px;">
        Email peringatan ini dikirim otomatis ketika stok barang mencapai atau di bawah batas minimum.<br>
        Untuk mengubah batas minimum, edit data barang melalui menu Manajemen Barang.
    </p>
@endsection
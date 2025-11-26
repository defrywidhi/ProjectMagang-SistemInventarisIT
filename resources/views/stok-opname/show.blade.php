@extends('layouts.master')

@section('title', 'Detail Stok Opname')
@section('content_title', 'Sesi Opname: ' . $stokOpname->tanggal_opname)

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Kolom Info Sesi --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h3 class="profile-username text-center">Sesi {{ $stokOpname->tanggal_opname }}</h3>
                    <p class="text-muted text-center">{{ $stokOpname->status }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Auditor</b> <a class="float-right">{{ $stokOpname->auditor->name }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Jumlah Item Diperiksa</b> <a class="float-right">{{ $stokOpname->details->count() }}</a>
                        </li>
                    </ul>
                    <a href="{{ route('stok-opname.index') }}" class="btn btn-secondary btn-block"><b>Kembali</b></a>
                </div>
            </div>
            <div class="card card-outline">
                <div class="card-header">
                    <h3 class="card-title">Catatan Sesi</h3>
                </div>
                <div class="card-body">
                    {{ $stokOpname->catatan ?? 'Tidak ada catatan.' }}
                </div>
            </div>
        </div>

        {{-- Kolom "Lembar Kerja" (Form & Tabel) --}}
        <div class="col-md-8">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">Input Hasil Stok Fisik</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    {{-- Ini adalah form besar yang membungkus seluruh tabel --}}
                    <form action="{{ route('stok-opname.saveDetails', $stokOpname->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Nama Barang</th>
                                    <th style="width: 15%" class="text-center">Stok Sistem</th>
                                    <th style="width: 15%" class="text-center">Stok Fisik (Input)</th>
                                    <th style="width: 40%">Keterangan Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stokOpname->details as $detail)
                                <tr>
                                    <td>{{ $detail->barangIt->nama_barang }}</td>
                                    <td class="text-center bg-light"><strong>{{ $detail->stok_sistem }}</strong></td>
                                    <td class="text-center">
                                        @if($stokOpname->status == 'Pending')
                                        {{-- Jika Pending: Tampilkan Input --}}
                                        <input type="number"
                                            name="stok_fisik[{{ $detail->id }}]"
                                            class="form-control"
                                            value="{{ $detail->stok_fisik }}"
                                            required min="0">
                                        @else
                                        {{-- Jika Selesai: Tampilkan dengan warna berdasarkan perbandingan --}}
                                        @php
                                        if ($detail->stok_fisik == $detail->stok_sistem) {
                                        $badgeColor = 'bg-success'; // Hijau - sama
                                        } elseif ($detail->stok_fisik < $detail->stok_sistem) {
                                            $badgeColor = 'bg-danger'; // Merah - kurang
                                            } else {
                                            $badgeColor = 'bg-primary'; // Biru - lebih
                                            }
                                            @endphp
                                            <span class="badge {{ $badgeColor }}" style="font-size: 14px;">
                                                {{ $detail->stok_fisik }}
                                            </span>
                                            @endif
                                    </td>
                                    <td>
                                        @if($stokOpname->status == 'Pending')
                                        {{-- Jika Pending: Tampilkan Input --}}
                                        <input type="text"
                                            name="keterangan_item[{{ $detail->id }}]"
                                            class="form-control"
                                            value="{{ $detail->keterangan_item }}"
                                            placeholder="Cth: Rusak, Hilang">
                                        @else
                                        {{-- Jika Selesai: Tampilkan Teks Biasa --}}
                                        {{ $detail->keterangan_item ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data detail tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>
                @if($stokOpname->status == 'Pending')
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan Hasil Opname</button>
                </div>
                @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
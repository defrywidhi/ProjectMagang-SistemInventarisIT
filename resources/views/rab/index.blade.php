@extends('layouts.master')

@section('content_title', 'Daftar RAB')
@section('title', 'RAB')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="">
                <a href="{{ route('rab.create') }}" class="btn btn-primary">Tambah RAB Baru</a>
            </div>
        </div>
        <div class="card-body p-0 text-center table-responsive">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode RAB</th>
                            <th>Judul</th>
                            <th>Pengaju</th>
                            <th>Status</th>
                            <th>Tgl Dibuat</th>
                            <th>Tgl Disetujui</th>
                            <th>Disetujui Oleh</th>
                            <th>Catatan</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @forelse ( $rabs as $item )
                        <tr>
                            <td>{{ $item->kode_rab }}</td>
                            <td>{{ $item->judul }}</td>
                            <td>{{ $item->pengaju->name ?? 'N/A' }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->tanggal_dibuat }}</td>
                            <td>{{ $item->tanggal_disetujui ?? '_' }}</td>
                            <td>{{ $item->penyetuju->name ?? '_' }}</td>
                            <td>{{ $item->catatan_approval ?? '_' }}</td>
                            <td class="text-center">
                                <a href="{{ route('rab.show', $item->id) }}" class="btn btn-info">Detail</a><br>
                                <a href="#" class="btn btn-warning">Edit</a>
                                <form action="#" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data RAB.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection
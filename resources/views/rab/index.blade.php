@extends('layouts.master')

@section('content_title', 'Daftar RAB')
@section('title', 'RAB')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="">
                <a href="{{ route('rab.create') }}" class="btn btn-primary">Tambah RAB Baru</a>
            </div>
        </div>
        <div class="card-body p-0 text-center table-responsive">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if (session('success_edit'))
            <div class="alert alert-success">{{ session('success_edit') }}</div>
            @endif
                <table class="table table-bordered">
                    <thead class="table-secondary">
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
                            <td class="text-center p-0">
                                <a href="{{ route('rab.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-info-circle"></i>
                                </a>
                                <a href="{{ route('rab.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form class="d-inline" action="{{ route('rab.destroy', $item->id) }}" method="POST" >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
@extends('layouts.master')

@section('content_title', 'Transaksi Keluar')
@section('title','Transaksi Keluar')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">Tambah Transaksi Keluar</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center" >
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah Keluar</th>
                            <th>Tanggal Keluar</th>
                            <th>Keterangan</th>
                            <th>User Input</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $transaksi_keluar as $item )
                        <tr>
                            <td>{{ $item->barang_it->nama_barang }}</td>
                            <td>{{ $item->jumlah_keluar }}</td>
                            <td>{{ $item->tanggal_keluar }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('transaksi-keluar.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('transaksi-keluar.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data transaksi keluar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
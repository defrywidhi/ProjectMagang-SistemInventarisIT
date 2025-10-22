@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Transaksi Keluar</h1>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">Tambah Transaksi Keluar</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah Keluar</th>
                            <th>Tanggal Keluar</th>
                            <th>Keterangan</th>
                            <th>User Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $transaksi_keluar as $item )
                        <tr>
                            <td>{{ $item->barang_it->nama_barang }}</td>
                            <td>{{ $item->jumlah_keluar }}</td>
                            <td>{{ $item->tanggal_keluar }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>
                                <a href="{{ route('transaksi-keluar.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('transaksi-keluar.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
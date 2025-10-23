@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Transaksi Masuk</h1>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-primary">Tambah Transaksi Masuk</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Tanggal Masuk</th>
                            <th>Harga Satuan</th>
                            <th>Keterangan</th>
                            <th>User Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $transaksis_masuk as $item )
                        <tr>
                            <td>{{ $item->barang_it->nama_barang }}</td>
                            <td>{{ $item->supplier->nama_supplier }}</td>
                            <td>{{ $item->jumlah_masuk }}</td>
                            <td>{{ $item->tanggal_masuk }}</td>
                            <td>{{ $item->harga_satuan }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>
                                <a href="{{ route('transaksi-masuk.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('transaksi-masuk.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Hapus</button>
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
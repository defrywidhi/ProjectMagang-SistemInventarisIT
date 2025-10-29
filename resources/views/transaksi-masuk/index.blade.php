@extends('layouts.master')

@section('content_title', 'Transaksi Masuk')
@section('title', 'Transaksi Masuk')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-primary">Tambah Transaksi Masuk</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Supplier</th>
                            <th>Jumlah</th>
                            <th>Tanggal Masuk</th>
                            <th>Harga Satuan</th>
                            <th>Keterangan</th>
                            <th>User Input</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ( $transaksis_masuk as $item )
                        <tr>
                            <td>{{ $item->barang_it->nama_barang }}</td>
                            <td>{{ $item->supplier->nama_supplier }}</td>
                            <td>{{ $item->jumlah_masuk }}</td>
                            <td>{{ $item->tanggal_masuk }}</td>
                            <td>{{ $item->harga_satuan }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('transaksi-masuk.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('transaksi-masuk.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data transaksi masuk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
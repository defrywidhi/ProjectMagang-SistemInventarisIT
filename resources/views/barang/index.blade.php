@extends('layouts.master')

@section('content')

<div class="container">

<div>
    @session('success')
    <div class="alert alert-success">
        {{ session('success') }}
    @endsession
</div>
    <div>
        <h1>Tabel Barang</h1>
    </div>

    <div>
        <a href="{{ route('barang.create') }}" class="btn btn-primary mb-3">Tambah Barang</a>
    </div>

    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Serial Number</th>
                    <th>Deskripsi</th>
                    <th>Stok</th>
                    <th>Stok Minimun</th>
                    <th>Kondisi</th>
                    <th>Lokasi Penyimpanan</th>
                    <th>Gambar Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
            @foreach ($barang as $item )
                <tr>
                    <td>{{ $item -> kategori -> nama_kategori }}</td>
                    <td>{{ $item -> nama_barang }}</td>
                    <td>{{ $item -> merk }}</td>
                    <td>{{ $item -> serial_number }}</td>
                    <td>{{ $item -> deskripsi }}</td>
                    <td>{{ $item -> stok }}</td>
                    <td>{{ $item -> stok_minimum }}</td>
                    <td>{{ $item -> kondisi }}</td>
                    <td>{{ $item -> lokasi_penyimpanan }}</td>
                    <td>
                        @if($item->gambar_barang)
                        <img src="{{ asset('storage/gambar_barang/'. $item->gambar_barang) }}" alt="Gambar Barang" class="object-fit-cover" style="width: 100px; height: 100px;">
                        @else
                        Tidak Ada Gambar
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('barang.destroy', $item->id) }}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('yakin menghapus file ini?')" >Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
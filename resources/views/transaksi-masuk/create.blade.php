@extends ('layouts.master')

@section('title', 'Transaksi Masuk')
@section('content_title', 'Transaksi Masuk')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Transaksi Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-masuk.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="barang_it_id">Pilih Nama Barang</label>
                            <select class="form-select mb-3" name="barang_it_id" id="barang_it_id" required>
                                <option value="">Pilih Nama Barang</option>
                                @foreach ($barangs as $item_barang)
                                <option value="{{ $item_barang->id }}">{{ $item_barang -> nama_barang }} (Merk : {{ $item_barang->merk ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Pilih Supplier Barang</label>
                            <select name="supplier_id" id="supplier_id" class="mb-3 form-select" required>
                                <option value="">Pilih Supplier Barang</option>
                                @foreach ($suppliers as $item_supplier )
                                <option value="{{ $item_supplier->id }}">{{ $item_supplier->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_masuk">Masukkan Jumlah Barang</label>
                            <input class="form-control mb-3" type="number" name="jumlah_masuk" id="jumlah_masuk" required @error('jumlah_masuk') is-invalid @enderror>
                            @error('jumlah_masuk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Barang Masuk</label>
                            <input class="form-control mb-3" type="date" name="tanggal_masuk" id="tanggal_masuk" required @error ('tanggal_masuk') is-invalid @enderror>
                            @error('tanggal_masuk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">Masukkan Harga Satuan Barang</label>
                            <input class="form-control mb-3" type="number" name="harga_satuan" id="harga_satuan" required @error ('harga_satuan') is-invalid @enderror>
                            @error('harga_satuan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan Barang</label>
                            <input class="form-control mb-3" type="text" name="keterangan" id="keterangan" @error('keterangan') is-invalid @enderror>
                            @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection
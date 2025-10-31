@extends ('layouts.master')

@section('title', 'Edit Transaksi Masuk')
@section('content_title', 'Edit Transaksi Masuk')



@section ('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Transaksi Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-masuk.update', $transaksi_masuk->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="barang_it_id">Nama Barang</label>
                            <select name="barang_it_id" id="barang_it_id" class="form-select">
                                @foreach ( $barangs as $data_barang )
                                <option value="{{ old('barang_it_id', $data_barang->id) }}" {{ $transaksi_masuk->barang_it_id == $data_barang->id ? 'selected' : '' }}>{{ $data_barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Nama Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select">
                                @foreach ( $suppliers as $data_supplier )
                                <option value="{{ old('supplier_id', $data_supplier->id) }}" {{ $transaksi_masuk->supplier_id == $data_supplier->id ? 'selected' : '' }}>{{ $data_supplier->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_masuk">Jumlah Barang</label>
                            <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" value="{{ old('jumlah_masuk', $transaksi_masuk->jumlah_masuk) }}">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Masuk Barang</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $transaksi_masuk->tanggal_masuk) }}">
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan Barang</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" value="{{ old('harga_satuan', $transaksi_masuk->harga_satuan) }}">
                        </div>
                        <div class="form-group">
                            <label for="keterangan">keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" value="{{ old('keterangan', $transaksi_masuk->keterangan) }}">
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Update Transaksi Masuk</button>
                            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
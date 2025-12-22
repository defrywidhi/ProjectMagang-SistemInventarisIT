@extends ('layouts.master')

@section('title', 'Edit Transaksi Masuk')
@section('content_title', 'Edit Transaksi Masuk')

@section ('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Transaksi Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-masuk.update', $transaksi_masuk->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="barang_it_id">Nama Barang</label>
                            <select name="barang_it_id" id="barang_it_id" class="form-select" required>
                                <option value=""> -- Pilih Barang -- </option>
                                @foreach ( $barangs as $data_barang )
                                <option value="{{ $data_barang->id }}" {{ old('barang_it_id', $transaksi_masuk->barang_it_id) == $data_barang->id ? 'selected' : '' }}>
                                    {{ $data_barang->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                            @error('barang_it_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Nama Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select" required>
                                <option value=""> -- Pilih Supplier -- </option>
                                @foreach ( $suppliers as $data_supplier )
                                <option value="{{ $data_supplier->id }}" {{ old('supplier_id', $transaksi_masuk->supplier_id) == $data_supplier->id ? 'selected' : '' }}>
                                    {{ $data_supplier->nama_supplier }}
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jumlah_masuk">Jumlah Barang</label>
                            <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control @error('jumlah_masuk') is-invalid @enderror" value="{{ old('jumlah_masuk', $transaksi_masuk->jumlah_masuk) }}" required>
                            @error('jumlah_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Masuk Barang</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $transaksi_masuk->tanggal_masuk) }}" required>
                            @error('tanggal_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan Barang</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control @error('harga_satuan') is-invalid @enderror" value="{{ old('harga_satuan', $transaksi_masuk->harga_satuan) }}" required>
                            @error('harga_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan', $transaksi_masuk->keterangan) }}">
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-warning">Update Transaksi Masuk</button>
                            <a href="{{ route('transaksi-masuk.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
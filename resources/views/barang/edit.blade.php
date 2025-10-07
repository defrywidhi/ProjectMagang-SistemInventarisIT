@extends('layouts.master')

@section('content')

<div class="container">
    <div class="card" style="width: 50%">
        <div class="card-header">
            <h1>Tambah data Barang</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('barang.update', $barang->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select class="form-select" name="kategori_id" id="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ( $kategoris as $item )
                        <option value="{{ $item -> id }}" {{ $barang->kategori_id == $item->id ? 'selected' : '' }}>{{ $item -> nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input value="{{ $barang->nama_barang }}" @error('nama_barang') is-invalid @enderror type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                    @error('nama_barang')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input value="{{ $barang->merk }}" @error('merk') is-invalid @enderror type="text" class="form-control" id="merk" name="merk">
                    @error('merk')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="serial_number">Serial Number</label>
                    <input value="{{ $barang->serial_number }}" @error('serial_number') is-invalid @enderror type="text" class="form-control" id="serial_number" name="serial_number">
                    @error('serial_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input value="{{ $barang->deskripsi }}" @error('deskripsi') is-invalid @enderror type="text" class="form-control" id="deskripsi" name="deskripsi">
                    @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input value="{{ $barang->stok }}" @error('stok') is-invalid
                        @enderror type="text" class="form-control" id="stok" name="stok" required>
                    @error('stok')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="stok_minimum">Stok Minimum</label>
                    <input value="{{ $barang->stok_minimum }}" @error('stok_minimum') is-invalid
                        @enderror type="text" class="form-control" id="stok_minimum" name="stok_minimum" required>
                    @error('stok_minimum')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kondisi">Kondisi</label>
                    <select value="{{ $barang->kondisi }}" class="form-select" name="kondisi" id="kondisi" required>
                        <option value="Baru">Baru</option>
                        <option value="Bekas">Bekas</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                    <input value="{{ $barang->lokasi_penyimpanan }}" @error('lokasi_penyimpanan') is-invalid
                        @enderror type="text" class="form-control" id="lokasi_penyimpanan" name="lokasi_penyimpanan">
                    @error('lokasi_penyimpanan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="gambar_barang">Gambar Barang</label>
                    @if ($barang->gambar_barang)
                        <div>
                        <img src="{{ asset('storage/gambar_barang/'.$barang->gambar_barang) }}" alt="gambar barang" class="ratio ratio-1x1 mb-3">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="gambar_barang" name="gambar_barang">
                </div>


                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
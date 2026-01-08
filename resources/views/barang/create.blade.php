@extends('layouts.master')

@section('title', 'Tambah Barang')
@section('content_title', 'Tambah Barang Baru')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tambah Barang</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-select" name="kategori_id" id="kategori_id" required>
                                <option value=""> -- Pilih Kategori -- </option>
                                @foreach ( $kategoris as $item )
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang</label>
                            <input value="{{ old('nama_barang') }}" @error('nama_barang') is-invalid @enderror type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                            @error('nama_barang')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="merk">Merk</label>
                            <input value="{{ old('merk') }}" @error('merk') is-invalid @enderror type="text" class="form-control" id="merk" name="merk">
                            @error('merk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="serial_number">Serial Number</label>
                            <input value="{{ old('serial_number') }}" @error('serial_number') is-invalid @enderror type="text" class="form-control" id="serial_number" name="serial_number">
                            @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <input value="{{ old('deskripsi') }}" @error('deskripsi') is-invalid @enderror type="text" class="form-control" id="deskripsi" name="deskripsi">
                            @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="stok_minimum">Stok Minimum</label>
                            <input value="{{ old('stok_minimum') }}" @error('stok_minimum') is-invalid
                                @enderror type="text" class="form-control" id="stok_minimum" name="stok_minimum" required>
                            @error('stok_minimum')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select class="form-select" name="kondisi" id="kondisi" required>
                                <option value=""> -- Pilih Kondisi --</option>
                                <option value="Baru" {{ old('kondisi') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Bekas" {{ old('kondisi') == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                                <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
                            <input value="{{ old('lokasi_penyimpanan') }}" @error('lokasi_penyimpanan') is-invalid
                                @enderror type="text" class="form-control" id="lokasi_penyimpanan" name="lokasi_penyimpanan">
                            @error('lokasi_penyimpanan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="gambar_barang">Gambar Barang</label>
                            <input type="file" class="form-control" id="gambar_barang" name="gambar_barang" @error ('gambar_barang') is-invalid @enderror>
                            @error('gambar_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
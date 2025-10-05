@extends('layouts.master')

@section('title', 'Edit Kategori')
@section('content_title', 'Edit Kategori')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Kategori</h3>
                </div>
                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="{{ $kategori->nama_kategori }}">
                            @error('nama_kategori')
                                <div>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kode_kategori">Kode Kategori</label>
                            <input type="text" class="form-control" id="kode_kategori" name="kode_kategori" value="{{ $kategori->kode_kategori }}">
                            @error('kode_kategori')
                                <div>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
            </div>
    </div>
</div>
@endsection
@extends ('layouts.master')

@section('title', 'Transaksi Keluar')
@section('content_title', 'Transaksi Keluar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Transaksi Keluar</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-keluar.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="barang_it_id">Barang Yang Keluar</label>
                            <select class="form-control" id="barang_it_id" name="barang_it_id" required>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_keluar">Jumlah Barang Keluar</label>
                            <input @error('jumlah_keluar') is-invalid @enderror type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" required>
                            @error('jumlah_keluar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_keluar">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">keterangan</label>
                            <input type="textarea" class="form-control" id="keterangan" name="keterangan">
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endsection
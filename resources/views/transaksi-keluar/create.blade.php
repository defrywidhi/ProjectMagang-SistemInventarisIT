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
                            <select class="form-control @error('barang_it_id') is-invalid @enderror" id="barang_it_id" name="barang_it_id" required>
                                <option value=""> -- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}" {{ old('barang_it_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                </option>
                                @endforeach
                            </select>
                            @error('barang_it_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jumlah_keluar">Jumlah Barang Keluar</label>
                            <input value="{{ old('jumlah_keluar') }}" @error('jumlah_keluar') is-invalid @enderror type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" required>
                            @error('jumlah_keluar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_keluar">Tanggal Keluar</label>
                            <input class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                type="date"
                                name="tanggal_keluar"
                                id="tanggal_keluar"
                                value="{{ old('tanggal_keluar', date('Y-m-d')) }}"
                                required>
                            @error('tanggal_keluar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
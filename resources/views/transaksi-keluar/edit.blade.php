@extends ('layouts.master')

@section('title', 'Edit Transaksi Keluar')
@section('content_title', 'Edit Transaksi Keluar')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Transaksi Keluar</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaksi-keluar.update', $transaksi_keluar->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="barang_it_id">Barang Yang Keluar</label>
                            <!-- <select class="form-control @error('barang_it_id') is-invalid @enderror" id="barang_it_id" name="barang_it_id" required>
                                <option value=""> -- Pilih Barang -- </option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}" {{ old('barang_it_id', $transaksi_keluar->barang_it_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                </option>
                                @endforeach
                            </select> -->
                            <select class="form-control @error('barang_it_id') is-invalid @enderror" id="barang_it_id" name="barang_it_id" required>
                                <option value=""> -- Pilih Barang -- </option>
                                @foreach($barangs as $item)
                                    {{-- Tampilkan barang jika: 
                                        1. Kondisinya baru atau bekas
                                        2. Atau barang tersebut adalah barang yang sedang dipakai di transaksi ini --}}
                                    @if(
                                        in_array($item->kondisi, ['baru', 'bekas']) || 
                                        $item->id == $transaksi_keluar->barang_it_id
                                    )
                                        <option value="{{ $item->id }}" 
                                            {{ old('barang_it_id', $transaksi_keluar->barang_it_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                            @if($item->kondisi == 'rusak')
                                                - [KONDISI RUSAK - data awal]
                                            @endif
                                        </option>
                                    @endif
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
                            <input type="number" class="form-control @error('jumlah_keluar') is-invalid @enderror" id="jumlah_keluar" name="jumlah_keluar" required value="{{ old('jumlah_keluar', $transaksi_keluar->jumlah_keluar) }}">
                            @error('jumlah_keluar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="tanggal_keluar">Tanggal Keluar</label>
                            <input type="date" class="form-control @error('tanggal_keluar') is-invalid @enderror" id="tanggal_keluar" name="tanggal_keluar" required value="{{ old('tanggal_keluar', $transaksi_keluar->tanggal_keluar) }}">
                            @error('tanggal_keluar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $transaksi_keluar->keterangan) }}</textarea>
                            @error('keterangan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-warning">Update</button>
                            <a href="{{ route('transaksi-keluar.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends ('layouts.master')

@section('title', 'Edit Barang Rab')
@section('content_title', 'Edit Barang Rab')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Edit Barang RAB</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('rab.details.update', $rab_detail->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_barang_diajukan">Nama Barang Diajukan</label>
                            <input type="text" class="form-control @error('nama_barang_diajukan') is-invalid
                            @enderror" id="nama_barang_diajukan" name="nama_barang_diajukan" value="{{ old('nama_barang_diajukan', $rab_detail->nama_barang_diajukan)}}" required>
                            @error('nama_barang_diajukan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control @error('jumlah') is-invalid
                            @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $rab_detail->jumlah)}}" required>
                            @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="perkiraan_harga_satuan">Harga Satuan</label>
                            <input type="number" class="form-control @error('perkiraan_harga_satuan') is-invalid
                            @enderror" id="perkiraan_harga_satuan" name="perkiraan_harga_satuan" value="{{ old('perkiraan_harga_satuan', $rab_detail->perkiraan_harga_satuan)}}" required>
                            @error('perkiraan_harga_satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ongkir">Ongkir</label>
                            <input type="number" class="form-control @error('ongkir') is-invalid
                            @enderror" id="ongkir" name="ongkir" value="{{ old('ongkir', $rab_detail->ongkir)}}" >
                            @error('ongkir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="asuransi">Asuransi</label>
                            <input type="number" class="form-control @error('asuransi') is-invalid
                            @enderror" id="asuransi" name="asuransi" value="{{ old('asuransi', $rab_detail->asuransi)}}" >
                            @error('asuransi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-warning">Update</button>
                            <a href="{{ route('rab.show', $rab_detail->rab_id) }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends ('layouts.master')

@section('title', 'Pengajuan RAB')
@section('content_title', 'Tambah RAB Baru')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulir Pengajuan RAB</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('rab.store') }}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="judul">Judul RAB</label>
                            <input type="text" class="form-control" id="judul" name="judul" required @error('judul') is-invalid @enderror value="{{ old('judul') }}">
                            @error('judul')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_dibuat">Tanggal Pengajuan</label>
                            <input class="form-control @error('tanggal_dibuat') is-invalid @enderror" type="date" name="tanggal_dibuat" id="tanggal_dibuat" value="{{ old('tanggal_dibuat', date('Y-m-d')) }}" required>
                            @error('tanggal_dibuat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('rab.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
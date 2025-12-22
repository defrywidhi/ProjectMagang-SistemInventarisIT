@extends ('layouts.master')

@section('title', 'Edit RAB')
@section('content_title', 'Edit RAB')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit RAB</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('rab.update', $rab->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="judul">Judul RAB</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" required value="{{ old('judul', $rab->judul) }}">
                            @error('judul')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal_dibuat">Tanggal Pengajuan</label>
                            <input value="{{ old('tanggal_dibuat', $rab->tanggal_dibuat) }}" class="form-control @error('tanggal_dibuat') is-invalid @enderror" type="date" name="tanggal_dibuat" id="tanggal_dibuat" required>
                            @error('tanggal_dibuat')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-warning">Simpan</button>
                            <a href="{{ route('rab.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
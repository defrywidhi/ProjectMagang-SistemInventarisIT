@extends('layouts.master')
@section('title', 'Edit Sesi Stok Opname')
@section('content_title', 'Edit Sesi Stok Opname')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Sesi</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('stok-opname.update', $stokOpname->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- PENTING --}}
                        <div class="form-group mb-3">
                            <label for="tanggal_opname">Tanggal Stok Opname</label>
                            <input type="date" class="form-control @error('tanggal_opname') is-invalid @enderror"
                                id="tanggal_opname" name="tanggal_opname"
                                value="{{ old('tanggal_opname', $stokOpname->tanggal_opname) }}" required>
                            @error('tanggal_opname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="catatan">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $stokOpname->catatan) }}</textarea>
                            @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Sesi</button>
                            <a href="{{ route('stok-opname.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
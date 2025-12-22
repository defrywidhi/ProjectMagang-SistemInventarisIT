@extends('layouts.master')

@section('title', 'Edit Supplier')
@section('content_title', 'Edit Supplier')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Formulir Edit Supplier</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier.update', $supplier->id) }}" method="post">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="nama_supplier">Nama Supplier</label>
                            <input value="{{ old('nama_supplier', $supplier -> nama_supplier) }}" class="form-control" type="text" name="nama_supplier" id="nama_supplier" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input value="{{ old('alamat', $supplier-> alamat) }}" class="form-control" type="text" name="alamat" id="alamat" required>
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input value="{{ old('nomor_telepon', $supplier-> nomor_telepon) }}" class="form-control" type="text" name="nomor_telepon" id="nomor_telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input value="{{ old('email', $supplier-> email) }}" class="form-control" type="text" name="email" id="email">
                        </div>
                        <div class="mt-5 mb-3">
                            <button type="submit" class="btn btn-warning">Kirim</button>
                            <a href="{{ route('supplier.index') }}" class="btn btn-secondary mx-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @endsection
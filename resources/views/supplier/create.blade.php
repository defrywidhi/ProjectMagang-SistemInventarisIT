@extends ('layouts.master')

@section('content')

<div class="container">
    <div class="card" style="width: 50%">
    <div class="card-header"><h1>Tambah data Supplier</h1></div>
    <div class="card-body">
        <form action="{{ route('supplier.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <input class="form-control" type="text" name="nama_supplier" id="nama_supplier" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input class="form-control" type="text" name="alamat" id="alamat" required>
            </div>
            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input class="form-control" type="text" name="nomor_telepon" id="nomor_telepon" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email">
            </div>
            <div class="mt-5 mb-3">
                <button type="submit" class="btn btn-primary">Kirim</button>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary mx-2">Batal</a>
            </div>
        </form>
    </div>
    </div>
</div>
@endsection
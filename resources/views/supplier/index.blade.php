@extends ('layouts.master')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container">

    <h1>Tabel Supplier</h1>

    <div class="mb-3">
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">Tambah Supplier</a>
    </div>

    <div class="">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Nomor Telepon</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $suppliers as $item )
                    <tr>
                        <td>{{ $item -> nama_supplier }}</td>
                        <td>{{ $item -> alamat }}</td>
                        <td>{{ $item -> nomor_telepon }}</td>
                        <td>{{ $item -> email }}</td>
                        <td>
                            <div class="">
                            <a href="{{ route('supplier.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <form class="d-inline-block" action="{{ route('supplier.destroy', $item->id) }}"method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data ini?')">Hapus</button>
                            </form>
                            </div>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
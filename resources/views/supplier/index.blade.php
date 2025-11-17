@extends ('layouts.master')

@section('content_title', 'Supplier')
@section('title', 'Supplier')

@section('content')

<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <a href="{{ route('supplier.create') }}" class="btn btn-primary">Tambah Supplier</a>
        </div>
        <div class="card-body p-0 text-center table-responsive">

            <!-- Success Alert - Bootstrap 5 Version -->
            @if(session('success'))
            <div class="alert alert-success fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Alert - Bootstrap 5 Version -->
            @if(session('error'))
            <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <table id="tabel-supplier" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $suppliers as $item )
                        <tr>
                            <td>{{ $item -> nama_supplier }}</td>
                            <td>{{ $item -> alamat }}</td>
                            <td>{{ $item -> nomor_telepon }}</td>
                            <td>{{ $item -> email ?? '_'}}</td>
                            <td class="text-center p-0">
                                <div class="">
                                <a href="{{ route('supplier.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form class="d-inline-block" action="{{ route('supplier.destroy', $item->id) }}"method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin menghapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                </div>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data supplier</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabel-supplier').DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
        });
    });
</script>
@endpush
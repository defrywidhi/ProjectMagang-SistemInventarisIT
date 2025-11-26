@extends('layouts.master')

@section('content_title', 'Stok Opname')
@section('title', 'Stok Opname')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <a href="{{ route('stok-opname.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tambah Stok Opname Baru</a>
        </div>
        <div class="card-body p-0 text-center table-responsive">

            <!-- Success Alert - Bootstrap 5 Version -->
            @if(session('success'))
            <div class="alert alert-success fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Alert - Bootstrap 5 Version -->
            @if(session('error'))
            <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <table id="tabel-stok-opname" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Auditor</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $stokOpnames as $item )
                    <tr>
                        <td>{{ $item->tanggal_opname }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->auditor->name }}</td>
                        <td>{{ $item->catatan ?? '_'}}</td>
                        <td class="text-center p-0">
                            <a href="{{ route('stok-opname.show', $item->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-info-circle"></i> {{ $item->status == 'Pending' ? 'Lanjutkan' : 'Detail' }}
                            </a>

                            @if($item->status == 'Pending')
                            <a href="{{ route('stok-opname.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('stok-opname.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus sesi ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data stok opname</td>
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
        $('#tabel-stok-opname').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            
            // --- INI PENGATURAN POSISINYA (DOM) ---
            // Penjelasan kode:
            // <'row' ...> : Membuat baris baru (seperti <div class="row">)
            // <'col-...' ...> : Membuat kolom (seperti <div class="col-md-6">)
            // l : Length (Show entries)
            // f : Filter (Search)
            // t : Table (Tabel itu sendiri)
            // i : Info (Showing 1 to 10...)
            // p : Pagination (Previous - Next)
            
            "dom":  
                "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });
    });
</script>
@endpush
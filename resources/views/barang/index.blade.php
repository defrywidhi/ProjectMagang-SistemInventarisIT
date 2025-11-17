@extends('layouts.master')

@section('content_title', 'Barang')
@section('title', 'Barang')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <a href="{{ route('barang.create') }}" class="btn btn-primary">Tambah Barang</a>
            <a href="{{ route('barang.exportExcel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Export ke Excel
            </a>
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

            <table id="tabel-barang" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Kategori</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Serial Number</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Stok Minimun</th>
                        <th>Kondisi</th>
                        <th>Lokasi Penyimpanan</th>
                        <th>Gambar Barang</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ($barangs as $item )
                    <tr>
                        <td>{{ $item -> kategori -> nama_kategori }}</td>
                        <td>{{ $item -> nama_barang }}</td>
                        <td>{{ $item -> merk ?? '_'}}</td>
                        <td>{{ $item -> serial_number ?? '_'}}</td>
                        <td>{{ $item -> deskripsi ?? '_'}}</td>
                        <td>{{ $item -> stok }}</td>
                        <td>{{ $item -> stok_minimum }}</td>
                        <td>{{ $item -> kondisi }}</td>
                        <td>{{ $item -> lokasi_penyimpanan ?? '_'}}</td>
                        <td>
                            @if($item->gambar_barang)
                            <img src="{{ asset('storage/gambar_barang/'. $item->gambar_barang) }}" alt="Gambar Barang" class="object-fit-cover" style="width: 100px; height: 100px;">
                            @else
                            Tidak Ada Gambar
                            @endif
                        </td>
                        <td class="text-center p-0">
                            <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('barang.destroy', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('yakin menghapus file ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">Tidak ada data barang.</td>
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
        $('#tabel-barang').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
        });
    });
</script>
@endpush
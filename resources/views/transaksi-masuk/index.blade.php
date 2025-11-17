@extends('layouts.master')

@section('content_title', 'Transaksi Masuk')
@section('title', 'Transaksi Masuk')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <a href="{{ route('transaksi-masuk.create') }}" class="btn btn-primary">Tambah Transaksi Masuk</a>
            <a href="{{ route('transaksi-masuk.exportExcel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Laporan Pengadaan
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

            <table id="tabel-transaksi-masuk" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Tanggal Masuk</th>
                        <th>Harga Satuan</th>
                        <th>Keterangan</th>
                        <th>User Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $transaksis_masuk as $item )
                    <tr>
                        <td>{{ $item->barang_it->nama_barang }}</td>
                        <td>{{ $item->supplier->nama_supplier }}</td>
                        <td>{{ $item->jumlah_masuk }}</td>
                        <td>{{ $item->tanggal_masuk }}</td>
                        <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $item->keterangan ?? '_'}}</td>
                        <td>{{ $item->user->name }}</td>
                        <td class="text-center p-0">
                            <a href="{{ route('transaksi-masuk.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('transaksi-masuk.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data transaksi masuk</td>
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
        $('#tabel-transaksi-masuk').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
        });
    });
</script>
@endpush
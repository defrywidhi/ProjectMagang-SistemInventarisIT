@extends('layouts.master')

@section('content_title', 'Transaksi Keluar')
@section('title','Transaksi Keluar')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <a href="{{ route('transaksi-keluar.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tambah Transaksi Keluar</a>
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

            <table id="tabel-transaksi-keluar" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah Keluar</th>
                        <th>Tanggal Keluar</th>
                        <th>Keterangan</th>
                        <th>User Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $transaksi_keluar as $item )
                    <tr>
                        <td>{{ $item->barang_it->nama_barang }}</td>
                        <td>{{ $item->jumlah_keluar }}</td>
                        <td>{{ $item->tanggal_keluar }}</td>
                        <td>{{ $item->keterangan ?? '_'}}</td>
                        <td>{{ $item->user->name }}</td>
                        <td class="text-center p-0">
                            <a href="{{ route('transaksi-keluar.cetakBuktiKeluar', $item->id) }}" class="btn btn-secondary btn-sm" target="_blank" title="Cetak Bukti">
                                <i class="bi bi-printer"></i>
                            </a>
                            <a href="{{ route('transaksi-keluar.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('transaksi-keluar.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data transaksi keluar</td>
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
        $('#tabel-transaksi-keluar').DataTable({
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

            "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });
    });
</script>
@endpush
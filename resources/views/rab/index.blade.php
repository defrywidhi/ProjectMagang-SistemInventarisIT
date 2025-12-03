@extends('layouts.master')

@section('content_title', 'Daftar RAB')
@section('title', 'RAB')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="">
                <a href="{{ route('rab.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle-fill"></i> Tambah RAB Baru</a>
            </div>
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

            @if(session('success_edit'))
            <div class="alert alert-success fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Berhasil!</strong> {{ session('success_edit') }}
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

            <table id="tabel-rab" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Kode RAB</th>
                        <th>Judul</th>
                        <th>Pengaju</th>
                        <th>Status</th>
                        <th>Tgl Dibuat</th>
                        <th>Tgl ditinjau</th>
                        <th>Ditinjau Oleh</th>
                        <th>Catatan</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $rabs as $item )
                    <tr>
                        <td>{{ $item->kode_rab }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->pengaju->name ?? 'N/A' }}</td>
                        <td>
                            @if ($item->status == 'Draft')
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            @elseif ($item->status == 'Menunggu Approval')
                                <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                            @elseif ($item->status == 'Ditolak')
                                <span class="badge bg-danger">{{ $item->status }}</span>
                            @elseif ($item->status == 'Disetujui')
                                <span class="badge bg-success">{{ $item->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->tanggal_dibuat }}</td>
                        <td>{{ $item->tanggal_disetujui ?? '_' }}</td>
                        <td>{{ $item->penyetuju->name ?? '_' }}</td>
                        <td>{{ $item->catatan_approval ?? '_' }}</td>
                        <td class="text-center p-0">
                            {{-- 1. Tombol Detail (Muncul untuk SEMUA status) --}}
                            <a href="{{ route('rab.show', $item->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-info-circle"></i>
                            </a>

                            {{-- 2. Tombol Edit (HANYA untuk Draft atau Ditolak) --}}
                            @if ($item->status == 'Draft' || $item->status == 'Ditolak')
                            <a href="{{ route('rab.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endif

                            {{-- 3. Tombol Hapus (Draft/Ditolak ATAU (Disetujui DAN Admin)) --}}
                            @if ($item->status == 'Draft' || $item->status == 'Ditolak' || ($item->status == 'Disetujui' && auth()->user()->hasRole('admin')))
                            <form class="d-inline" action="{{ route('rab.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('PERINGATAN: Menghapus RAB ini mungkin akan mempengaruhi data transaksi. Yakin ingin menghapus?');">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data RAB.</td>
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
        $('#tabel-rab').DataTable({
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
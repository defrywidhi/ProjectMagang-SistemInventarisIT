@extends('layouts.master')

@section('title', 'User Management')
@section('content_title', 'User Management')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            {{-- TAMBAHKAN TOMBOL INI --}}
            <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User Baru</a>
        </div>

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
        
        <div class="card-body p-0 text-center table-responsive">
            <table id="tabel-users" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 150px;">Aksi</th> {{-- TAMBAHKAN KOLOM AKSI --}}
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ($users as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->getRoleNames()->first() ?? 'Belum punya role' }}</td>
                        <td>
                            {{-- Kita siapkan tombolnya untuk nanti --}}
                            <a href="{{ route('users.edit', $item->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type.submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data User.</td> {{-- Colspan jadi 4 --}}
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
        $('#tabel-users').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
        });
    });
</script>
@endpush
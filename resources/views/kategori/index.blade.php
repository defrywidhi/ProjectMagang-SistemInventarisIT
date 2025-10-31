@extends ('layouts.master')

@section('content_title', 'Kategori')
@section('title', 'Kategori')

@section('content')
<div class="container">
<div class="card card-outline card-success">
    <div class="card-header">
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
    </div>
    <div class="card-body p-0 text-center table-responsive">
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
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>Nama Kategori</th>
                    <th>Kode Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($kategoris as $item )
                <tr>
                    <td>{{ $item->nama_kategori }}</td>
                    <td>{{ $item->kode_kategori }}</td>
                    <td class="text-center p-0">
                        <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('kategori.destroy', $item->id) }}" method="post" class="d-inline-block">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('apakah yakin data ini akan dihapus?')" >
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">Tidak ada data kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
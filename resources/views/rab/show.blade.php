@extends('layouts.master')

@section('title', 'Detail RAB')
@section('content_title', 'Detail RAB')

@section('content')
<div class="container">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('rab.index') }}" class="btn btn-secondary">Kembali ke Daftar RAB</a>
    </div>

    @if(session('success'))
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

    {{-- Kartu Info Header RAB --}}
    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title">Informasi RAB</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Kode RAB : </strong> {{ $rab->kode_rab }}</p>
                    <p><strong>Judul : </strong> {{ $rab->judul }}</p>
                    <p><strong>Tanggal Dibuat : </strong> {{ $rab->tanggal_dibuat }}</p>
                    <p><strong>Pengaju : </strong> {{ $rab->pengaju->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status : </strong> {{ $rab->status }}</p>
                    <p><strong>Tanggal ditinjau : </strong> {{ $rab->tanggal_disetujui ?? '-' }}</p>
                    <p><strong>Ditinjau Oleh : </strong> {{ $rab->penyetuju->name ?? '-' }}</p>
                    <p><strong>Catatan Approval : </strong> {{ $rab->catatan_approval ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Barang --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rincian Barang Diajukan</h3>
        </div>
        <div class="card-body p-0"> {{-- p-0 agar tabel mepet --}}
            <table class="table table-bordered table-striped"> {{-- Tambah striped --}}
                <thead class="text-center">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Ongkir</th>
                        <th>Asuransi</th>
                        <th>Total Harga</th>

                        {{-- Tombol Edit dan Hapus hanya ditampilkan jika status RAB adalah Draft atau Ditolak --}}
                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody> {{-- Tambahkan tbody --}}
                    @forelse ($rab->details as $detail)
                    <tr>
                        <td>{{ $detail->nama_barang_diajukan }}</td> {{-- Perbaiki pemanggilan nama barang --}}
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->perkiraan_harga_satuan, 0, ',', '.') }}</td> {{-- Format harga --}}
                        <td class="text-end">Rp {{ number_format($detail->ongkir, 0, ',', '.') }}</td> {{-- Format harga --}}
                        <td class="text-end">Rp {{ number_format($detail->asuransi, 0, ',', '.') }}</td> {{-- Format harga --}}
                        <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td> {{-- Format harga --}}

                        {{-- Tombol Edit dan Hapus hanya ditampilkan jika status RAB adalah Draft atau Ditolak --}}
                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <td class="text-center">
                            <a href="{{ route('rab.details.edit', $detail->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('rab.details.destroy', $detail->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $rab->status == 'Draft' ? 7 : 6 }}" class="text-center">Belum ada detail barang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <hr>

            {{-- hanya ditampilkan jika status RAB adalah Draft atau Ditolak --}}
            @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
            {{-- menampilkan pesan RAB ditolak --}}
            @if ($rab->status == 'Ditolak')
            <p class="text-danger ms-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>RAB ini ditolak: {{ $rab->catatan_approval ?? 'Tidak ada catatan.' }}</strong>
            </p>
            @endif

            {{-- tombol untuk mengajukan RAB pertama kali dan ulang jika status adalah Ditolak --}}
            @can('buat rab')
            <form action="{{ route('rab.ajukan', $rab->id) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-success btn-sm ms-2" onclick="return confirm('Apakah Anda yakin ingin mengajukan RAB ini untuk approval? Setelah diajukan, RAB tidak bisa diedit lagi.')">
                    <i class="bi bi-check-circle"></i>
                    {{ $rab->status == 'Ditolak' ? 'Ajukan Ulang' : 'Ajukan RAB' }}
                </button>
            </form>
            @endcan

            {{-- status informasi terkai rab yang sedang di buka --}}
            @if ($rab->status == 'Draft')
            <p class="text-muted d-inline-block ms-2 mt-2">RAB ini masih draft dan dapat diubah.</p>
            @else
            <p class="text-muted d-inline-block ms-2 mt-2">Silahkan revisi ulang RAB.</p>
            @endif

            {{-- informasi ketika rab menunggu approval --}}
            @elseif ($rab->status == 'Menunggu Approval')
            <p class="text-warning ms-2">
                <i class="bi bi-hourglass-split"></i>
                <strong>Rab ini sedang menunggu persetujuan.</strong>
            </p>

            @can('setujui rab')
            <form action="{{ route('rab.approve', $rab->id) }}" method="post" class="d-inline-block ms-2">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin akan menyetujui RAB ini?')">
                    <i class="bi bi-check-circle"></i>Setujui</button>
            </form>

            <button type="button" class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#modalTolakRAB">
                <i class="bi bi-x-lg"></i>Tolak</button>
            @endcan

            @else
            @if ($rab->status == 'Disetujui')
                @can('input barang masuk')
                <a href="{{ route('transaksi-masuk.create', ['rab_id' => $rab->id]) }}" class="btn btn-success ms-2"><i class="bi bi-cart-plus-fill"></i>Catat Pembelian</a>
                @endcan
                @endif
            <p class="text-info m-2">
                <i class="bi bi-info-circle-fill"></i>
                RAB ini sudah diajukan dan : <strong>{{ $rab->status }}</strong>
            </p>
            @endif
        </div>
    </div>


    {{-- Kartu Form Tambah Detail Barang --}}
    @role('admin')
    @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
    <div class="card card-outline card-success mt-4">
        <div class="card-header">
            <h3 class="card-title">Tambah Detail Barang Baru</h3>
        </div>
        <div class="card-body">
            {{-- Kita akan buat route dan controller untuk ini nanti --}}
            <form action="{{ route('rab.details.store', $rab->id) }}" method="POST">
                @csrf
                {{-- Hidden input untuk rab_id --}}
                <input type="hidden" name="rab_id" value="{{ $rab->id }}">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="nama_barang_diajukan">Nama Barang</label>
                        <input value="{{ old('nama_barang_diajukan') }}" type="text" name="nama_barang_diajukan" class="form-control @error('nama_barang_diajukan') is-invalid
                            @enderror" required>
                        @error('nama_barang_diajukan')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="jumlah">Jumlah</label>
                        <input value="{{ old('jumlah') }}" type="number" name="jumlah" class="form-control @error('jumlah') is-invalid
                            @enderror" required min="1" value="1">
                        @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="perkiraan_harga_satuan">Harga Satuan</label>
                        <input value="{{ old('perkiraan_harga_satuan') }}" type="number" name="perkiraan_harga_satuan" class="form-control @error('perkiraan_harga_satuan') is-invalid
                            @enderror" required min="0" value="0">
                        @error('perkiraan_harga_satuan')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="ongkir">Ongkir</label>
                        <input value="{{ old('ongkir') }}" type="number" name="ongkir" class="form-control @error('ongkir') is-invalid
                            @enderror" min="0" value="0">
                        @error('ongkir')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="asuransi">Asuransi</label>
                        <input value="{{ old('asuransi') }}" type="number" name="asuransi" class="form-control @error('asuransi') is-invalid
                            @enderror" min="0" value="0">
                        @error('asuransi')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 mt-3">
                        <button type="submit" class="btn btn-success">Tambah Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endrole

</div>
@endsection



{{-- Modal (pop up) Tolak RAB --}}
@if ($rab->status == 'Menunggu Approval')
<div class="modal fade" id="modalTolakRAB" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="exampleModalLabel">Tolak Pengajuan RAB</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('rab.reject' , $rab->id) }}" method="post">
                @csrf
                <div class="modal-body">
                    <label for="catatan_approval">Catatan Penolakan</label>
                    <textarea name="catatan_approval" id="catatan_approval" rows="3" class="form-control @error('catatan_approval') is-invalid @enderror" required></textarea>
                    @error('catatan_approval')
                    <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Tolak RAB</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
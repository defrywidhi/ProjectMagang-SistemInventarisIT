@extends('layouts.master')

@section('title', 'Detail RAB')
@section('content_title', 'Detail RAB')

@section('content')
<div class="container">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('rab.index') }}" class="btn btn-secondary">Kembali ke Daftar RAB</a>
    </div>

    @if(session('success_detail'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success_detail') }}
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
                    <p><strong>Tanggal Disetujui : </strong> {{ $rab->tanggal_disetujui ?? '-' }}</p>
                    <p><strong>Disetujui Oleh : </strong> {{ $rab->penyetuju->name ?? '-' }}</p>
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
                        @if ($rab->status == 'Draft')
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
                        @if ($rab->status == 'Draft')
                            <td class="text-center">
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
        </div>
    </div>

    {{-- Kartu Form Tambah Detail Barang (AKAN KITA BUAT LOGIKANYA NANTI) --}}
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
                        <input type="text" name="nama_barang_diajukan" class="form-control @error('nama_barang_diajukan') is-invalid
                            @enderror" required>
                        @error('nama_barang_diajukan')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid
                            @enderror" required min="1" value="1">
                        @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="perkiraan_harga_satuan">Harga Satuan</label>
                        <input type="number" name="perkiraan_harga_satuan" class="form-control @error('perkiraan_harga_satuan') is-invalid
                            @enderror" required min="0" value="0">
                        @error('perkiraan_harga_satuan')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="ongkir">Ongkir</label>
                        <input type="number" name="ongkir" class="form-control @error('ongkir') is-invalid
                            @enderror" min="0" value="0">
                        @error('ongkir')
                        <div class="invalid-feedback">{{ $message }}</div> {{-- Gunakan $message --}}
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="asuransi">Asuransi</label>
                        <input type="number" name="asuransi" class="form-control @error('asuransi') is-invalid
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

</div>
@endsection
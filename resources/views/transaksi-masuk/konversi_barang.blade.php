@extends('layouts.master')

@section('title', 'Konfirmasi Barang Baru')
@section('content_title', 'Konversi Barang RAB ke Master')

@section('content')
<div class="container-fluid">
    
    {{-- ALERT INFO --}}
    <div class="alert alert-warning shadow-sm">
        <h5><i class="bi bi-exclamation-triangle-fill"></i> Perhatian!</h5>
        RAB ini mengandung <strong>{{ $pendingItems->count() }} barang</strong> yang belum terdaftar di Master Gudang.<br>
        Sistem tidak bisa mencatat transaksi sebelum barang-barang ini memiliki <strong>Kategori</strong> dan terdaftar resmi di database.
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang Pending</h3>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-striped table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="25%">Info Barang (Dari RAB)</th>
                        <th width="15%" class="text-center">Preview Foto</th>
                        <th width="55%">Lengkapi Data Master</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingItems as $item)
                    <tr>
                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                        
                        {{-- KOLOM 1: INFO DARI RAB --}}
                        <td class="align-middle">
                            <span class="fw-bold text-primary">{{ $item->nama_barang_custom }}</span>
                            <br>
                            @if($item->keterangan)
                                <small class="text-muted"><i class="bi bi-info-circle"></i> {{ $item->keterangan }}</small>
                            @else
                                <small class="text-muted fst-italic">Tidak ada keterangan</small>
                            @endif
                        </td>

                        {{-- KOLOM 2: FOTO DARI RAB --}}
                        <td class="text-center align-middle">
                            @if($item->foto_custom)
                                <img src="{{ asset('storage/' . $item->foto_custom) }}" 
                                     class="img-thumbnail" 
                                     style="height: 80px; width: 80px; object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>

                        {{-- KOLOM 3: FORM INPUT DATA MASTER --}}
                        <td class="align-middle">
                            <form action="{{ route('transaksi-masuk.store-konversi') }}" method="POST" class="row g-2 align-items-end">
                                @csrf
                                <input type="hidden" name="rab_detail_id" value="{{ $item->id }}">
                                
                                {{-- Pilih Kategori (Wajib) --}}
                                <div class="col-md-5">
                                    <label class="form-label small text-muted mb-1">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($kategoris as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Input Merk (Opsional) --}}
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Merk</label>
                                    <input type="text" name="merk" class="form-control form-control-sm" placeholder="Contoh: Asus">
                                </div>

                                {{-- Input Lokasi (Opsional) --}}
                                <div class="col-md-2">
                                    <label class="form-label small text-muted mb-1">Rak/Lokasi</label>
                                    <input type="text" name="lokasi" class="form-control form-control-sm" placeholder="A-01">
                                </div>

                                {{-- Tombol Simpan --}}
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success btn-sm w-100 shadow-sm" title="Simpan ke Master">
                                        <i class="bi bi-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    * Data akan otomatis hilang dari tabel ini setelah disimpan. Foto dari RAB akan otomatis dicopy ke Master Barang.
                </small>

                {{-- TOMBOL LANJUT --}}
                {{-- Logic: Jika masih ada item pending, tombol disabled atau alert --}}
                @if($pendingItems->count() > 0)
                    <button class="btn btn-secondary disabled">
                        <i class="bi bi-arrow-right-circle"></i> Selesaikan Daftar Pending Dulu
                    </button>
                @else
                    {{-- Jika list kosong (user refresh halaman setelah selesai semua) --}}
                    <a href="{{ route('transaksi-masuk.create', ['rab_id' => $rabId]) }}" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Semua Beres! Lanjut Transaksi
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
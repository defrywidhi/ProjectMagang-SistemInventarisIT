@extends('layouts.master')

@section('title', 'Detail Stok Opname')
@section('content_title', 'Lembar Kerja Stok Opname')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- KOLOM KIRI: INFO SESI --}}
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h5 class="text-center fw-bold">{{ $stokOpname->kode_opname }}</h5>
                    <p class="text-muted text-center mb-1">
                        {{ \Carbon\Carbon::parse($stokOpname->tanggal_opname)->format('d F Y') }}
                    </p>
                    
                    <div class="text-center mb-3">
                        @if ($stokOpname->status == 'Pending')
                            <span class="badge bg-warning text-dark">Sedang Proses</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Auditor</b> <span>{{ $stokOpname->auditor->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Metode</b> <span>{{ $stokOpname->metode }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Total Item</b> <span class="badge bg-info">{{ $stokOpname->details->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Belum Cek</b> 
                            <span class="badge bg-secondary" id="count-belum">{{ $stokOpname->details->where('status_fisik', 'Belum Cek')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Selisih</b> 
                            <span class="badge bg-danger" id="count-selisih">{{ $stokOpname->details->where('status_fisik', 'Selisih')->count() }}</span>
                        </li>
                    </ul>

                    {{-- TOMBOL AKSI --}}
                    @if($stokOpname->status == 'Pending')
                        <form action="{{ route('stok-opname.selesaikan', $stokOpname->id) }}" method="POST" id="formSelesaikan">
                            @csrf
                            <button type="button" class="btn btn-success btn-block w-100 mb-2" onclick="konfirmasiSelesai()">
                                <i class="bi bi-check-circle"></i> Selesaikan SO
                            </button>
                        </form>
                    @else
                        <a href="{{ route('stok-opname.cetak', $stokOpname->id) }}" class="btn btn-secondary btn-block w-100 mb-2" target="_blank">
                            <i class="bi bi-printer"></i> Cetak Laporan
                        </a>
                    @endif
                    
                    <a href="{{ route('stok-opname.index') }}" class="btn btn-outline-secondary btn-block w-100">Kembali</a>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Catatan</h3>
                </div>
                <div class="card-body">
                    {{ $stokOpname->catatan ?? '-' }}
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL CEK --}}
        <div class="col-md-9">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-list-check"></i> Lembar Pengecekan Barang</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="25%">Nama Barang</th>
                                <th width="10%">Sistem</th>
                                <th width="20%">Status Cek</th>
                                <th width="15%">Fisik</th>
                                <th width="10%">Selisih</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokOpname->details as $detail)
                            {{-- Tentukan Warna Baris --}}
                            @php
                                $rowClass = '';
                                if($detail->status_fisik == 'Sesuai') $rowClass = 'table-success';
                                if($detail->status_fisik == 'Selisih') $rowClass = 'table-danger';
                            @endphp

                            <tr id="row-{{ $detail->id }}" class="{{ $rowClass }}">
                                <td>
                                    <strong>{{ $detail->barangIt->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ $detail->barangIt->merk }} - {{ $detail->barangIt->kondisi }}</small>
                                </td>
                                
                                <td class="text-center fw-bold bg-light" id="sistem-{{ $detail->id }}">
                                    {{ $detail->stok_sistem }}
                                </td>

                                <td class="text-center">
                                    @if($stokOpname->status == 'Pending')
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check btn-status" name="status_{{ $detail->id }}" 
                                                id="sesuai_{{ $detail->id }}" value="Sesuai" data-id="{{ $detail->id }}"
                                                {{ $detail->status_fisik == 'Sesuai' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success btn-sm" for="sesuai_{{ $detail->id }}">Sesuai</label>

                                            <input type="radio" class="btn-check btn-status" name="status_{{ $detail->id }}" 
                                                id="selisih_{{ $detail->id }}" value="Selisih" data-id="{{ $detail->id }}"
                                                {{ $detail->status_fisik == 'Selisih' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger btn-sm" for="selisih_{{ $detail->id }}">Beda</label>
                                        </div>
                                    @else
                                        {{-- Tampilan Readonly kalau Selesai --}}
                                        @if($detail->status_fisik == 'Sesuai')
                                            <span class="badge bg-success">Sesuai</span>
                                        @elseif($detail->status_fisik == 'Selisih')
                                            <span class="badge bg-danger">Selisih</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Cek</span>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <input type="number" class="form-control text-center input-fisik" 
                                        id="fisik-{{ $detail->id }}" data-id="{{ $detail->id }}"
                                        value="{{ $detail->stok_fisik }}" 
                                        {{ $stokOpname->status != 'Pending' ? 'disabled' : '' }}
                                        {{ $detail->status_fisik == 'Sesuai' ? 'readonly' : '' }}>
                                </td>

                                <td class="text-center fw-bold" id="selisih-text-{{ $detail->id }}">
                                    {{ $detail->selisih != 0 ? $detail->selisih : '-' }}
                                </td>

                                <td>
                                    <input type="text" class="form-control input-ket" 
                                        id="ket-{{ $detail->id }}" data-id="{{ $detail->id }}"
                                        value="{{ $detail->keterangan_item }}" placeholder="..."
                                        {{ $stokOpname->status != 'Pending' ? 'disabled' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const baseUrl = "{{ url('stok-opname/update-item') }}";

    $(document).ready(function() {
        
        // 1. EVENT: KLIK RADIO BUTTON (SESUAI / BEDA)
        $('.btn-status').change(function() {
            let id = $(this).data('id');
            let status = $(this).val();
            let stokSistem = parseInt($(`#sistem-${id}`).text());
            
            // UI Change
            $('#row-' + id).removeClass('table-success table-danger');

            if (status === 'Sesuai') {
                $('#row-' + id).addClass('table-success');
                $('#fisik-' + id).val(stokSistem).prop('readonly', true); // Auto isi & Kunci
            } else {
                $('#row-' + id).addClass('table-danger');
                $('#fisik-' + id).prop('readonly', false).focus(); // Buka kunci & fokus
                if($('#fisik-' + id).val() == stokSistem) $('#fisik-' + id).val(''); // Kosongkan biar diisi
            }

            // Simpan Otomatis
            saveItem(id);
        });

        // 2. EVENT: INPUT ANGKA FISIK / KETERANGAN
        // Pakai 'change' biar gak spam request tiap ketik. Request dikirim pas enter / pindah kolom.
        $('.input-fisik, .input-ket').change(function() {
            let id = $(this).data('id');
            saveItem(id);
        });

        // FUNGSI SIMPAN AJAX
        function saveItem(id) {
            let status = $(`input[name="status_${id}"]:checked`).val();
            let fisik = $(`#fisik-${id}`).val();
            let ket = $(`#ket-${id}`).val();

            // Validasi sederhana
            if (!status) return; // Belum pilih status, jangan simpan
            if (fisik === '') return; // Fisik kosong, jangan simpan

            $.ajax({
                url: `${baseUrl}/${id}`,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status_fisik: status,
                    stok_fisik: fisik,
                    keterangan: ket
                },
                success: function(response) {
                    // Update tampilan selisih
                    let selisih = response.selisih;
                    let textSelisih = selisih > 0 ? `+${selisih}` : (selisih == 0 ? '-' : selisih);
                    $(`#selisih-text-${id}`).text(textSelisih);
                    
                    // Optional: Toast notif kecil "Tersimpan"
                    // console.log('Saved item ' + id);
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal menyimpan data item ini.', 'error');
                }
            });
        }
    });

    // KONFIRMASI SELESAI
    function konfirmasiSelesai() {
        // Cek apakah masih ada yg belum dicek (Radio button belum kepilih)
        // Kita hitung jumlah radio 'checked' dibagi jumlah baris?
        // Cara simpel: Cek badge "Belum Cek" di sidebar (opsional).
        // Biar Controller yang validasi akhir.

        Swal.fire({
            title: 'Selesaikan Stok Opname?',
            text: "Pastikan semua barang sudah dicek. Data akan dikunci permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, Selesaikan!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formSelesaikan').submit();
            }
        });
    }

    // Flash Message
    @if(session('success'))
        Swal.fire({icon: 'success', title: 'Berhasil', text: '{{ session('success') }}'});
    @endif
    @if(session('error'))
        Swal.fire({icon: 'error', title: 'Gagal', text: '{{ session('error') }}'});
    @endif
</script>
@endpush
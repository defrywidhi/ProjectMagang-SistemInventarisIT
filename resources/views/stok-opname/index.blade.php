@extends('layouts.master')

@section('content_title', 'Stok Opname')
@section('title', 'Stok Opname')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            {{-- UBAHAN 1: Tombol memanggil Modal, bukan link --}}
            <button type="button" class="btn btn-primary" onclick="openModalCreate()">
                <i class="bi bi-plus-circle-fill"></i> Tambah Stok Opname Baru
            </button>
        </div>
        <div class="card-body p-0 text-center table-responsive">

            <table id="tabel-stok-opname" class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>Kode SO</th> {{-- Kolom Baru --}}
                        <th>Tanggal</th>
                        <th>Metode</th>  {{-- Kolom Baru --}}
                        <th>Status</th>
                        <th>Auditor</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $stokOpnames as $item )
                    <tr>
                        <td>
                            <span class="badge bg-secondary" style="font-family: monospace; font-size: 0.9em;">
                                {{ $item->kode_opname ?? '-' }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d-m-Y') }}</td>
                        <td>
                            @if($item->metode == 'Random')
                                <span class="badge bg-info text-dark"><i class="bi bi-shuffle"></i> Random</span>
                            @else
                                <span class="badge bg-primary"><i class="bi bi-list-check"></i> Full</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->status == 'Pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Proses</span>
                            @elseif ($item->status == 'Selesai')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                            @else
                                <span class="badge bg-danger">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>{{ $item->auditor->name }}</td>
                        <td>{{ $item->catatan ?? '-'}}</td>
                        <td class="text-center p-1">
                            {{-- Tombol Detail / Lanjutkan --}}
                            <a href="{{ route('stok-opname.show', $item->id) }}" class="btn btn-info btn-sm text-white" title="{{ $item->status == 'Pending' ? 'Lanjutkan Cek' : 'Lihat Detail' }}">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if($item->status == 'Pending')
                                <button class="btn btn-warning btn-sm btn-edit"
                                    data-id="{{ $item->id }}"
                                    data-tanggal="{{ $item->tanggal_opname }}"
                                    data-catatan="{{ $item->catatan }}"
                                    data-url="{{ route('stok-opname.update', $item->id) }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endif

                            <form class="d-inline-block form-delete" action="{{ route('stok-opname.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" title="Hapus Sesi">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data stok opname</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL CREATE STOK OPNAME --}}
<div class="modal fade" id="modalCreateSO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-clipboard-data"></i> Buat Sesi Stok Opname</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCreateSO">
                @csrf
                <div class="modal-body">
                    
                    {{-- INFO STATISTIK BARANG (DIPERBAIKI) --}}
                    <div class="row mb-3 text-center g-2">
                        <div class="col-4">
                            <div class="border rounded p-2 bg-light">
                                <small class="d-block text-muted">Total Aset</small>
                                <strong class="h5">{{ $totalBarang }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2 bg-warning bg-opacity-10 text-warning">
                                <small class="d-block">Cooldown 1 Bln</small>
                                <strong class="h5">{{ $barangCooldown }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2 bg-success bg-opacity-10 text-success">
                                <small class="d-block">Siap Cek</small>
                                <strong class="h5">{{ $barangSiapOpname }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- ALERT JIKA SEMUA BARANG KENA COOLDOWN --}}
                    @if($barangSiapOpname <= 0)
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i> <strong>Tidak ada barang yang bisa dicek!</strong><br>
                            Semua barang aktif sudah di-opname dalam 30 hari terakhir.
                        </div>
                    @else
                        <div class="alert alert-info py-2 mb-3" style="font-size: 0.9em;">
                            <i class="bi bi-info-circle"></i> Sistem otomatis menyembunyikan {{ $barangCooldown }} barang yang baru saja dicek bulan ini agar pemeriksaan lebih efisien.
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label class="fw-bold">Tanggal Opname</label>
                        <input type="date" name="tanggal_opname" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- PILIHAN METODE --}}
                    <div class="form-group mb-3">
                        <label class="fw-bold d-block mb-2">Metode Pengecekan</label>
                        
                        {{-- DISABLE INPUT JIKA TIDAK ADA BARANG --}}
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="metode" id="metodeFull" value="Full" checked 
                                {{ $barangSiapOpname <= 0 ? 'disabled' : '' }}>
                            <label class="form-check-label" for="metodeFull">
                                <span class="badge bg-primary">Full Audit</span> 
                                (Cek {{ $barangSiapOpname }} Barang)
                            </label>
                        </div>
                        
                        <div class="form-check form-check-inline mt-2 mt-sm-0">
                            <input class="form-check-input" type="radio" name="metode" id="metodeRandom" value="Random"
                                {{ $barangSiapOpname <= 0 ? 'disabled' : '' }}>
                            <label class="form-check-label" for="metodeRandom">
                                <span class="badge bg-info text-dark">Random Sampling</span> (Cek Acak)
                            </label>
                        </div>
                    </div>

                    {{-- INPUT JUMLAH SAMPEL (Hanya muncul jika Random) --}}
                    <div class="form-group mb-3 p-3 bg-light border rounded" id="divSampel" style="display: none;">
                        <label class="fw-bold text-success">Jumlah Sampel Acak</label>
                        {{-- MAX DISET SESUAI BARANG SIAP --}}
                        <input type="number" name="jumlah_sampel" class="form-control" 
                               min="1" max="{{ $barangSiapOpname }}" 
                               placeholder="Maksimal {{ $barangSiapOpname }} barang">
                        
                        <small class="text-muted">
                            Hanya bisa mengambil maksimal <strong>{{ $barangSiapOpname }}</strong> sampel dari barang yang tersedia (Ready).
                        </small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Audit Tahunan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- TOMBOL MATI KALAU GAK ADA BARANG --}}
                    @if($barangSiapOpname > 0)
                        <button type="submit" class="btn btn-success" id="btnSimpan">Mulai Sesi Opname</button>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>Tidak Ada Barang</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT HEADER SO --}}
<div class="modal fade" id="modalEditSO" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Informasi SO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditSO">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_url">
                
                <div class="modal-body">
                    <div class="alert alert-warning py-2" style="font-size: 0.9em">
                        <i class="bi bi-exclamation-triangle"></i> Metode & Barang tidak bisa diubah, hanya Tanggal & Catatan.
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Tanggal Opname</label>
                        <input type="date" name="tanggal_opname" id="edit_tanggal" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Catatan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdate">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
            
        $('input[name="metode"]').change(function() {
            if ($(this).val() === 'Random') {
                $('#divSampel').slideDown();
                $('input[name="jumlah_sampel"]').prop('required', true);
            } else {
                $('#divSampel').slideUp();
                $('input[name="jumlah_sampel"]').prop('required', false).val('');
            }
        });
        
        $('#formCreateSO').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            
            $('#btnSimpan').text('Memproses...').attr('disabled', true);
            $('.form-control').removeClass('is-invalid');
            
            $.ajax({
                url: "{{ route('stok-opname.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#modalCreateSO').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload()); // Reload biar masuk ke tabel/redirect
                },
                error: function(xhr) {
                    $('#btnSimpan').text('Mulai Sesi Opname').attr('disabled', false);
                    if(xhr.status === 422) { // Error Validasi
                        Swal.fire('Gagal', 'Periksa kembali inputan Anda.', 'warning');
                    } else {
                        Swal.fire('Error', xhr.responseJSON.message || 'Terjadi kesalahan sistem', 'error');
                    }
                }
            });
        });
        
        $('.btn-delete-confirm').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus sesi ini?',
                text: "Data hasil cek yang sudah diinput juga akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            let tanggal = $(this).data('tanggal'); // Format: YYYY-MM-DD
            let catatan = $(this).data('catatan');
            let url = $(this).data('url');

            $('#edit_url').val(url);
            $('#edit_tanggal').val(tanggal);
            $('#edit_catatan').val(catatan);
            
            $('#modalEditSO').modal('show');
        });

        $('#formEditSO').on('submit', function(e) {
            e.preventDefault();
            let url = $('#edit_url').val();
            let formData = $(this).serialize();

            $('#btnUpdate').text('Menyimpan...').attr('disabled', true);

            $.ajax({
                url: url,
                type: "POST", // Method spoofing PUT ada di form
                data: formData,
                success: function(response) {
                    $('#modalEditSO').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    $('#btnUpdate').text('Simpan Perubahan').attr('disabled', false);
                    Swal.fire('Error', 'Gagal update data', 'error');
                }
            });
        });

        $('#tabel-stok-opname').DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "order": [[ 1, "desc" ]], // Urutkan berdasarkan tanggal terbaru
            "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });

    });
    
    // Helper Modal
    window.openModalCreate = function() {
        $('#formCreateSO')[0].reset();
        $('#divSampel').hide(); // Sembunyikan sampel saat buka awal
        $('#modalCreateSO').modal('show');
    }
    
    // Session Flash
    @if(session('success'))
    Swal.fire({icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false});
    @endif
    @if(session('error'))
    Swal.fire({icon: 'error', title: 'Gagal', text: '{{ session('error') }}'});
    @endif
</script>
@endpush
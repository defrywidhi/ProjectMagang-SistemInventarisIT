@extends('layouts.master')

@section('content_title', 'Daftar RAB')
@section('title', 'RAB')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            {{-- TOMBOL TAMBAH (Panggil Modal) --}}
            <button type="button" class="btn btn-primary" onclick="openModalCreate()">
                <i class="bi bi-plus-circle-fill"></i> Tambah RAB Baru
            </button>
        </div>
        <div class="card-body p-0 text-center table-responsive">

            <table id="tabel-rab" class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>Kode RAB</th>
                        <th>Judul</th>
                        <th>Pengaju</th>
                        <th>Status</th>
                        <th>Tgl Dibuat</th>
                        <th>Finalisasi</th>
                        <th>Catatan</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $rabs as $item )
                    <tr>
                        <td>
                            <span class="badge bg-secondary" style="font-family: monospace; font-size: 0.9em;">
                                {{ $item->kode_rab }}
                            </span>
                        </td>
                        <td class="text-start fw-bold">{{ $item->judul }}</td>
                        <td>{{ $item->pengaju->name ?? 'User Terhapus' }}</td>
                        <td>
                            @php
                                $warna = match($item->status) {
                                    'Draft' => 'secondary',
                                    'Menunggu Approval' => 'warning text-dark',
                                    'Disetujui' => 'success',
                                    'Ditolak' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $warna }}">{{ $item->status }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_dibuat)->format('d-m-Y') }}</td>
                        <td>
                            @if($item->status == 'Disetujui')
                                {{-- Jika Selesai, tampilkan info Direktur --}}
                                <small class="d-block fw-bold">{{ $item->direktur->name ?? '-' }}</small>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->direktur_at)->format('d-m-Y') }}</small>
                            @elseif($item->status == 'Menunggu Direktur')
                                {{-- Info bahwa Manajer sudah ACC --}}
                                <small class="text-info"><i class="bi bi-check"></i> Manajer ACC</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item->catatan_approval ?? '-' }}</td>
                        <td class="p-1">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- 1. Tombol Detail (Selalu Muncul) --}}
                                <a href="{{ route('rab.show', $item->id) }}" class="btn btn-info btn-sm text-white" title="Detail Items">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- 2. Tombol Edit (HANYA Draft / Ditolak) --}}
                                @if ($item->status == 'Draft' || $item->status == 'Ditolak')
                                    <button class="btn btn-warning btn-sm btn-edit" 
                                        data-url-edit="{{ route('rab.edit', $item->id) }}"
                                        data-url-update="{{ route('rab.update', $item->id) }}"
                                        title="Edit Judul/Tanggal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                @endif

                                {{-- 3. Tombol Hapus (Logic: Draft/Ditolak ATAU Admin) --}}
                                {{-- Admin boleh hapus status apapun (sesuai controller destroy) --}}
                                @if ($item->status == 'Draft' || $item->status == 'Ditolak' || auth()->user()->hasRole('admin'))
                                    <form action="{{ route('rab.destroy', $item->id) }}" method="POST" class="d-inline-block form-delete">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
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

{{-- MODAL UNIFIED (CREATE & EDIT RAB) --}}
<div class="modal fade" id="modalRab" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Buat RAB Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRab">
                @csrf
                {{-- Field tersembunyi untuk Method Spoofing (PUT saat Edit) --}}
                <input type="hidden" id="method_field" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="alert alert-info py-2" style="font-size: 0.9em">
                        <i class="bi bi-info-circle"></i> Item barang ditambahkan setelah RAB dibuat.
                    </div>

                    <div class="form-group mb-3">
                        <label for="judul" class="form-label fw-bold">Judul Pengajuan</label>
                        <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Pengadaan Laptop Divisi IT" required>
                        <div class="invalid-feedback" id="error-judul"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal_dibuat" class="form-label fw-bold">Tanggal Pengajuan</label>
                        <input type="date" class="form-control" id="tanggal_dibuat" name="tanggal_dibuat" value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="error-tanggal_dibuat"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const storeUrl = "{{ route('rab.store') }}";
    let currentUpdateUrl = "";

    $(document).ready(function() {
        
        // ============================================================
        // 1. FORM SUBMIT (Prioritas Utama - Paling Atas)
        // ============================================================
        $('#formRab').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let url = ($('#method_field').val() === 'POST') ? storeUrl : currentUpdateUrl;

            // Bersihkan error validasi sebelumnya
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#btnSimpan').text('Memproses...').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#modalRab').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    $('#btnSimpan').text('Simpan Data').attr('disabled', false);
                    
                    if(xhr.status === 422) { // Error Validasi Input
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(value[0]);
                        });
                    } else if (xhr.status === 403) { // Error Hak Akses/Status
                        Swal.fire('Gagal!', xhr.responseJSON.message, 'error');
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                }
            });
        });

        // ============================================================
        // 2. TOMBOL EDIT CLICK
        // ============================================================
        $('.btn-edit').click(function() {
            let urlEdit = $(this).data('url-edit');
            currentUpdateUrl = $(this).data('url-update');

            resetForm(); // Bersihkan form dulu
            $('#modalTitle').text('Edit Data RAB');
            $('#method_field').val('PUT'); // Ubah method jadi PUT
            $('#btnSimpan').text('Update Data');

            // Ambil data via AJAX
            $.get(urlEdit, function(data) {
                $('#judul').val(data.judul);
                $('#tanggal_dibuat').val(data.tanggal_dibuat);
                $('#modalRab').modal('show');
            }).fail(function() {
                Swal.fire('Error', 'Gagal mengambil data', 'error');
            });
        });

        // ============================================================
        // 3. TOMBOL HAPUS (SweetAlert Confirm)
        // ============================================================
        $('.btn-delete-confirm').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            
            Swal.fire({
                title: 'Yakin hapus RAB ini?',
                text: "Semua detail barang di dalamnya juga akan terhapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // ============================================================
        // 4. DATATABLES (Paling Bawah - Biar aman)
        // ============================================================
        $('#tabel-rab').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });
    });

    // Helper Functions
    window.openModalCreate = function() {
        resetForm();
        $('#modalRab').modal('show');
    }

    function resetForm() {
        $('#formRab')[0].reset();
        $('#modalTitle').text('Buat RAB Baru');
        $('#method_field').val('POST');
        $('#btnSimpan').text('Simpan Data');
        $('.form-control').removeClass('is-invalid');
        // Set tanggal hari ini default
        document.getElementById('tanggal_dibuat').valueAsDate = new Date();
    }

    // Menangkap Session Flash (untuk Notifikasi Redirect dari Controller lain)
    @if(session('success'))
        Swal.fire({icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false});
    @endif
    @if(session('success_edit'))
        Swal.fire({icon: 'success', title: 'Berhasil!', text: '{{ session('success_edit') }}', timer: 2000, showConfirmButton: false});
    @endif
    @if(session('error'))
        Swal.fire({icon: 'error', title: 'Gagal!', text: '{{ session('error') }}'});
    @endif
</script>
@endpush
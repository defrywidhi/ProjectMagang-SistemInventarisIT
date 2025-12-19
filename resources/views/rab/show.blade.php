@extends('layouts.master')

@section('title', 'Detail RAB')
@section('content_title', 'Detail RAB')

@section('content')
<div class="container">
    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('rab.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar RAB
        </a>
    </div>

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
                    <p><strong>Tanggal Dibuat : </strong> {{ \Carbon\Carbon::parse($rab->tanggal_dibuat)->format('d-m-Y') }}</p>
                    <p><strong>Pengaju : </strong> {{ $rab->pengaju->name ?? 'N/A' }}</p>
                    <p><strong>Catatan Approval : </strong> {{ $rab->catatan_approval ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status : </strong> 
                        @php
                            $badgeColor = match($rab->status) {
                                'Draft' => 'secondary',
                                'Menunggu Approval' => 'warning text-dark',
                                'Disetujui' => 'success',
                                'Ditolak' => 'danger',
                                default => 'light'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }}">{{ $rab->status }}</span>
                    </p>
                    <hr class="my-2">
                    <div class="mb-2">
                        <strong><i class="bi bi-person-check"></i> Approval Manajer:</strong><br>
                        @if($rab->manager_id)
                            <span class="text-success">{{ $rab->manager->name }}</span> 
                            <small class="text-muted">({{ \Carbon\Carbon::parse($rab->manager_at)->format('d M Y, H:i') }})</small>
                        @else
                            <span class="text-muted fst-italic">Belum disetujui</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong><i class="bi bi-person-check-fill"></i> Approval Direktur:</strong><br>
                        @if($rab->direktur_id)
                            <span class="text-success fw-bold">{{ $rab->direktur->name }}</span> 
                            <small class="text-muted">({{ \Carbon\Carbon::parse($rab->direktur_at)->format('d M Y, H:i') }})</small>
                        @else
                            <span class="text-muted fst-italic">Belum disetujui</span>
                        @endif
                    </div>
                    @if($rab->catatan_approval)
                        <div class="alert alert-light border mt-2 p-2">
                            <strong>Catatan:</strong> {{ $rab->catatan_approval }}
                        </div>
                    @endif
                   
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu Detail Barang --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rincian Barang Diajukan</h3>
        </div>
        <div class="card-body p-0"> 
            <table id="tabel-detail-rab" class="table table-bordered table-striped"> 
                <thead class="text-center table-secondary">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Ongkir</th>
                        <th>Asuransi</th>
                        <th>Total Harga</th>

                        {{-- Tampilkan Kolom Aksi HANYA jika Draft/Ditolak --}}
                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody> 
                    @forelse ($rab->details as $detail)
                    <tr>
                        <td>{{ $detail->nama_barang_diajukan }}</td> 
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->perkiraan_harga_satuan, 0, ',', '.') }}</td> 
                        <td class="text-end">Rp {{ number_format($detail->ongkir, 0, ',', '.') }}</td> 
                        <td class="text-end">Rp {{ number_format($detail->asuransi, 0, ',', '.') }}</td> 
                        <td class="text-end fw-bold">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td> 

                        {{-- Tombol Edit/Hapus HANYA jika Draft/Ditolak --}}
                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <td class="text-center p-1">
                            {{-- MODAL AJAX EDIT (Fitur Baru) --}}
                            <button class="btn btn-warning btn-sm btn-edit-detail" 
                                data-url-edit="{{ route('rab.details.edit', $detail->id) }}"
                                data-url-update="{{ route('rab.details.update', $detail->id) }}"
                                title="Edit Item">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- HAPUS (Fitur Lama dengan SweetAlert Confirm) --}}
                            <form action="{{ route('rab.details.destroy', $detail->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete-confirm" title="Hapus Item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ ($rab->status == 'Draft' || $rab->status == 'Ditolak') ? 7 : 6 }}" class="text-center">
                            Belum ada detail barang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <hr>
            <div class="px-3 pb-3">
                
                {{-- 1. DRAFT / DITOLAK --}}
                @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                    @if ($rab->status == 'Ditolak')
                        <div class="alert alert-danger">
                            <strong>Ditolak:</strong> {{ $rab->catatan_approval }}
                        </div>
                    @endif

                    @can('buat rab')
                        @if($rab->details->count() > 0)
                            <form action="{{ route('rab.ajukan', $rab->id) }}" method="post" class="d-inline form-ajukan">
                                @csrf
                                {{-- HAPUS onclick confirm biasa, GANTI dengan class btn-confirm-ajukan --}}
                                <button type="button" class="btn btn-success btn-confirm-ajukan">
                                    <i class="bi bi-send"></i> {{ $rab->status == 'Ditolak' ? 'Ajukan Ulang' : 'Ajukan RAB' }}
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Isi barang dulu</button>
                        @endif
                    @endcan
                    <span class="text-muted ms-2"><small>Edit tersedia.</small></span>

                {{-- 2. MENUNGGU MANAGER --}}
                @elseif ($rab->status == 'Menunggu Manager')
                    <div class="alert alert-warning">
                        <i class="bi bi-hourglass-split"></i> Menunggu persetujuan <strong>Manajer</strong>.
                    </div>

                    @role('manager')
                        <button type="button" class="btn btn-primary btn-approve" 
                            data-role="Manajer" 
                            data-url="{{ route('rab.approve.manager', $rab->id) }}">
                            <i class="bi bi-check-circle"></i> Setujui (Manajer)
                        </button>
                        
                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalTolakRAB">
                            <i class="bi bi-x-lg"></i> Tolak
                        </button>
                    @endrole

                    <a href="{{ route('rab.cetakPDF', $rab->id) }}" class="btn btn-info text-white ms-1" target="_blank">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>

                {{-- 3. MENUNGGU DIREKTUR --}}
                @elseif ($rab->status == 'Menunggu Direktur')
                    <div class="alert alert-info">
                        <i class="bi bi-check-circle"></i> Manajer OK.<br>
                        <i class="bi bi-hourglass-split"></i> Menunggu persetujuan <strong>Direktur</strong>.
                    </div>

                    @role('direktur')
                        <button type="button" class="btn btn-primary btn-approve" 
                            data-role="Direktur" 
                            data-url="{{ route('rab.approve.direktur', $rab->id) }}">
                            <i class="bi bi-check-circle"></i> Setujui (Direktur)
                        </button>

                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalTolakRAB">
                            <i class="bi bi-x-lg"></i> Tolak
                        </button>
                    @endrole

                    <a href="{{ route('rab.cetakPDF', $rab->id) }}" class="btn btn-info text-white ms-1" target="_blank">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>

                {{-- 4. FINAL (DISETUJUI) --}}
                @elseif ($rab->status == 'Disetujui')
                    <div class="alert alert-success col-2">
                        <i class="bi bi-check-circle-fill"></i> <strong>RAB DISETUJUI</strong>
                    </div>

                    @if (!$rab->transaksiMasuks()->exists())
                        @can('input barang masuk')
                            <a href="{{ route('transaksi-masuk.create', ['rab_id' => $rab->id]) }}" class="btn btn-success">
                                <i class="bi bi-cart-plus"></i> Catat Pembelian
                            </a>
                        @endcan
                    @else
                        <span class="btn btn-success disabled" >Stok Masuk</span>
                    @endif

                    <a href="{{ route('rab.cetakPDF', $rab->id) }}" class="btn btn-info text-white ms-1" target="_blank">
                        <i class="bi bi-printer"></i> Cetak PDF
                    </a>
                @endif

                {{-- Info Status Umum --}}
                @if($rab->status != 'Draft' && $rab->status != 'Ditolak')
                <p class="text-info m-2">
                    <i class="bi bi-info-circle-fill"></i> Status RAB saat ini: <strong>{{ $rab->status }}</strong>
                </p>
                @endif
            </div>
        </div>
    </div>

    {{-- KARTU FORM TAMBAH DETAIL BARANG (HANYA ADMIN & DRAFT/DITOLAK) --}}
    @role('admin')
    @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
    <div class="card card-outline card-success mt-4">
        <div class="card-header">
            <h3 class="card-title">Tambah Detail Barang Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rab.details.store', $rab->id) }}" method="POST">
                @csrf
                <input type="hidden" name="rab_id" value="{{ $rab->id }}">
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Nama Barang</label>
                        <input value="{{ old('nama_barang_diajukan') }}" type="text" name="nama_barang_diajukan" class="form-control @error('nama_barang_diajukan') is-invalid @enderror" required>
                        @error('nama_barang_diajukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label>Jumlah</label>
                        <input value="{{ old('jumlah', '1') }}" type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" required min="1">
                        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label>Harga Satuan</label>
                        <input value="{{ old('perkiraan_harga_satuan', '0') }}" type="number" name="perkiraan_harga_satuan" class="form-control @error('perkiraan_harga_satuan') is-invalid @enderror" required min="0">
                        @error('perkiraan_harga_satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label>Ongkir</label>
                        <input value="{{ old('ongkir', '0') }}" type="number" name="ongkir" class="form-control" min="0">
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label>Asuransi</label>
                        <input value="{{ old('asuransi', '0') }}" type="number" name="asuransi" class="form-control" min="0">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">Tambah Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endrole

</div>

{{-- MODAL EDIT DETAIL ITEM (AJAX) --}}
<div class="modal fade" id="modalEditDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">Edit Rincian Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditDetail">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_url_update">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama Barang</label>
                        <input type="text" id="edit_nama_barang" name="nama_barang_diajukan" class="form-control" required>
                        <div class="invalid-feedback" id="error-nama_barang_diajukan"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group mb-3">
                            <label>Jumlah</label>
                            <input type="number" id="edit_jumlah" name="jumlah" class="form-control" required min="1">
                            <div class="invalid-feedback" id="error-jumlah"></div>
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label>Harga Satuan</label>
                            <input type="number" id="edit_harga" name="perkiraan_harga_satuan" class="form-control" required min="0">
                            <div class="invalid-feedback" id="error-perkiraan_harga_satuan"></div>
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label>Ongkir</label>
                            <input type="number" id="edit_ongkir" name="ongkir" class="form-control" min="0">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label>Asuransi</label>
                            <input type="number" id="edit_asuransi" name="asuransi" class="form-control" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateDetail">Update Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TOLAK RAB --}}
<div class="modal fade" id="modalTolakRAB" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Pengajuan RAB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('rab.reject', $rab->id) }}" method="post">
                @csrf
                <div class="modal-body">
                    <label>Alasan Penolakan</label>
                    <textarea name="catatan_approval" rows="3" class="form-control" required placeholder="Berikan alasan kenapa RAB ditolak..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak RAB</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL APPROVAL DENGAN PASSWORD --}}
<div class="modal fade" id="modalApprove" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="lblApproveTitle">Konfirmasi Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formApprove" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-lock"></i> 
                        <strong>Verifikasi Keamanan:</strong><br>
                        Silakan masukkan password akun Anda untuk menandatangani dokumen ini secara digital.
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">Password Anda</label>
                        <input type="password" name="password" class="form-control" required placeholder="Ketikan password login...">
                    </div>

                    <div class="mt-3 text-muted" style="font-size: 0.9em">
                        * Dengan ini saya menyetujui RAB ini dan tanda tangan digital saya akan dibubuhkan pada dokumen.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi & Setuju</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Init DataTable
        $('#tabel-detail-rab').DataTable({
            "responsive": true,
            "lengthChange": false, 
            "autoWidth": false,
            "searching": false,    
            "paging": false,       
            "info": false
        });

        $('.btn-confirm-ajukan').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            
            Swal.fire({
                title: 'Ajukan RAB ini?',
                text: "Status akan berubah menjadi Menunggu Approval. Data tidak bisa diubah setelah diajukan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // Warna Success Bootstrap
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan Loading sebelum submit
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                    form.submit();
                }
            });
        });

        $('.btn-approve').click(function() {
            let role = $(this).data('role');
            let url = $(this).data('url');

            $('#lblApproveTitle').text('Konfirmasi Persetujuan ' + role);
            $('#formApprove').attr('action', url);
            $('input[name="password"]').val(''); // Reset password field
            
            $('#modalApprove').modal('show');
        });

        $('#formApprove').on('submit', function() {
            $('#modalApprove').modal('hide'); // Tutup modal dulu
            Swal.fire({
                title: 'Memverifikasi...',
                text: 'Mengecek password dan tanda tangan...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });
        });
        
        // 1. EDIT DETAIL (AJAX)
        $('.btn-edit-detail').click(function() {
            let urlEdit = $(this).data('url-edit');
            let urlUpdate = $(this).data('url-update');
            $('#edit_url_update').val(urlUpdate);
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.get(urlEdit, function(data) {
                $('#edit_nama_barang').val(data.nama_barang_diajukan);
                $('#edit_jumlah').val(data.jumlah);
                $('#edit_harga').val(data.perkiraan_harga_satuan);
                $('#edit_ongkir').val(data.ongkir);
                $('#edit_asuransi').val(data.asuransi);
                $('#modalEditDetail').modal('show');
            }).fail(function(xhr) {
                Swal.fire('Error', 'Gagal mengambil data item.', 'error');
            });
        });

        // 2. UPDATE DETAIL (AJAX)
        $('#formEditDetail').on('submit', function(e) {
            e.preventDefault();
            let url = $('#edit_url_update').val();
            let formData = $(this).serialize();

            $('#btnUpdateDetail').text('Menyimpan...').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#modalEditDetail').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    $('#btnUpdateDetail').text('Update Perubahan').attr('disabled', false);
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#error-${key}`).text(value[0]);
                            $(`[name="${key}"]`).addClass('is-invalid');
                        });
                    } else {
                        Swal.fire('Gagal', xhr.responseJSON.message || 'Terjadi kesalahan sistem', 'error');
                    }
                }
            });
        });

        // 3. DELETE CONFIRM
        $('.btn-delete-confirm').click(function(e) {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus item ini?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        $('.btn-approve').click(function() {
            let role = $(this).data('role');
            let url = $(this).data('url');

            // Ubah Judul & Action Form Modal
            $('#lblApproveTitle').text('Konfirmasi Persetujuan ' + role);
            $('#formApprove').attr('action', url);
            
            // Reset input password
            $('input[name="password"]').val('');
            
            // Tampilkan Modal
            $('#modalApprove').modal('show');
        });
    });

    @if(session('success'))
        Swal.fire({icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false});
    @endif
    @if(session('error'))
        Swal.fire({icon: 'error', title: 'Gagal', text: '{{ session('error') }}'});
    @endif
</script>
@endpush
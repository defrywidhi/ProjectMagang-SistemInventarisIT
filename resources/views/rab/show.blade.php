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
                                'Menunggu Manager' => 'warning text-dark',
                                'Menunggu Direktur' => 'info text-dark',
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
                        <th width="5%">No</th>
                        <th width="25%">Nama Barang</th>
                        <th width="10%">Foto</th> {{-- KOLOM BARU --}}
                        <th width="8%">Jumlah</th>
                        <th width="15%">Harga Satuan</th>
                        <th>Ongkir</th>
                        <th>Asuransi</th>
                        <th width="15%">Total Harga</th>

                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody> 
                    @forelse ($rab->details as $detail)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        
                        {{-- 1. NAMA BARANG & KETERANGAN --}}
                        <td>
                            <strong>{{ $detail->nama_barang_diajukan }}</strong>
                            @if($detail->keterangan)
                                <br>
                                <small class="text-muted fst-italic">
                                    <i class="bi bi-info-circle"></i> Ket: {{ $detail->keterangan }}
                                </small>
                            @endif
                        </td>
                        
                        {{-- 2. FOTO (HYBRID LOGIC) --}}
                        <td class="text-center">
                            @if($detail->barang_it_id && $detail->barang_it && $detail->barang_it->gambar_barang)
                                {{-- Jika Master --}}
                                <img src="{{ asset('storage/gambar_barang/' . $detail->barang_it->gambar_barang) }}" 
                                     class="img-thumbnail" style="height: 50px; width: auto; object-fit: contain;">
                            @elseif($detail->foto_custom)
                                {{-- Jika Custom --}}
                                <img src="{{ asset('storage/' . $detail->foto_custom) }}" 
                                     class="img-thumbnail" style="height: 50px; width: auto; object-fit: contain;">
                            @else
                                <span class="badge bg-light text-dark border">-</span>
                            @endif
                        </td>

                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($detail->perkiraan_harga_satuan, 0, ',', '.') }}</td> 
                        <td class="text-end">Rp {{ number_format($detail->ongkir, 0, ',', '.') }}</td> 
                        <td class="text-end">Rp {{ number_format($detail->asuransi, 0, ',', '.') }}</td> 
                        <td class="text-end fw-bold">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td> 

                        {{-- Tombol Edit/Hapus --}}
                        @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
                        <td class="text-center p-1">
                            <button class="btn btn-warning btn-sm btn-edit-detail" 
                                data-url-edit="{{ route('rab.details.edit', $detail->id) }}"
                                data-url-update="{{ route('rab.details.update', $detail->id) }}"
                                title="Edit Item">
                                <i class="bi bi-pencil"></i>
                            </button>

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
                        <td colspan="{{ ($rab->status == 'Draft' || $rab->status == 'Ditolak') ? 9 : 8 }}" class="text-center">
                            Belum ada detail barang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <hr>
            <div class="px-3 pb-3">
                
                {{-- STATUS LOGIC (Tombol Ajukan / Approve) --}}
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
                                <button type="button" class="btn btn-success btn-confirm-ajukan">
                                    <i class="bi bi-send"></i> {{ $rab->status == 'Ditolak' ? 'Ajukan Ulang' : 'Ajukan RAB' }}
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Isi barang dulu</button>
                        @endif
                    @endcan
                    <span class="text-muted ms-2"><small>Edit tersedia.</small></span>

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

                @elseif ($rab->status == 'Disetujui')
                    
                    {{-- HITUNG PROGRES --}}
                    @php
                        $totalItem = $rab->details->count();
                        $sudahMasuk = $rab->transaksiMasuks->count();
                        
                        // Hitung Persentase (biar gak error division by zero)
                        $persen = $totalItem > 0 ? ($sudahMasuk / $totalItem) * 100 : 0;
                        
                        // Tentukan Status: Selesai atau Belum?
                        $isComplete = $sudahMasuk >= $totalItem;
                    @endphp

                    <div class="row align-items-center">
                        {{-- STATUS TEXT --}}
                        <div class="col-md-2">
                            @if($isComplete)
                                <div class="alert alert-success m-0 p-2">
                                    <i class="bi bi-check-circle-fill"></i> <strong>SEMUA BARANG SUDAH MASUK</strong>
                                </div>
                            @else
                                <div class="alert alert-info m-0 p-2">
                                    <i class="bi bi-cart-check"></i> <strong>Proses Pembelian</strong>
                                    <br>
                                    <small>Item Masuk: {{ $sudahMasuk }} dari {{ $totalItem }}</small>
                                </div>
                            @endif
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="col-md-7">
                            @can('input barang masuk')
                                
                                @if(!$isComplete)
                                    {{-- KONDISI A: BELUM LENGKAP -> TAMPILKAN TOMBOL --}}
                                    <a href="{{ route('transaksi-masuk.create', ['rab_id' => $rab->id]) }}" class="btn btn-success shadow-sm">
                                        <i class="bi bi-cart-plus"></i> 
                                        @if($sudahMasuk == 0)
                                            Mulai Catat Pembelian
                                        @else
                                            Input Barang Berikutnya
                                        @endif
                                    </a>
                                @else
                                    {{-- KONDISI B: SUDAH LENGKAP -> MATIKAN TOMBOL --}}
                                    <button class="btn btn-secondary disabled">
                                        <i class="bi bi-lock-fill"></i> Selesai
                                    </button>
                                @endif

                                {{-- Tombol Lihat History (Selalu Ada kalau sudah ada transaksi) --}}
                                @if($sudahMasuk > 0)
                                    <a href="{{ route('transaksi-masuk.index', ['rab_id' => $rab->id]) }}" class="btn btn-outline-primary ms-1" title="Lihat Riwayat">
                                        <i class="bi bi-list-ul"></i> History
                                    </a>
                                @endif

                            @endcan

                            <a href="{{ route('rab.cetakPDF', $rab->id) }}" class="btn btn-info text-white ms-1" target="_blank">
                                <i class="bi bi-printer"></i> PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- KARTU FORM TAMBAH DETAIL BARANG --}}
    @role('admin')
    @if ($rab->status == 'Draft' || $rab->status == 'Ditolak')
    <div class="card card-outline card-success mt-4">
        <div class="card-header">
            <h3 class="card-title">Tambah Detail Barang Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rab.details.store', $rab->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- PILIHAN TIPE INPUT --}}
                <div class="card p-3 mb-3 bg-light border">
                    <label class="fw-bold mb-2">Sumber Barang:</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipe_input" id="optMaster" value="master" checked onclick="toggleInput('master')">
                            <label class="form-check-label" for="optMaster">Ambil dari Master Gudang</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipe_input" id="optCustom" value="custom" onclick="toggleInput('custom')">
                            <label class="form-check-label" for="optCustom">Input Barang Baru (Custom)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        {{-- INPUT MASTER --}}
                        <div id="input-master-group">
                            <div class="form-group mb-3">
                                <label>Pilih Barang <span class="text-danger">*</span></label>
                                <select name="barang_it_id" class="form-control select2" style="width: 100%;">
                                    <option value="">-- Cari Barang --</option>
                                    @foreach($barangs as $b) 
                                        <option value="{{ $b->id }}">{{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- INPUT CUSTOM --}}
                        <div id="input-custom-group" style="display: none;">
                            <div class="form-group mb-3">
                                <label>Nama Barang Baru <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang_custom" class="form-control" placeholder="Misal: Laptop ROG Zephyrus...">
                            </div>
                            <div class="form-group mb-3">
                                <label>Upload Foto Barang <span class="text-danger">*</span></label>
                                <input type="file" name="foto_custom" class="form-control" accept="image/*">
                                <small class="text-muted">Wajib upload foto untuk barang baru.</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Harga Satuan <span class="text-danger">*</span></label>
                                <input type="number" name="perkiraan_harga_satuan" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Keterangan (Opsional)</label>
                                <textarea name="keterangan" class="form-control" rows="1" placeholder="Warna, Spek, dll"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Ongkir</label>
                                <input type="number" name="ongkir" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Asuransi</label>
                                <input type="number" name="asuransi" class="form-control" value="0" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-2">
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> Tambah Item</button>
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
                            <input type="number" id="edit_ongkir" name="ongkir" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label>Asuransi</label>
                            <input type="number" id="edit_asuransi" name="asuransi" class="form-control" min="0" placeholder="0">
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

{{-- MODAL APPROVAL DENGAN PIN --}}
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
                        <strong>Verifikasi PIN:</strong><br>
                        Silakan masukkan <strong>PIN Approval (6 Digit)</strong> Anda untuk menandatangani dokumen ini.
                    </div>

                    <div class="form-group">
                        <label class="fw-bold">PIN Approval</label>
                        {{-- GANTI name="password" JADI name="pin" --}}
                        <input type="password" name="pin" class="form-control text-center" 
                               required placeholder="* * * * * *" maxlength="6" inputmode="numeric" 
                               style="font-size: 24px; letter-spacing: 5px;">
                    </div>

                    <div class="mt-3 text-muted" style="font-size: 0.9em">
                        * Tanda tangan digital Anda akan dibubuhkan secara otomatis.
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
    function toggleInput(mode) {
        if(mode === 'master') {
            $('#input-master-group').show();
            $('#input-custom-group').hide();
            $('select[name="barang_it_id"]').attr('required', true);
            $('input[name="nama_barang_custom"]').attr('required', false);
            $('input[name="foto_custom"]').attr('required', false);
        } else {
            $('#input-master-group').hide();
            $('#input-custom-group').show();
            $('select[name="barang_it_id"]').attr('required', false);
            $('input[name="nama_barang_custom"]').attr('required', true);
            $('input[name="foto_custom"]').attr('required', true);
        }
    }

    $(document).ready(function() {
        if($('#optCustom').is(':checked')) { toggleInput('custom'); } else { toggleInput('master'); }

        $('#tabel-detail-rab').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "searching": false, "paging": false, "info": false
        });

        $('.btn-confirm-ajukan').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Ajukan RAB ini?', text: "Data tidak bisa diubah setelah diajukan.",
                icon: 'question', showCancelButton: true,
                confirmButtonColor: '#198754', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading() } });
                    form.submit();
                }
            });
        });

        $('.btn-approve').click(function() {
            let role = $(this).data('role');
            let url = $(this).data('url');
            $('#lblApproveTitle').text('Konfirmasi Persetujuan ' + role);
            $('#formApprove').attr('action', url);
            $('input[name="pin"]').val('');
            $('#modalApprove').modal('show');
            setTimeout(() => { $('input[name="pin"]').focus(); }, 500);
        });

        $('#formApprove').on('submit', function() {
            $('#modalApprove').modal('hide');
            Swal.fire({ title: 'Memverifikasi...', didOpen: () => { Swal.showLoading() } });
        });

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
                // FIX: Jika data null, isi dengan 0 agar form tidak kosong
                $('#edit_ongkir').val(data.ongkir ?? 0);
                $('#edit_asuransi').val(data.asuransi ?? 0);
                $('#modalEditDetail').modal('show');
            }).fail(function() {
                Swal.fire('Error', 'Gagal mengambil data item.', 'error');
            });
        });

        $('#formEditDetail').on('submit', function(e) {
            e.preventDefault();
            let url = $('#edit_url_update').val();
            let formData = $(this).serialize();
            $('#btnUpdateDetail').text('Menyimpan...').attr('disabled', true);

            $.ajax({
                url: url, type: 'POST', data: formData,
                success: function(response) {
                    $('#modalEditDetail').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Berhasil!', timer: 1500, showConfirmButton: false }).then(() => location.reload());
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
                        Swal.fire('Gagal', xhr.responseJSON.message, 'error');
                    }
                }
            });
        });

        $('.btn-delete-confirm').click(function() {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus item?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

    // 1. Alert Jika SUKSES (session('success'))
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // 2. Alert Jika GAGAL/ERROR (session('error')) -> INI YANG ABANG CARI
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33',
        });
    @endif

    // 3. Alert Jika Ada ERROR VALIDASI (Misal: PIN cuma 5 angka)
    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Periksa Inputan',
            html: `
                <ul style="text-align: left;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#d33',
        });
    @endif
</script>
@endpush
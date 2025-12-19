@extends('layouts.master')
@section('content_title', 'Transaksi Masuk')
@section('title', 'Transaksi Masuk')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            {{-- TOMBOL CREATE MODAL --}}
            <button type="button" class="btn btn-primary" onclick="openModalCreate()">
                <i class="bi bi-plus-circle-fill"></i> Tambah Transaksi Masuk
            </button>
            <a href="{{ route('transaksi-masuk.exportExcel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Laporan
            </a>
        </div>
        <div class="card-body p-0 text-center table-responsive">
            
            {{-- Tabel Data --}}
            <table id="tabel-transaksi-masuk" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th width="25%">Nama Barang</th>
                        <th>Kondisi</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Harga Satuan</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ($transaksis_masuk as $item)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $item->barang_it->nama_barang }}</span>
                            <br>
                            @if($item->rab)
                                <small class="text-primary">
                                    <i class="bi bi-file-earmark-text"></i> RAB: {{ $item->rab->kode_rab }}
                                </small>
                            @elseif($item->transaksi_keluar_id)
                                <small class="text-danger">
                                    <i class="bi bi-arrow-return-left"></i> Retur / Kembali
                                </small>
                            @else
                                <small class="text-muted">-</small> 
                            @endif
                        </td>

                        <td class="text-center">
                            @php
                                $kondisi = $item->barang_it->kondisi;
                                $warna = 'secondary'; // Default

                                if($kondisi == 'Baru') {
                                    $warna = 'success'; // Hijau
                                } elseif ($kondisi == 'Rusak') {
                                    $warna = 'danger';  // Merah
                                } elseif ($kondisi == 'Bekas') {
                                    $warna = 'warning text-dark'; // Kuning (text dark biar kebaca)
                                }
                            @endphp
                            
                            <span class="badge bg-{{ $warna }}">
                                {{ $kondisi }}
                            </span>
                        </td>

                        <td>{{ $item->supplier->nama_supplier }}</td>
                        <td>{{ $item->jumlah_masuk }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('transaksi-masuk.cetakInvoice', $item->id) }}" class="btn btn-secondary btn-sm" target="_blank" title="Cetak">
                                    <i class="bi bi-printer"></i>
                                </a>                              
                                <button class="btn btn-warning btn-sm btn-edit" 
                                    data-url-edit="{{ route('transaksi-masuk.edit', $item->id) }}"
                                    data-url-update="{{ route('transaksi-masuk.update', $item->id) }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('transaksi-masuk.destroy', $item->id) }}" method="POST" class="d-inline-block form-delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete-confirm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL UNIFIED --}}
<div class="modal fade" id="modalTransaksiMasuk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Tambah Transaksi Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTransaksiMasuk">
                @csrf
                <input type="hidden" id="method_field" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Pilih Barang</label>
                                <select class="form-select" name="barang_it_id" id="barang_it_id" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach (\App\Models\BarangIT::all() as $brg)
                                        <option value="{{ $brg->id }}">
                                            {{ $brg->nama_barang }} ({{ $brg->merk }}) - [{{ $brg->kondisi }}] - Stok: {{ $brg->stok }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-barang_it_id"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Supplier</label>
                                <select class="form-select" name="supplier_id" id="supplier_id" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach (\App\Models\Supplier::all() as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-supplier_id"></div>
                            </div>

                            {{-- FITUR RAB --}}
                            <div class="form-group mb-3">
                                <label>Asal RAB (Opsional)</label>
                                <select class="form-select" name="rab_id" id="rab_id">
                                    <option value="">-- Pilih RAB --</option>
                                    @foreach (\App\Models\Rab::where('status', 'Disetujui')->get() as $rab)
                                        <option value="{{ $rab->id }}">{{ $rab->kode_rab }} - {{ $rab->judul }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- DROPDOWN CONTEKAN ITEM RAB --}}
                            <div class="form-group mb-3" id="rab-items-container" style="display: none;">
                                <label class="text-primary fw-bold">Auto-fill dari Item RAB:</label>
                                <select class="form-select border-primary" id="rab_detail_item">
                                    <option value="">-- Pilih Item --</option>
                                </select>
                                <small class="text-muted">Pilih item untuk mengisi Jumlah & Harga otomatis.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Jumlah Masuk</label>
                                <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" min="1" required>
                                <div class="invalid-feedback" id="error-jumlah_masuk"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Harga Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_satuan" id="harga_satuan" class="form-control" min="0" required>
                                </div>
                                <div class="invalid-feedback" id="error-harga_satuan"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ date('Y-m-d') }}" required>
                                <div class="invalid-feedback" id="error-tanggal_masuk"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const storeUrl = "{{ route('transaksi-masuk.store') }}";
    let currentUpdateUrl = ""; 
    let rabDetailsData = []; 
    
    $(document).ready(function() {
        // DISINI CREATE
        $('#formTransaksiMasuk').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let url = ($('#method_field').val() === 'POST') ? storeUrl : currentUpdateUrl;
    
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#btnSimpan').text('Memproses...').attr('disabled', true);
    
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#modalTransaksiMasuk').modal('hide');
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    $('#btnSimpan').text('Simpan').attr('disabled', false);
                    if(xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(value[0]);
                        });
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                }
            });
        });

        // 1. LOGIKA AUTO-FILL RAB (PERBAIKAN URL)
        $('#rab_id').change(function() {
            let rabId = $(this).val();
            if(!rabId) {
                $('#rab-items-container').slideUp();
                return;
            }
            
            // --- PERBAIKAN DI SINI ---
            // Kita gunakan placeholder manual 'RAB_ID_PLACEHOLDER' biar replace-nya pasti berhasil
            let urlTemplate = "{{ route('rab.getDetailsJson', ['rab' => 'RAB_ID_PLACEHOLDER']) }}";
            let url = urlTemplate.replace('RAB_ID_PLACEHOLDER', rabId);
            // -------------------------
            
            $('#rab_detail_item').html('<option>Loading...</option>');
            $('#rab-items-container').slideDown();
            
            $.get(url, function(data) {
                rabDetailsData = data;
                let options = '<option value="">-- Pilih Item untuk Auto-fill --</option>';
                data.forEach(function(item) {
                    // Format angka ke rupiah (opsional, biar cantik di dropdown)
                    let hargaFmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.perkiraan_harga_satuan);
                    options += `<option value="${item.id}">${item.nama_barang_diajukan} (Qty: ${item.jumlah} @ ${hargaFmt})</option>`;
                });
                $('#rab_detail_item').html(options);
            }).fail(function(jqXHR) {
                console.log("Error:", jqXHR);
                alert('Gagal mengambil data RAB. Cek Console Log.');
                $('#rab-items-container').slideUp();
            });
        });
        
        // Saat item RAB dipilih -> Isi form
        $('#rab_detail_item').change(function() {
            let selectedId = $(this).val();
            let item = rabDetailsData.find(d => d.id == selectedId);
            if(item) {
                $('#jumlah_masuk').val(item.jumlah);
                $('#harga_satuan').val(item.perkiraan_harga_satuan);
                if($('#keterangan').val() == '') $('#keterangan').val('Pengadaan dari RAB: ' + item.nama_barang_diajukan);
            }
        });
        
        // 2. TOMBOL EDIT
        $('.btn-edit').click(function() {
            let urlEdit = $(this).data('url-edit');
            currentUpdateUrl = $(this).data('url-update');
            
            resetForm();
            $('#modalTitle').text('Edit Transaksi Masuk');
            $('#method_field').val('PUT');
            $('#btnSimpan').text('Update Transaksi');
            
            $.get(urlEdit, function(response) {
                let data = response.transaksi;
                $('#barang_it_id').val(data.barang_it_id);
                $('#supplier_id').val(data.supplier_id);
                $('#jumlah_masuk').val(data.jumlah_masuk);
                $('#harga_satuan').val(data.harga_satuan);
                $('#tanggal_masuk').val(data.tanggal_masuk);
                $('#keterangan').val(data.keterangan);
                
                // Set RAB jika ada, trigger change agar item muncul
                if(data.rab_id) {
                    $('#rab_id').val(data.rab_id).trigger('change');
                }

                $('#modalTransaksiMasuk').modal('show');
            });
        });
         
        // 4. DELETE
        $('.btn-delete-confirm').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Yakin hapus transaksi?',
                text: "Stok akan dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
        
        // TABLES
        $('#tabel-transaksi-masuk').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
    
            "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });
        
    });
    
    // Helper Reset
    window.openModalCreate = function() {
        resetForm();
        $('#modalTransaksiMasuk').modal('show');
    }
    
    function resetForm() {
        $('#formTransaksiMasuk')[0].reset();
        $('#modalTitle').text('Tambah Transaksi Masuk');
        $('#method_field').val('POST');
        $('#btnSimpan').text('Simpan Transaksi');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#rab-items-container').hide();
        // Reset juga value dropdown RAB
        $('#rab_id').val('');
        $('#rab_detail_item').html('<option value="">-- Pilih Item --</option>');
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
        });
    @endif
</script>
@endpush
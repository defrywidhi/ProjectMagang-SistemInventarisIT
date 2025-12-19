@extends('layouts.master')
@section('content_title', 'Transaksi Keluar')
@section('title', 'Transaksi Keluar')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            {{-- TOMBOL CREATE (AJAX MODAL) --}}
            <button type="button" class="btn btn-primary" onclick="openModalCreate()">
                <i class="bi bi-plus-circle-fill"></i> Tambah Transaksi Keluar
            </button>
        </div>
        <div class="card-body p-0 text-center table-responsive">    
            <table id="tabel-transaksi-keluar" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        {{-- HAPUS KOLOM 'NO', GANTI JADI 'KODE TRANSAKSI' --}}
                        <th width="10%">Kode</th> 
                        <th width="25%">Nama Barang</th>
                        <th>Kondisi</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ($transaksi_keluar as $item)
                    <tr>
                        {{-- ISI KOLOM KODE DENGAN FORMAT TRK-ID --}}
                        <td class="text-center">
                            <span class="badge bg-secondary" style="font-family: monospace; font-size: 0.9em;">
                                TRK-{{ $item->id }}
                            </span>
                        </td>

                        <td>
                            <span class="fw-bold">{{ $item->barang_it->nama_barang }}</span>
                            <br>
                            <small class="text-muted">{{ $item->barang_it->merk ?? '-' }}</small>
                        </td>

                        <td>
                            @php
                                $kondisi = $item->barang_it->kondisi;
                                $warna = $kondisi == 'Baru' ? 'success' : ($kondisi == 'Rusak' ? 'danger' : 'warning text-dark');
                            @endphp
                            <span class="badge bg-{{ $warna }}">{{ $kondisi }}</span>
                        </td>

                        <td>
                            <span class="fw-bold fs-5">{{ $item->jumlah_keluar }}</span>
                            
                            @if($item->jumlah_dikembalikan > 0)
                                <div class="mt-1">
                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem">
                                        Kembali: {{ $item->jumlah_dikembalikan }}
                                    </span>
                                </div>
                                @php $sisa = $item->jumlah_keluar - $item->jumlah_dikembalikan; @endphp
                                @if($sisa == 0)
                                    <span class="badge bg-success mt-1" style="font-size: 0.7rem">Selesai</span>
                                @else
                                    <span class="badge bg-danger mt-1" style="font-size: 0.7rem">Sisa: {{ $sisa }}</span>
                                @endif
                            @endif
                        </td>

                        <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->user->name }}</td>
                        
                        <td class="p-1">
                            <div class="d-flex justify-content-center gap-1">
                                @if($item->jumlah_dikembalikan < $item->jumlah_keluar)
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalRetur{{ $item->id }}" title="Retur Barang">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                @endif

                                <a href="{{ route('transaksi-keluar.cetakBuktiKeluar', $item->id) }}" class="btn btn-secondary btn-sm" target="_blank" title="Cetak">
                                    <i class="bi bi-printer"></i>
                                </a>

                                <button class="btn btn-warning btn-sm btn-edit" 
                                    data-url-edit="{{ route('transaksi-keluar.edit', $item->id) }}"
                                    data-url-update="{{ route('transaksi-keluar.update', $item->id) }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('transaksi-keluar.destroy', $item->id) }}" method="POST" class="d-inline-block form-delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete-confirm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Retur --}}
                    <div class="modal fade" id="modalRetur{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">Pengembalian Barang (TRK-{{ $item->id }})</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('transaksi-keluar.retur', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body text-start">
                                        <p>Barang: <strong>{{ $item->barang_it->nama_barang }}</strong></p>
                                        <p>Dipinjam: {{ $item->jumlah_keluar }} | Sisa di Luar: {{ $item->jumlah_keluar - $item->jumlah_dikembalikan }}</p>
                                        
                                        <div class="form-group mb-3">
                                            <label>Jumlah yang Dikembalikan</label>
                                            <input type="number" name="jumlah_kembali" class="form-control" min="1" max="{{ $item->jumlah_keluar - $item->jumlah_dikembalikan }}" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Jenis Pengembalian</label>
                                            <select name="jenis_retur" class="form-select" required>
                                                <option value="Kembali Pinjam">Selesai Dipinjam (Normal)</option>
                                                <option value="Selesai Service">Selesai Service (Barang Bagus)</option>
                                                <option value="Retur Rusak">Dikembalikan Rusak</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Kondisi Saat Kembali</label>
                                            <select name="kondisi_akhir" class="form-select" required>
                                                <option value="Bekas">Masih Bagus (Bekas)</option>
                                                <option value="Baru">Seperti Baru</option>
                                                <option value="Rusak">Rusak</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Biaya Perbaikan / Denda (Rp)</label>
                                            <input type="number" name="biaya_perbaikan" class="form-control" value="0" min="0">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>Catatan Tambahan</label>
                                            <textarea name="keterangan" class="form-control" placeholder="..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Proses Retur</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="8" class="text-center">Tidak ada data transaksi keluar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL UNIFIED (CREATE & EDIT TRANSAKSI KELUAR) --}}
<div class="modal fade" id="modalTransaksiKeluar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Tambah Transaksi Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTransaksiKeluar" >
                @csrf
                <input type="hidden" id="method_field" name="_method" value="POST">
                
                <div class="modal-body">
                    {{-- PILIH BARANG (Hanya yg stok > 0) --}}
                    <div class="form-group mb-3">
                        <label>Barang yang Keluar</label>
                        <select class="form-select" name="barang_it_id" id="barang_it_id" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach (\App\Models\BarangIT::where('stok', '>', 0)->get() as $brg)
                                <option value="{{ $brg->id }}">
                                    {{ $brg->nama_barang }} ({{ $brg->merk }}) - [{{ $brg->kondisi }}] - Stok: {{ $brg->stok }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error-barang_it_id"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Jumlah Keluar</label>
                        <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control" min="1" required>
                        <div class="invalid-feedback" id="error-jumlah_keluar"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="error-tanggal_keluar"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Keterangan / Keperluan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Contoh: Dipinjam oleh Ruang Melati..."></textarea>
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
    const storeUrl = "{{ route('transaksi-keluar.store') }}";
    let currentUpdateUrl = ""; 

    $(document).ready(function() {

        // ============================================================
        // 1. WAJIB PALING ATAS: FORM SUBMIT
        // (Biar kalau DataTables error, tombol simpan tetap hidup)
        // ============================================================
        $('#formTransaksiKeluar').on('submit', function(e) {
            e.preventDefault(); 
            
            console.log("Tombol Simpan Diklik!"); // Cek Console

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
                    $('#modalTransaksiKeluar').modal('hide');
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

        // ============================================================
        // 2. TOMBOL EDIT
        // ============================================================
        $('.btn-edit').click(function() {
            let urlEdit = $(this).data('url-edit');
            currentUpdateUrl = $(this).data('url-update');

            resetForm(); 
            $('#modalTitle').text('Edit Transaksi Keluar');
            $('#method_field').val('PUT');
            $('#btnSimpan').text('Update Data');

            $.get(urlEdit, function(response) {
                let data = response.transaksi;
                let barang = response.barang_sekarang;
                
                // Logika Stok 0
                if ($('#barang_it_id option[value="' + data.barang_it_id + '"]').length === 0) {
                    let namaLengkap = `${barang.nama_barang} (${barang.merk}) - [${barang.kondisi}] - Stok: ${barang.stok} (Habis)`;
                    let newOption = new Option(namaLengkap, barang.id, true, true);
                    $('#barang_it_id').append(newOption);
                }

                $('#barang_it_id').val(data.barang_it_id).trigger('change');
                $('#jumlah_keluar').val(data.jumlah_keluar);
                $('#tanggal_keluar').val(data.tanggal_keluar);
                $('#keterangan').val(data.keterangan);

                $('#modalTransaksiKeluar').modal('show');
            });
        });

        // ============================================================
        // 3. TOMBOL DELETE
        // ============================================================
        $('.btn-delete-confirm').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Yakin hapus transaksi?',
                text: "Stok akan dikembalikan ke gudang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });

        // ============================================================
        // 4. TERAKHIR: DATATABLES
        // (Taruh paling bawah. Kalau dia error, fitur lain aman)
        // ============================================================
        $('#tabel-transaksi-keluar').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
        });

    }); // <--- Tutup Document Ready

    // Helper Functions (Tetap di luar)
    window.openModalCreate = function() {
        resetForm();
        $('#modalTransaksiKeluar').modal('show');
    }

    function resetForm() {
        $('#formTransaksiKeluar')[0].reset();
        $('#modalTitle').text('Tambah Transaksi Keluar');
        $('#method_field').val('POST');
        $('#btnSimpan').text('Simpan Data');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('#barang_it_id').val(""); 
    }

    @if(session('success'))
        Swal.fire({icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false});
    @endif
    @if(session('error'))
        Swal.fire({icon: 'error', title: 'Gagal!', text: '{{ session('error') }}'});
    @endif
</script>
@endpush
@extends('layouts.master')

@section('content_title', 'Barang')
@section('title', 'Barang')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <!-- <a href="{{ route('barang.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tambah Barang</a> -->

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                <i class="bi bi-plus-circle-fill"></i> Tambah Barang
            </button>
            <a href="{{ route('barang.exportExcel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Export ke Excel
            </a>
        </div>
        <div class="card-body p-0 text-center table-responsive">

            <!-- Success Alert - Bootstrap 5 Version -->
            @if(session('success'))
            <div class="alert alert-success fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Error Alert - Bootstrap 5 Version -->
            @if(session('error'))
            <div class="alert alert-danger fade show d-flex align-items-center" role="alert">
                <div class="me-2">
                    <strong>Gagal!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Tambahkan table-sm, text-nowrap, dan style font-size --}}
            <table id="tabel-barang" class="table table-bordered table-sm text-nowrap" style="font-size: 1rem;">
                <thead class="table-secondary">
                    <tr>
                        <th>Kategori</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Serial Number</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Stok Minimun</th>
                        <th>Kondisi</th>
                        <th>Lokasi Penyimpanan</th>
                        <th>Gambar Barang</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    @forelse ($barangs as $item )
                    <tr>
                        <td>{{ $item -> kategori -> kode_kategori }}</td>
                        <td>{{ $item -> nama_barang }}</td>
                        <td>{{ $item -> merk ?? '_'}}</td>
                        <td>{{ $item -> serial_number ?? '_'}}</td>
                        <td>{{ $item -> deskripsi ?? '_'}}</td>
                        <td>
                            @if ($item -> stok > $item -> stok_minimum)
                                <span class="badge bg-success"> {{ $item -> stok }}</span>
                            @elseif ($item -> stok == $item -> stok_minimum)
                                <span class="badge bg-warning"> {{ $item -> stok }}</span>
                            @else
                                <span class="badge bg-danger"> {{ $item -> stok }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-warning">{{ $item -> stok_minimum }}</td>
                        <td>@if ($item->kondisi == 'Baru')
                            <span class="badge bg-success"> {{ $item -> kondisi }}</span>
                        @elseif ($item->kondisi == 'Bekas') 
                            <span class="badge bg-warning"> {{ $item -> kondisi }}</span>
                        @elseif ($item->kondisi == 'Rusak')
                            <span class="badge bg-danger"> {{ $item -> kondisi }}</span>
                        @else
                            <span class="badge bg-secondary"> {{ $item -> kondisi }}</span>
                        @endif
                        </td>
                        <td>{{ $item -> lokasi_penyimpanan ?? '_'}}</td>
                        <td>
                            @if($item->gambar_barang)
                            <img src="{{ asset('storage/gambar_barang/'. $item->gambar_barang) }}" alt="Gambar Barang" class="object-fit-cover" style="width: 100px; height: 100px;">
                            @else
                            Tidak Ada Gambar
                            @endif
                        </td>
                        <!-- <td class="text-center p-0">
                            <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('barang.destroy', $item->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('yakin menghapus file ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td> -->

                        <td class="text-center align-middle p-2">
                            <div class="d-flex justify-content-center gap-1">
                            
                            @if($item->kondisi == 'Rusak' && $item->stok > 0)
                            <a href="{{ route('transaksi-keluar.create', ['barang_id' => $item->id, 'tipe' => 'service']) }}" 
                               class="btn btn-warning btn-sm" title="Kirim Service">
                                <i class="bi bi-tools"></i>
                            </a>
                            @endif

                            @if(($item->kondisi == 'Baru' || $item->kondisi == 'Bekas') && $item->stok > 0)
                            <a href="{{ route('transaksi-keluar.create', ['barang_id' => $item->id, 'tipe' => 'pakai']) }}" 
                               class="btn btn-success btn-sm" title="Keluarkan / Pakai">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                            @endif

                            {{-- Tombol Edit (AJAX) --}}
                            <button type="button" class="btn btn-warning btn-sm btn-edit" 
                                    data-url="{{ route('barang.edit', $item->id) }}" 
                                    data-update-url="{{ route('barang.update', $item->id) }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Tombol Hapus (AJAX) --}}
                            <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                    data-url="{{ route('barang.destroy', $item->id) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11">Tidak ada data barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



<!-- MODAL UNTUK TAMBAH BARANG -->
 {{-- MODAL TAMBAH BARANG --}}
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- Pakai modal-lg biar lebar --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- enctype wajib ada walau nanti di-override JS --}}
            <form id="formTambahBarang" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        {{-- KIRI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" required>
                                <div class="invalid-feedback" id="error-nama_barang"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kategori</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-kategori_id"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Merk</label>
                                <input type="text" name="merk" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Serial Number</label>
                                <input type="text" name="serial_number" class="form-control">
                                <div class="invalid-feedback" id="error-serial_number"></div>
                            </div>
                        </div>
                        {{-- KANAN --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Stok Minimum</label>
                                <input type="number" name="stok_minimum" class="form-control" value="1" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kondisi</label>
                                <select name="kondisi" class="form-select">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas">Bekas</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi_penyimpanan" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Gambar</label>
                                <input type="file" name="gambar_barang" class="form-control">
                                <div class="invalid-feedback" id="error-gambar_barang"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL EDIT BARANG --}}
<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditBarang" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        {{-- KIRI --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" id="edit_nama_barang" class="form-control" required>
                                <div class="invalid-feedback" id="error-edit-nama_barang"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kategori</label>
                                <select name="kategori_id" id="edit_kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="error-edit-kategori_id"></div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Merk</label>
                                <input type="text" name="merk" id="edit_merk" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Serial Number</label>
                                <input type="text" name="serial_number" id="edit_serial_number" class="form-control">
                                <div class="invalid-feedback" id="error-edit-serial_number"></div>
                            </div>
                        </div>
                        {{-- KANAN --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Stok Minimum</label>
                                <input type="number" name="stok_minimum" id="edit_stok_minimum" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kondisi</label>
                                <select name="kondisi" id="edit_kondisi" class="form-select">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas">Bekas</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi_penyimpanan" id="edit_lokasi_penyimpanan" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Ganti Gambar (Opsional)</label>
                                <input type="file" name="gambar_barang" id="edit_gambar_barang" class="form-control">
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                                <div class="invalid-feedback" id="error-edit-gambar_barang"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="btnUpdate">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        
        // AJAX Tambah Barang (DENGAN GAMBAR)
        $('#formTambahBarang').on('submit', function(e) {
            e.preventDefault();
            
            // --- JURUS KHUSUS UPLOAD FILE ---
            // Kita pakai FormData, bukan serialize()
            let formData = new FormData(this); 
            // -------------------------------
            
            // Reset Error
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#btnSimpan').text('Menyimpan...').attr('disabled', true);
            
            $.ajax({
                url: "{{ route('barang.store') }}",
                type: "POST",
                data: formData, // Kirim FormData
                
                // --- WAJIB ADA UNTUK UPLOAD FILE ---
                contentType: false, // Biar browser yang atur header encoding
                processData: false, // Biar jQuery gak ngubah data jadi string
                // -----------------------------------
                
                success: function(response) {
                    $('#modalTambahBarang').modal('hide');
                    $('#formTambahBarang')[0].reset();
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    $('#btnSimpan').text('Simpan').attr('disabled', false);
                    let errors = xhr.responseJSON.errors;
                    
                    // Mapping Error (Manual satu per satu biar aman)
                    if (errors.nama_barang) { $('input[name="nama_barang"]').addClass('is-invalid'); $('#error-nama_barang').text(errors.nama_barang[0]); }
                    if (errors.kategori_id) { $('select[name="kategori_id"]').addClass('is-invalid'); $('#error-kategori_id').text(errors.kategori_id[0]); }
                    if (errors.serial_number) { $('input[name="serial_number"]').addClass('is-invalid'); $('#error-serial_number').text(errors.serial_number[0]); }
                    if (errors.gambar_barang) { $('input[name="gambar_barang"]').addClass('is-invalid'); $('#error-gambar_barang').text(errors.gambar_barang[0]); }
                }
            });
        });
    });
    
    // 1. LOGIKA DELETE AJAX (Sama kayak modul lain)
    $(document).on('click', '.btn-delete', function() {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Yakin hapus barang ini?',
            text: "Data dan file gambar akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Terhapus!', response.message, 'success').then(() => location.reload());
                    },
                    error: function(xhr) {
                        let pesan = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menghapus!';
                        Swal.fire('Gagal!', pesan, 'error');
                    }
                });
            }
        });
    });
    
    // 2. LOGIKA BUKA MODAL EDIT
    let editUrl = '';
    $(document).on('click', '.btn-edit', function() {
        let showUrl = $(this).data('url');
        editUrl = $(this).data('update-url');
        
        $('#formEditBarang')[0].reset();
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $.get(showUrl, function(data) {
            // Isi form dengan data
            $('#edit_nama_barang').val(data.nama_barang);
            $('#edit_kategori_id').val(data.kategori_id); // Dropdown otomatis kepilih
            $('#edit_merk').val(data.merk);
            $('#edit_serial_number').val(data.serial_number);
            $('#edit_stok_minimum').val(data.stok_minimum);
            $('#edit_kondisi').val(data.kondisi);
            $('#edit_lokasi_penyimpanan').val(data.lokasi_penyimpanan);
            
            $('#modalEditBarang').modal('show');
        });
    });
    
    // 3. LOGIKA UPDATE AJAX (Pakai FormData!)
    $('#formEditBarang').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this); // JURUS FormData untuk File
        
        $('#btnUpdate').text('Mengupdate...').attr('disabled', true);
        
        $.ajax({
            url: editUrl,
            type: "POST", // Tetap POST karena ada file, method PUT di-handle _method field
            data: formData,
            contentType: false, // Wajib buat upload
            processData: false, // Wajib buat upload
            success: function(response) {
                $('#modalEditBarang').modal('hide');
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: response.message, showConfirmButton: false, timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                $('#btnUpdate').text('Update').attr('disabled', false);
                let errors = xhr.responseJSON.errors;
                
                // Mapping Error (Contoh sebagian)
                if (errors.nama_barang) { $('#edit_nama_barang').addClass('is-invalid'); $('#error-edit-nama_barang').text(errors.nama_barang[0]); }
                if (errors.kategori_id) { $('#edit_kategori_id').addClass('is-invalid'); $('#error-edit-kategori_id').text(errors.kategori_id[0]); }
                if (errors.serial_number) { $('#edit_serial_number').addClass('is-invalid'); $('#error-edit-serial_number').text(errors.serial_number[0]); }
                // ... tambahkan mapping lain jika perlu ...
            }
        });
    });
    
    $('#tabel-barang').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
    
        // --- INI PENGATURAN POSISINYA (DOM) ---
        // Penjelasan kode:
        // <'row' ...> : Membuat baris baru (seperti <div class="row">)
        // <'col-...' ...> : Membuat kolom (seperti <div class="col-md-6">)
        // l : Length (Show entries)
        // f : Filter (Search)
        // t : Table (Tabel itu sendiri)
        // i : Info (Showing 1 to 10...)
        // p : Pagination (Previous - Next)
    
        "dom": "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
    });
</script>
@endpush
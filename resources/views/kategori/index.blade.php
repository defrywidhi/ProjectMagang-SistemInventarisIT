@extends ('layouts.master')

@section('content_title', 'Kategori')
@section('title', 'Kategori')

@section('content')
<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <!-- <a href="{{ route('kategori.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Tambah Kategori</a> -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="bi bi-plus-circle-fill"></i> Tambah Kategori
            </button>
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
            
            <table id="tabel-kategori" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Kode Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle text-center">
                    @forelse ($kategoris as $item )
                    <tr>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>{{ $item->kode_kategori }}</td>
                        <!-- <td>
                            <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kategori.destroy', $item->id) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('apakah yakin data ini akan dihapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td> -->
                        <td class="text-center align-middle p-2">
                            {{-- Tombol Edit (Pemicu AJAX) --}}
                            {{-- Kita simpan URL edit di atribut data-url --}}
                            <button type="button" class="btn btn-warning btn-sm btn-edit" 
                                    data-url="{{ route('kategori.edit', $item->id) }}" 
                                    data-update-url="{{ route('kategori.update', $item->id) }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            {{-- Tombol Hapus (Pemicu AJAX) --}}
                            {{-- Kita simpan URL destroy di atribut data-url --}}
                            <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                    data-url="{{ route('kategori.destroy', $item->id) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">Tidak ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


<!-- Modal untuk form input -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Form tanpa action, kita handle pakai JS --}}
            <form id="formTambahKategori">
                @csrf
                <div class="modal-body">
                    {{-- Input Nama --}}
                    <div class="form-group mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Laptop">
                        {{-- Tempat pesan error --}}
                        <div class="invalid-feedback" id="error-nama_kategori"></div>
                    </div>
                    
                    {{-- Input Kode --}}
                    <div class="form-group mb-3">
                        <label>Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control" placeholder="Contoh: LP">
                        <div class="invalid-feedback" id="error-kode_kategori"></div>
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

<!-- MODAL UNTUK EDIT DATA -->
 {{-- MODAL EDIT KATEGORI --}}
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning"> {{-- Warna kuning biar beda --}}
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKategori">
                @csrf
                @method('PUT') {{-- Penting untuk Update --}}
                <div class="modal-body">
                    {{-- Input Nama --}}
                    <div class="form-group mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" required>
                        <div class="invalid-feedback" id="error-edit-nama_kategori"></div>
                    </div>
                    
                    {{-- Input Kode --}}
                    <div class="form-group mb-3">
                        <label>Kode Kategori</label>
                        <input type="text" name="kode_kategori" id="edit_kode_kategori" class="form-control" required>
                        <div class="invalid-feedback" id="error-edit-kode_kategori"></div>
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
        
        $('#formTambahKategori').on('submit', function(e) {
            e.preventDefault(); // Stop form biar gak refresh halaman
            
            // Ambil data dari form
            let formData = $(this).serialize();
            
            // Reset pesan error dulu (biar bersih)
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Ubah tombol jadi "Loading..."
            $('#btnSimpan').text('Menyimpan...').attr('disabled', true);
            
            // Kirim Surat Lewat Belakang (AJAX)
            $.ajax({
                url: "{{ route('kategori.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    // JIKA SUKSES:
                    $('#modalTambahKategori').modal('hide'); // Tutup modal
                    $('#formTambahKategori')[0].reset(); // Bersihkan form
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500 // Otomatis tutup setelah 1.5 detik
                    }).then(() => {
                        location.reload(); // Refresh halaman setelah alert tutup
                    });
                },
                error: function(xhr) {
                    // JIKA ERROR (Validasi):
                    $('#btnSimpan').text('Simpan').attr('disabled', false); // Balikin tombol
                    
                    // Ambil daftar error dari Laravel
                    let errors = xhr.responseJSON.errors;
                    
                    // Tampilkan error di masing-masing input
                    if (errors.nama_kategori) {
                        $('input[name="nama_kategori"]').addClass('is-invalid');
                        $('#error-nama_kategori').text(errors.nama_kategori[0]);
                    }
                    if (errors.kode_kategori) {
                        $('input[name="kode_kategori"]').addClass('is-invalid');
                        $('#error-kode_kategori').text(errors.kode_kategori[0]);
                    }
                }
            });
        });
    });
    
    // ==========================================
    // 1. LOGIKA DELETE (HAPUS) VIA AJAX
    // ==========================================
    // Kita pakai 'on click' pada document karena tombolnya ada di dalam DataTables (elemen dinamis)
    $(document).on('click', '.btn-delete', function() {
        let url = $(this).data('url'); // Ambil URL dari tombol
        
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: "Data tidak bisa dikembalikan!",
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
                    data: {
                        _token: '{{ csrf_token() }}' // Wajib kirim token CSRF
                    },
                    success: function(response) {
                        Swal.fire('Terhapus!', response.message, 'success')
                        .then(() => location.reload());
                    },
                    error: function(xhr) {
                        // Handle error (misal: masih ada relasi barang)
                        let pesan = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem';
                        Swal.fire('Gagal!', pesan, 'error');
                    }
                });
            }
        });
    });
    
    // ==========================================
    // 2. LOGIKA BUKA MODAL EDIT (AMBIL DATA)
    // ==========================================
    let editUrl = ''; // Variabel global untuk simpan URL update saat ini
    
    $(document).on('click', '.btn-edit', function() {
        let showUrl = $(this).data('url'); // URL untuk ambil data (method edit)
        editUrl = $(this).data('update-url'); // URL untuk simpan data (method update)
        
        // Reset form & error sebelum buka
        $('#formEditKategori')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Ambil data kategori dari server
        $.get(showUrl, function(data) {
            // Isi form dengan data yang didapat
            $('#edit_nama_kategori').val(data.nama_kategori);
            $('#edit_kode_kategori').val(data.kode_kategori);
            
            // Tampilkan modal
            $('#modalEditKategori').modal('show');
        });
    });
    
    // ==========================================
    // 3. LOGIKA SIMPAN EDIT (UPDATE) VIA AJAX
    // ==========================================
    $('#formEditKategori').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $('#btnUpdate').text('Mengupdate...').attr('disabled', true);
        
        $.ajax({
            url: editUrl, // Pakai URL yang tadi kita simpan
            type: "POST", // Di form sudah ada @method('PUT'), jadi type tetap POST aman, atau ganti PUT juga bisa
            data: formData,
            success: function(response) {
                $('#modalEditKategori').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                $('#btnUpdate').text('Update').attr('disabled', false);
                let errors = xhr.responseJSON.errors;
                
                if (errors.nama_kategori) {
                    $('#edit_nama_kategori').addClass('is-invalid');
                    $('#error-edit-nama_kategori').text(errors.nama_kategori[0]);
                }
                if (errors.kode_kategori) {
                    $('#edit_kode_kategori').addClass('is-invalid');
                    $('#error-edit-kode_kategori').text(errors.kode_kategori[0]);
                }
            }
        });
    });
    
    $('#tabel-kategori').DataTable({
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
        
        "dom":  
            "<'row mb-3 mt-3'<'ml-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'mr-3 col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mb-3 mt-3'<'col-sm-12 col-md-5'i><'ml-3 col-sm-12 col-md-7'p>>",
    });
</script>
@endpush
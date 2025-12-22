@extends ('layouts.master')

@section('content_title', 'Supplier')
@section('title', 'Supplier')

@section('content')

<div class="container">
    <div class="card card-outline card-success">
        <div class="card-header">
            <!-- <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Tambah Supplier</a> -->

            <!-- Button untuk ajax -->
             <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSupplier">
                <i class="bi bi-plus-circle-fill"></i> Tambah Supplier
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

            <table id="tabel-supplier" class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @forelse ( $suppliers as $item )
                        <tr>
                            <td>{{ $item -> nama_supplier }}</td>
                            <td>{{ $item -> alamat }}</td>
                            <td>{{ $item -> nomor_telepon }}</td>
                            <td>{{ $item -> email ?? '_'}}</td>
                            <!-- <td class="text-center p-0">
                                <div class="">
                                <a href="{{ route('supplier.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form class="d-inline-block" action="{{ route('supplier.destroy', $item->id) }}"method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin menghapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                </div>
                            </td> -->

                            <!-- kolom aksi ajax -->
                            <td class="text-center align-middle p-2">
                                {{-- Tombol Edit (AJAX) --}}
                                <button type="button" class="btn btn-warning btn-sm btn-edit" 
                                        data-url="{{ route('supplier.edit', $item->id) }}" 
                                        data-update-url="{{ route('supplier.update', $item->id) }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Tombol Hapus (AJAX) --}}
                                <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                        data-url="{{ route('supplier.destroy', $item->id) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data supplier</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<!-- Modal untuk ajax -->
<div class="modal fade" id="modalTambahSupplier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Supplier Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Form AJAX --}}
            <form id="formTambahSupplier">
                @csrf
                <div class="modal-body">
                    {{-- Input Nama --}}
                    <div class="form-group mb-3">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                        <div class="invalid-feedback" id="error-nama_supplier"></div>
                    </div>
                    {{-- Input Alamat --}}
                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                        <div class="invalid-feedback" id="error-alamat"></div>
                    </div>
                    {{-- Input Telepon --}}
                    <div class="form-group mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-control" required>
                        <div class="invalid-feedback" id="error-nomor_telepon"></div>
                    </div>
                    {{-- Input Email --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                        <div class="invalid-feedback" id="error-email"></div>
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


{{-- MODAL EDIT SUPPLIER --}}
<div class="modal fade" id="modalEditSupplier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditSupplier">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama Supplier</label>
                        <input type="text" name="nama_supplier" id="edit_nama_supplier" class="form-control" required>
                        <div class="invalid-feedback" id="error-edit-nama_supplier"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
                        <div class="invalid-feedback" id="error-edit-alamat"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-control" required>
                        <div class="invalid-feedback" id="error-edit-nomor_telepon"></div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                        <div class="invalid-feedback" id="error-edit-email"></div>
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
        $('#formTambahSupplier').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            
            // Reset UI
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#btnSimpan').text('Menyimpan...').attr('disabled', true);
            
            $.ajax({
                url: "{{ route('supplier.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    $('#modalTambahSupplier').modal('hide');
                    $('#formTambahSupplier')[0].reset();
                    
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: response.message, showConfirmButton: false, timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    $('#btnSimpan').text('Simpan').attr('disabled', false);
                    let errors = xhr.responseJSON.errors;
                    
                    // Loop error untuk menampilkan pesan merah di input yang salah
                    if (errors.nama_supplier) {
                        $('input[name="nama_supplier"]').addClass('is-invalid');
                        $('#error-nama_supplier').text(errors.nama_supplier[0]);
                    }
                    if (errors.alamat) {
                        $('input[name="alamat"]').addClass('is-invalid');
                        $('#error-alamat').text(errors.alamat[0]);
                    }
                    if (errors.nomor_telepon) {
                        $('input[name="nomor_telepon"]').addClass('is-invalid');
                        $('#error-nomor_telepon').text(errors.nomor_telepon[0]);
                    }
                    if (errors.email) {
                        $('input[name="email"]').addClass('is-invalid');
                        $('#error-email').text(errors.email[0]);
                    }
                }
            });
        });

        $('.btn-delete').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Yakin hapus supplier ini?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    $(document).on('click', '.btn-delete', function() {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Yakin hapus supplier ini?',
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
    
    let editUrl = '';
    $(document).on('click', '.btn-edit', function() {
        let showUrl = $(this).data('url');
        editUrl = $(this).data('update-url');
        
        $('#formEditSupplier')[0].reset();
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $.get(showUrl, function(data) {
            $('#edit_nama_supplier').val(data.nama_supplier);
            $('#edit_alamat').val(data.alamat);
            $('#edit_nomor_telepon').val(data.nomor_telepon);
            $('#edit_email').val(data.email);
            $('#modalEditSupplier').modal('show');
        });
    });
    
    $('#formEditSupplier').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        $('#btnUpdate').text('Mengupdate...').attr('disabled', true);
        
        $.ajax({
            url: editUrl,
            type: "POST",
            data: formData,
            success: function(response) {
                $('#modalEditSupplier').modal('hide');
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: response.message, showConfirmButton: false, timer: 1500
                }).then(() => location.reload());
            },
            error: function(xhr) {
                $('#btnUpdate').text('Update').attr('disabled', false);
                let errors = xhr.responseJSON.errors;
                
                // Mapping Error ke Input
                if (errors.nama_supplier) { $('#edit_nama_supplier').addClass('is-invalid'); $('#error-edit-nama_supplier').text(errors.nama_supplier[0]); }
                if (errors.alamat) { $('#edit_alamat').addClass('is-invalid'); $('#error-edit-alamat').text(errors.alamat[0]); }
                if (errors.nomor_telepon) { $('#edit_nomor_telepon').addClass('is-invalid'); $('#error-edit-nomor_telepon').text(errors.nomor_telepon[0]); }
                if (errors.email) { $('#edit_email').addClass('is-invalid'); $('#error-edit-email').text(errors.email[0]); }
            }
        });
    });

    $('#tabel-supplier').DataTable({
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
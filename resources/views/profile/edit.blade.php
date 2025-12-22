@extends('layouts.master')

@section('title', 'Profil Saya')
@section('content_title', 'Pengaturan Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- KARTU 1: UPDATE INFO PROFIL --}}
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Informasi Profil</h3>
                </div>
                
                {{-- Form ini mengarah ke rute bawaan Breeze: profile.update --}}
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- KARTU 2: GANTI PASSWORD --}}
        <div class="col-md-6">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">Ganti Password</h3>
                </div>

                {{-- Form ini mengarah ke rute bawaan Breeze: password.update --}}
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-danger">Ganti Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-pen-fill"></i> Tanda Tangan Digital</h3>
                    </div>
                    
                    {{-- Form Upload TTD --}}
                    <form method="post" action="{{ route('profile.upload_ttd') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Upload Tanda Tangan (PNG Transparan)</label>
                                        <input type="file" name="ttd" class="form-control @error('ttd') is-invalid @enderror" accept=".png">
                                        <small class="text-muted">Format wajib .png (background transparan disarankan). Maks 2MB.</small>
                                        
                                        @error('ttd')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-warning mt-3">
                                        <i class="bi bi-upload"></i> Upload Tanda Tangan
                                    </button>
                                </div>

                                <div class="col-md-6 text-center">
                                    <label class="d-block mb-2">Preview Tanda Tangan Saat Ini</label>
                                    <div class="border p-3 d-inline-block bg-white rounded">
                                        @if(auth()->user()->ttd)
                                            {{-- Tampilkan gambar jika ada --}}
                                            <img src="{{ asset('storage/' . auth()->user()->ttd) }}" alt="Tanda Tangan" style="max-height: 100px; max-width: 100%;">
                                        @else
                                            {{-- Placeholder jika belum ada --}}
                                            <span class="text-muted fst-italic">Belum ada tanda tangan.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-pen-fill"></i> Atur PIN Persetujuan</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            PIN ini digunakan saat Anda melakukan <strong>Persetujuan (Approval)</strong> dokumen RAB. 
                            <br>PIN berbeda dengan Password Login. Gunakan 6 digit angka.
                        </div>
                        <form action="{{ route('profile.update-pin') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">PIN Baru (6 Angka)</label>
                                <div class="col-sm-9">
                                    <input type="password" name="pin" class="form-control @error('pin') is-invalid @enderror" 
                                        placeholder="Contoh: 123456" maxlength="6" inputmode="numeric" required>
                                    @error('pin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label class="col-sm-3 col-form-label">Konfirmasi PIN</label>
                                <div class="col-sm-9">
                                    <input type="password" name="pin_confirmation" class="form-control" 
                                        placeholder="Ketik ulang PIN baru" maxlength="6" inputmode="numeric" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-warning text-dark">
                                        <i class="bi bi-save"></i> Simpan PIN
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<script>
    // 1. Notifikasi Sukses Bawaan Laravel (Profil Updated)
    @if (session('status') === 'profile-updated')
        Swal.fire({
            icon: 'success',
            title: 'Profil Diperbarui!',
            text: 'Informasi profil Anda berhasil disimpan.',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // 2. Notifikasi Sukses Bawaan Laravel (Password Updated)
    @if (session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: 'Password Diubah!',
            text: 'Password akun Anda berhasil diganti.',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // 3. Notifikasi Sukses Custom (TTD & PIN)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}", // Pesan dinamis dari controller
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // 4. Notifikasi Error Custom
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33',
        });
    @endif

    // 5. Notifikasi Error Validasi Form (Jika ada input salah)
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
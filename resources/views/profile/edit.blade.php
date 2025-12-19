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
                        {{-- Pesan Sukses --}}
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Profil berhasil diperbarui.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

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
                        {{-- Pesan Sukses --}}
                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Password berhasil diubah.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

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

    </div>
</div>
@endsection
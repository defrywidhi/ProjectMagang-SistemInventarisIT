@extends('emails.email-layout')

@section('title', 'Permintaan Reset Password')

@section('header-title', 'Reset Password')
@section('header-subtitle', 'Laporan Permintaan dari Pengguna')

@section('content')
    <p class="greeting">Halo Admin,</p>
    
    <div class="content-block">
        <p>Ada pengguna yang melaporkan lupa password dan meminta bantuan reset.</p>
    </div>

    <div class="alert-box alert-warning">
        <strong>⚠️ Tindakan Diperlukan</strong><br>
        Mohon segera reset password pengguna ini untuk membantu mereka mengakses sistem kembali.
    </div>

    <div class="info-box">
        <h3 style="margin-bottom: 15px; color: #667eea;">Detail Pengguna</h3>
        <ul>
            <li><strong>Nama:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Role:</strong> {{ $user->getRoleNames()->first() }}</li>
            <li><strong>Waktu Lapor:</strong> {{ now()->format('d M Y, H:i') }} WIB</li>
        </ul>
    </div>

    <div class="content-block">
        <p>Langkah selanjutnya:</p>
        <ol style="padding-left: 20px; color: #555;">
            <li>Reset password melalui menu <span class="highlight-text">Manajemen User</span></li>
            <li>Sistem akan mengirimkan password baru ke email pengguna</li>
            <li>Pastikan pengguna mengganti password setelah login</li>
        </ol>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('users.edit', $user->id) }}" class="btn">
            🔧 Reset Password Sekarang
        </a>
    </div>
@endsection
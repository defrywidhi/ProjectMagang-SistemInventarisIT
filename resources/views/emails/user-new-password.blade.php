@extends('emails.email-layout')

@section('title', 'Password Baru')

@section('header-title', '🔑 Password Baru')
@section('header-subtitle', 'Akun Anda Telah Direset')

@section('content')
    <p class="greeting">Halo {{ $user->name }},</p>
    
    <div class="alert-box alert-success">
        <strong>✓ Reset Password Berhasil</strong><br>
        Admin telah mereset password akun Anda sesuai permintaan.
    </div>

    <div class="content-block">
        <p>Berikut adalah kredensial login baru Anda:</p>
    </div>

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; border: 2px dashed #667eea; margin: 20px 0;">
        <div style="margin-bottom: 15px;">
            <label style="color: #667eea; font-weight: 600; display: block; margin-bottom: 5px;">Email:</label>
            <div style="background-color: white; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 14px;">
                {{ $user->email }}
            </div>
        </div>
        <div>
            <label style="color: #667eea; font-weight: 600; display: block; margin-bottom: 5px;">Password Baru:</label>
            <div style="background-color: white; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 14px; font-weight: bold; color: #c62828;">
                {{ $password }}
            </div>
        </div>
    </div>

    <div class="alert-box alert-warning">
        <strong>🔒 Penting untuk Keamanan!</strong><br>
        Segera ganti password ini setelah login pertama kali. Password sementara ini dikirim melalui email yang tidak terenkripsi.
    </div>

    <div class="divider"></div>

    <div class="content-block">
        <p><strong>Langkah Selanjutnya:</strong></p>
        <ol style="padding-left: 20px; color: #555; margin-top: 10px;">
            <li>Klik tombol login di bawah ini</li>
            <li>Masukkan email dan password baru Anda</li>
            <li>Setelah login, segera buka menu <strong>Profil</strong></li>
            <li>Ganti password dengan password yang hanya Anda ketahui</li>
            <li>Gunakan password yang kuat (minimal 8 karakter, kombinasi huruf, angka, dan simbol)</li>
        </ol>
    </div>

    <div style="background-color: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #2196f3;">
        <strong style="color: #1565c0;">💡 Tips Password Kuat:</strong><br>
        <ul style="margin-top: 10px; color: #555; padding-left: 20px;">
            <li>Gunakan minimal 8 karakter</li>
            <li>Kombinasikan huruf besar, kecil, angka, dan simbol</li>
            <li>Hindari menggunakan informasi pribadi</li>
            <li>Jangan gunakan password yang sama dengan akun lain</li>
        </ul>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('login') }}" class="btn">
            🚀 Login Sekarang
        </a>
    </div>

    <div class="divider"></div>

    <p style="text-align: center; color: #777; font-size: 13px;">
        Jika Anda tidak merasa meminta reset password, segera hubungi administrator.<br>
        Jangan bagikan password Anda kepada siapapun, termasuk administrator.
    </p>
@endsection
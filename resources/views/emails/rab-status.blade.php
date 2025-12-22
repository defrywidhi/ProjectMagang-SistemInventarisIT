@extends('emails.email-layout')

@section('title', 'Update Status RAB')

@section('header-title', 'Update Status RAB')
@section('header-subtitle', 'Pemberitahuan Keputusan')

@section('content')
    <p class="greeting">Halo {{ $rab->pengaju->name }},</p>
    
    <div class="content-block">
        <p>Status RAB Anda dengan kode <strong class="highlight-text">{{ $rab->kode_rab }}</strong> telah diperbarui.</p>
    </div>

    <div class="info-box">
        <h3 style="margin-bottom: 15px; color: #667eea;">Informasi RAB</h3>
        <ul>
            <li><strong>Kode RAB:</strong> {{ $rab->kode_rab }}</li>
            <li><strong>Judul:</strong> {{ $rab->judul }}</li>
            <li><strong>Tanggal Keputusan:</strong> {{ now()->format('d M Y, H:i') }} WIB</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <div class="status-badge {{ $rab->status == 'Disetujui' ? 'status-approved' : 'status-rejected' }}">
            {{ $rab->status == 'Disetujui' ? '✓ DISETUJUI' : '✗ DITOLAK' }}
        </div>
    </div>

    @if($rab->status == 'Disetujui')
        <div class="alert-box alert-success">
            <strong>🎉 Selamat!</strong><br>
            RAB Anda telah disetujui oleh {{ $rab->penyetuju->name }}.
        </div>

        <div class="content-block">
            <p><strong>Langkah Selanjutnya:</strong></p>
            <ul style="padding-left: 20px; color: #555;">
                <li>Lanjutkan ke proses pencatatan pembelian</li>
                <li>Pastikan semua item sesuai dengan RAB yang disetujui</li>
                <li>Upload bukti pembelian setelah selesai</li>
            </ul>
        </div>

    @elseif($rab->status == 'Ditolak')
        <div class="alert-box alert-danger">
            <strong>📌 RAB Ditolak</strong><br>
            RAB Anda ditolak oleh {{ $rab->penyetuju->name }} dengan catatan berikut:
        </div>

        <div style="background-color: #fff3e0; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #ff9800;">
            <strong style="color: #e65100;">Catatan Penolakan:</strong><br>
            <p style="margin-top: 10px; color: #555; font-style: italic;">"{{ $rab->catatan_approval }}"</p>
        </div>

        <div class="content-block">
            <p><strong>Yang Harus Dilakukan:</strong></p>
            <ul style="padding-left: 20px; color: #555;">
                <li>Baca dengan teliti catatan penolakan di atas</li>
                <li>Lakukan perbaikan sesuai masukan</li>
                <li>Ajukan kembali RAB yang sudah direvisi</li>
            </ul>
        </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('rab.show', $rab->id) }}" class="btn">
            📄 Lihat Detail RAB
        </a>
    </div>

    @if($rab->status == 'Ditolak')
        <div class="divider"></div>
        <p style="text-align: center; color: #777; font-size: 14px;">
            Jangan ragu untuk menghubungi manajer jika ada yang perlu didiskusikan.
        </p>
    @endif
@endsection
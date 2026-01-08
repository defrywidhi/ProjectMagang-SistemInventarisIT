@extends('emails.email-layout')

@section('title', $rab->catatan_approval ? 'Revisi RAB' : 'Pengajuan RAB Baru')

@section('header-title', $rab->catatan_approval ? '🔄 Revisi RAB' : '📋 Pengajuan RAB Baru')
@section('header-subtitle', $rab->catatan_approval ? 'Permintaan Review Ulang' : 'Menunggu Persetujuan Anda')

@section('content')
    <p class="greeting">Halo Manajer,</p>
    
    @if($rab->catatan_approval)
        <div class="content-block">
            <p>Admin telah mengirimkan <strong>revisi</strong> untuk RAB yang sebelumnya Anda tolak.</p>
        </div>

        <div class="alert-box alert-danger">
            <strong>📝 Catatan Penolakan Sebelumnya:</strong><br>
            <em>"{{ $rab->catatan_approval }}"</em>
        </div>

        <div class="alert-box alert-warning">
            <strong>💡 Perhatian</strong><br>
            Admin telah melakukan perbaikan sesuai masukan Anda. Mohon review kembali untuk memastikan revisi sudah sesuai.
        </div>
    @else
        <div class="content-block">
            <p>Ada pengajuan RAB baru yang memerlukan persetujuan Anda. Mohon segera ditinjau agar proses pengadaan dapat berjalan lancar.</p>
        </div>
    @endif

    <div class="info-box">
        <h3 style="margin-bottom: 15px; color: #667eea;">Informasi RAB</h3>
        <ul>
            <li><strong>Kode RAB:</strong> {{ $rab->kode_rab }}</li>
            <li><strong>Judul:</strong> {{ $rab->judul }}</li>
            <li><strong>Diajukan Oleh:</strong> {{ $rab->pengaju->name }}</li>
            <li><strong>Tanggal:</strong> {{ $rab->tanggal_dibuat }}</li>
            @if($rab->catatan_approval)
                <li><strong>Status:</strong> <span style="color: #ff9800;">Revisi</span></li>
            @else
                <li><strong>Status:</strong> <span style="color: #2196f3;">Baru</span></li>
            @endif
        </ul>
    </div>

    <div class="divider"></div>

    <div class="content-block">
        <p><strong>Tindakan yang Diperlukan:</strong></p>
        <ul style="padding-left: 20px; color: #555;">
            <li>Review detail RAB dan item-item yang diajukan</li>
            <li>Periksa kelengkapan dokumen pendukung</li>
            <li>Berikan persetujuan atau catatan perbaikan</li>
        </ul>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('rab.show', $rab->id) }}" class="btn">
            👁️ Lihat & Review RAB
        </a>
    </div>
@endsection
@extends('emails.email-layout')

@section('title', 'Update Progress RAB')

@section('header-title', '📊 Update Progress RAB')
@section('header-subtitle', 'Pemberitahuan Kemajuan Approval')

@section('content')
    <p class="greeting">Halo {{ $rab->pengaju->name }},</p>
    
    <div class="alert-box alert-success">
        <strong>✓ Kabar Baik!</strong><br>
        RAB Anda telah disetujui oleh {{ $approver }}.
    </div>

    <div class="info-box">
        <h3 style="margin-bottom: 15px; color: #667eea;">Informasi RAB</h3>
        <ul>
            <li><strong>Kode RAB:</strong> {{ $rab->kode_rab }}</li>
            <li><strong>Judul:</strong> {{ $rab->judul }}</li>
            <li><strong>Status Saat Ini:</strong> <span style="color: #ff9800;">{{ $rab->status }}</span></li>
        </ul>
    </div>

    @if($rab->status == 'Menunggu Direktur')
        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #2196f3;">
            <strong style="color: #1565c0;">📌 Status Sekarang:</strong><br>
            <p style="margin-top: 10px; color: #555;">
                RAB Anda telah lolos tahap pertama dan sekarang sedang menunggu persetujuan dari <strong>Direktur</strong>. Harap bersabar menunggu keputusan final.
            </p>
        </div>

        <div class="content-block">
            <p><strong>Progress Approval:</strong></p>
            <div style="margin-top: 15px;">
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <span style="background-color: #4caf50; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px; font-weight: bold;">✓ SELESAI</span>
                    <span style="margin-left: 10px; color: #555;">Manager - {{ $rab->manager->name ?? 'N/A' }}</span>
                </div>
                <div style="display: flex; align-items: center;">
                    <span style="background-color: #ff9800; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px; font-weight: bold;">⏳ PROSES</span>
                    <span style="margin-left: 10px; color: #555;">Direktur - Menunggu Review</span>
                </div>
            </div>
        </div>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('rab.show', $rab->id) }}" class="btn">
            📄 Lihat Detail RAB
        </a>
    </div>

    <div class="divider"></div>

    <p style="text-align: center; color: #777; font-size: 13px;">
        Anda akan mendapatkan notifikasi kembali setelah ada keputusan final dari Direktur.
    </p>
@endsection
<!DOCTYPE html>
<html>
<body>
    {{-- Judul Dinamis --}}
    <h3>
        @if($rab->catatan_approval)
            [REVISI] Pengajuan Ulang RAB
        @else
            Permintaan Persetujuan RAB Baru
        @endif
    </h3>

    <p>Halo Manajer,</p>

    @if($rab->catatan_approval)
        <p>Admin telah mengirimkan <strong>revisi</strong> untuk RAB yang sebelumnya Anda tolak.</p>

        <div style="background-color: #ffebee; padding: 10px; border-left: 4px solid #f44336; margin-bottom: 15px;">
            <strong>Catatan Penolakan Sebelumnya:</strong><br>
            "{{ $rab->catatan_approval }}"
        </div>
    @else
        <p>Ada pengajuan RAB baru yang menunggu persetujuan Anda:</p>
    @endif

    <ul>
        <li><strong>Kode RAB:</strong> {{ $rab->kode_rab }}</li>
        <li><strong>Judul:</strong> {{ $rab->judul }}</li>
        <li><strong>Diajukan Oleh:</strong> {{ $rab->pengaju->name }}</li>
        <li><strong>Tanggal:</strong> {{ $rab->tanggal_dibuat }}</li>
    </ul>

    <p>Silakan login ke sistem untuk memeriksa revisi/detail dan melakukan approval.</p>

    <a href="{{ route('rab.show', $rab->id) }}">Klik disini untuk melihat RAB</a>
</body>
</html>
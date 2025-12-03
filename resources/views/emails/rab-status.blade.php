<!DOCTYPE html>
<html>

<body>
    <h3>Update Status RAB Anda</h3>
    <p>Halo {{ $rab->pengaju->name }},</p>
    <p>Status RAB Anda <strong>{{ $rab->kode_rab }}</strong> ({{ $rab->judul }}) telah diperbarui menjadi:</p>
    <h2 style="color: {{ $rab->status == 'Disetujui' ? 'green' : 'red' }}">
        {{ strtoupper($rab->status) }}
    </h2>

    @if($rab->status == 'Disetujui')
    <p>Disetujui oleh: {{ $rab->penyetuju->name }}</p>
    <p>Silakan lanjutkan ke proses pencatatan pembelian.</p>
    @elseif($rab->status == 'Ditolak')
    <p>Ditolak oleh: {{ $rab->penyetuju->name }}</p>
    <p><strong>Catatan:</strong> {{ $rab->catatan_approval }}</p>
    <p>Silakan revisi dan ajukan ulang.</p>
    @endif

    <a href="{{ route('rab.show', $rab->id) }}">Lihat Detail RAB</a>
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Opname - {{ $so->kode_opname }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 2px 0; color: #555; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px; vertical-align: top; }
        .label { font-weight: bold; width: 120px; }

        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; text-align: left; }
        .data-table th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .bg-danger { background-color: #ffcccc; } /* Merah muda buat selisih */
        .bg-success { background-color: #ccffcc; } /* Hijau muda buat sesuai */

        .signature { width: 100%; margin-top: 50px; }
        .signature td { text-align: center; width: 50%; }
        .sign-space { height: 80px; }
    </style>
</head>
<body>

    {{-- HEADER LAPORAN --}}
    <div class="header">
        <h2>BERITA ACARA STOK OPNAME</h2>
        <p>Sistem Inventory RS</p>
    </div>

    {{-- INFO SO --}}
    <table class="info-table">
        <tr>
            <td class="label">Kode Dokumen</td>
            <td>: {{ $so->kode_opname }}</td>
            <td class="label">Metode Cek</td>
            <td>: {{ $so->metode }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Opname</td>
            <td>: {{ \Carbon\Carbon::parse($so->tanggal_opname)->format('d F Y') }}</td>
            <td class="label">Status</td>
            <td>: {{ $so->status }}</td>
        </tr>
        <tr>
            <td class="label">Auditor</td>
            <td>: {{ $so->auditor->name }}</td>
            <td class="label">Tanggal Cetak</td>
            <td>: {{ $tanggal_cetak }}</td>
        </tr>
        <tr>
            <td class="label">Catatan</td>
            <td colspan="3">: {{ $so->catatan ?? '-' }}</td>
        </tr>
    </table>

    {{-- TABEL DATA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang</th>
                <th width="15%">Stok Sistem</th>
                <th width="15%">Stok Fisik</th>
                <th width="10%">Selisih</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $item)
            <tr class="{{ $item->status_fisik == 'Selisih' ? 'bg-danger' : '' }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    {{ $item->barang->nama_barang }} <br>
                    <small style="color: #555;">{{ $item->barang->merk }}</small>
                </td>
                <td class="text-center">{{ $item->stok_sistem }}</td>
                <td class="text-center">{{ $item->stok_fisik }}</td>
                <td class="text-center" style="font-weight: bold;">
                    {{ $item->selisih > 0 ? '+'.$item->selisih : $item->selisih }}
                </td>
                <td>{{ $item->keterangan_item ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RINGKASAN --}}
    <p>
        <strong>Ringkasan:</strong> <br>
        Total Item: {{ $details->count() }} | 
        Sesuai: {{ $details->where('status_fisik', 'Sesuai')->count() }} | 
        Selisih: {{ $details->where('status_fisik', 'Selisih')->count() }}
    </p>

    {{-- TANDA TANGAN --}}
    <table class="signature">
        <tr>
            <td>
                Dibuat Oleh,<br>
                <br>
                <div class="sign-space"></div>
                ( ........................................... )
            </td>
            <td>
                Mengetahui,<br>
                Kepala Gudang / Manajer
                <div class="sign-space"></div>
                ( ........................................... )
            </td>
        </tr>
    </table>

</body>
</html>
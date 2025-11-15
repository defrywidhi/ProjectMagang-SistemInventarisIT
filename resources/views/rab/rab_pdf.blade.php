<!DOCTYPE html>
<html>
<head>
    <title>RAB {{ $rab->kode_rab }}</title>
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .signature-table { margin-top: 50px; border: none; }
        .signature-table td { border: none; padding: 20px; text-align: center; }
    </style>
</head>
<body>
    <h1>RENCANA ANGGARAN BIAYA (RAB)</h1>

    <p><strong>PEKERJAAN:</strong> {{ $rab->judul }}</p>
    <p><strong>LOKASI:</strong> RSU KERTHA USADA</p>
    <p><strong>KODE RAB:</strong> {{ $rab->kode_rab }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Uraian Barang</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Ongkir</th>
                <th class="text-end">Asuransi</th>
                <th class="text-end">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @forelse ($rab->details as $detail)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $detail->nama_barang_diajukan }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-end">Rp {{ number_format($detail->perkiraan_harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->ongkir, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->asuransi, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
                @php $totalKeseluruhan += $detail->total_harga; @endphp
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada detail barang.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-end">TOTAL</th>
                <th class="text-end">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <br><br>
                Direktur RSU Kertha Usada Singaraja
                <br><br><br><br><br>
                (dr. I Wayan Parna Arianta, MARS)
            </td>
            <td>
                Singaraja, {{ \Carbon\Carbon::parse($rab->tanggal_disetujui ?? $rab->tanggal_dibuat)->format('d F Y') }}
                <br><br>
                Kepala Bidang Penunjang Medis
                <br><br><br><br><br>
                (...............................)
            </td>
        </tr>
    </table>
</body>
</html>
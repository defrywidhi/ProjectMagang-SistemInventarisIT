<!DOCTYPE html>
<html>
<head>
    <title>Bukti Masuk #{{ $transaksi->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 5px; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .data-table th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .signature { margin-top: 40px; width: 100%; }
        .signature td { text-align: center; width: 50%; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BUKTI PENERIMAAN BARANG (INVOICE)</h1>
        <p>Sistem Inventory Rumah Sakit</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="15%"><strong>No. Transaksi</strong></td>
            <td width="35%">: #TRX-IN-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
            <td width="15%"><strong>Tanggal Terima</strong></td>
            <td width="35%">: {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Supplier</strong></td>
            <td>: {{ $transaksi->supplier->nama_supplier }}</td>
            <td><strong>Diterima Oleh</strong></td>
            <td>: {{ $transaksi->user->name }}</td>
        </tr>
        <tr>
            <td><strong>Asal RAB</strong></td>
            <td>: {{ $transaksi->rab->kode_rab ?? '-' }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaksi->barang_it->nama_barang }}</td>
                <td>{{ $transaksi->barang_it->kategori->nama_kategori ?? '-' }}</td>
                <td class="text-center">{{ $transaksi->jumlah_masuk }} unit</td>
                <td class="text-end">Rp {{ number_format($transaksi->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($transaksi->jumlah_masuk * $transaksi->harga_satuan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">TOTAL NILAI</th>
                <th class="text-end">Rp {{ number_format($transaksi->jumlah_masuk * $transaksi->harga_satuan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="margin-bottom: 10px;">
        <strong>Keterangan:</strong><br>
        {{ $transaksi->keterangan ?? '-' }}
    </div>

    <table class="signature">
        <tr>
            <td>
                Diserahkan Oleh (Supplier),
                <br><br><br><br>
                (....................................)
            </td>
            <td>
                Diterima Oleh (Admin Gudang),
                <br><br><br><br>
                ( <strong>{{ $transaksi->user->name }}</strong> )
            </td>
        </tr>
    </table>

</body>
</html>
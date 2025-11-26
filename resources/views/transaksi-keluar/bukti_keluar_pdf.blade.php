<!DOCTYPE html>
<html>
<head>
    <title>Bukti Keluar #{{ $transaksi->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .data-table th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
        .signature { margin-top: 50px; width: 100%; }
        .signature td { text-align: center; width: 50%; vertical-align: top; }
        .line { border-bottom: 1px solid #000; width: 80%; margin: 50px auto 0 auto; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BUKTI PENGELUARAN BARANG</h1>
        <p>Sistem Inventory Rumah Sakit</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="20%"><strong>No. Transaksi</strong></td>
            <td>: #TRX-OUT-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Keluar</strong></td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal_keluar)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td><strong>Dicatat Oleh</strong></td>
            <td>: {{ $transaksi->user->name }} ({{ $transaksi->user->getRoleNames()->first() }})</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Keterangan / Keperluan</th>
                <th class="text-center">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaksi->barang_it->nama_barang }}</td>
                <td>{{ $transaksi->barang_it->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $transaksi->keterangan ?? '-' }}</td>
                <td class="text-center"><strong>{{ $transaksi->jumlah_keluar }}</strong> unit</td>
            </tr>
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td>
                Diserahkan Oleh (Gudang),
                <div class="line"></div>
                (Admin Gudang)
            </td>
            <td>
                Diterima Oleh (Teknisi/User),
                <div class="line"></div>
                (....................................)
            </td>
        </tr>
    </table>

</body>
</html>
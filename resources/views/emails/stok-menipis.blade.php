<!DOCTYPE html>
<html>
<head>
    <title>Peringatan Stok Menipis</title>
    <style> table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; } </style>
</head>
<body>
    <h2>Peringatan Stok Menipis</h2>
    <p>Halo Admin,</p>
    <p>Sistem Inventory mendeteksi ada beberapa barang yang stoknya sudah di bawah batas minimum. Mohon segera lakukan pengadaan.</p>

    <table style="width:100%">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok Saat Ini</th>
                <th>Stok Minimum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangs as $barang)
                <tr>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? 'N/A' }}</td>
                    <td style="text-align: center; color: red;"><strong>{{ $barang->stok }}</strong></td>
                    <td style="text-align: center;">{{ $barang->stok_minimum }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <p>Terima kasih,<br>Sistem Inventory Otomatis</p>
</body>
</html>
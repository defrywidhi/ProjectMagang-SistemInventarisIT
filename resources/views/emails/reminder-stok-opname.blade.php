<!DOCTYPE html>
<html>
<body>
    <h3>Waktunya Stok Opname!</h3>
    <p>Halo {{ $user->name }},</p>

    <p>Ini adalah pengingat otomatis bahwa kita sudah memasuki bulan baru.</p>
    <p>Mohon segera jadwalkan dan lakukan <strong>Stok Opname</strong> untuk memastikan data stok sistem sesuai dengan fisik di gudang.</p>

    <p>Silakan login ke sistem untuk memulai sesi:</p>
    <a href="{{ route('stok-opname.index') }}">Buka Menu Stok Opname</a>

    <br><br>
    <p>Terima kasih,<br>Sistem Inventory Otomatis</p>
</body>
</html>
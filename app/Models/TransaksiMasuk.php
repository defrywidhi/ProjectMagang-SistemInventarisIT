<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    //
    protected $table = 'transaksi_masuk';

    protected $fillable = [
        'barang_it_id',
        'supplier_id',
        'jumlah_masuk',
        'tanggal_masuk',
        'harga_satuan',
        'user_id',
        'keterangan',
        'rab_id',
    ];

    public function barang_it()
    {
        return $this->belongsTo(BarangIT::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rab()
    {
        return $this->belongsTo(Rab::class);
    }
}

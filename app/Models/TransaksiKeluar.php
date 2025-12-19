<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    //
    protected $table = 'transaksi_keluar';

    protected $fillable = [
        'barang_it_id',
        'jumlah_keluar',
        'tanggal_keluar',
        'user_id',
        'keterangan',
        'jumlah_dikembalikan',
    ];

    public function barang_it()
    {
        return $this->belongsTo(BarangIT::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Relasi ke Transaksi Masuk (Anak-anak returnya)
    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class, 'transaksi_keluar_id');
    }
}

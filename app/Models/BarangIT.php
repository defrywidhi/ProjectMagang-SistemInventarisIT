<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangIT extends Model
{
    //
    protected $table = 'barang_it';

    protected $fillable = [
        'kategori_id',
        'nama_barang',
        'merk',
        'serial_number',
        'deskripsi',
        'stok',
        'stok_minimum',
        'kondisi',
        'lokasi_penyimpanan',
        'gambar_barang',
    ];
    
    public function kategori(){
        return $this->belongsTo(Kategori::class);
    }

    public function transaksiMasuks(){
        return $this->hasMany(TransaksiMasuk::class);
    }

    public function transaksiKeluars(){
        return $this->hasMany(TransaksiKeluar::class);
    }
}
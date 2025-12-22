<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //

    protected $fillable =[
        'nama_supplier',
        'alamat',
        'nomor_telepon',
        'email'
    ];

    public function transaksiMasuks(){
        return $this->hasMany(TransaksiMasuk::class, 'supplier_id');
    }
}

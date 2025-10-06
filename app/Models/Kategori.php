<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    //
    protected $fillable = [
        'nama_kategori', 
        'kode_kategori'
    ];

    public function barangs(){
        return $this->hasMany(BarangIT::class);
    }
}

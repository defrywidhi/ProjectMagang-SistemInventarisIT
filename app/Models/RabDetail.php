<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rab;

class RabDetail extends Model
{
    //
    protected $fillable = [
        'rab_id',
        'barang_it_id',
        'nama_barang_custom',
        'foto_custom',
        'nama_barang_diajukan',
        'jumlah',
        'perkiraan_harga_satuan',
        'ongkir',
        'asuransi',
        'total_harga',
        'keterangan',
    ];

    public function rab(){
        return $this->belongsTo(Rab::class);
    }

    public function barang_it(){
        return $this->belongsTo(BarangIT::class);
    }
}

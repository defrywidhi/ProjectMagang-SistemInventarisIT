<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rab;

class RabDetail extends Model
{
    //
    protected $fillable = [
        'rab_id',
        'nama_barang_diajukan',
        'jumlah',
        'perkiraan_harga_satuan',
        'ongkir',
        'asuransi',
        'total_harga',
    ];

    public function rab(){
        return $this->belongsTo(Rab::class);
    }
}

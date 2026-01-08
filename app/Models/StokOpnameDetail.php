<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpnameDetail extends Model
{
    //
    protected $fillable = [
        'stok_opname_id',
        'barang_it_id',
        'stok_sistem',
        'stok_fisik', 
        'selisih',
        'status_fisik',
        'keterangan_item',
    ];

    public function header()
    {
        return $this->belongsTo(StokOpname::class, 'stok_opname_id');
    }

    public function barangIt()
    {
        return $this->belongsTo(BarangIt::class);
    }

    public function barang()
    {
        return $this->belongsTo(BarangIT::class, 'barang_it_id');
    }
}

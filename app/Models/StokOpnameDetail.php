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
        'keterangan_item',
    ];

    public function stokOpname()
    {
        return $this->belongsTo(StokOpname::class);
    }

    public function barangIt()
    {
        return $this->belongsTo(BarangIt::class);
    }
}

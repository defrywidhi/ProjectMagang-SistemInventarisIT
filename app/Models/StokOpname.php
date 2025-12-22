<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    //
    protected $fillable = [
        'kode_opname',    
        'tanggal_opname',
        'metode',       
        'catatan',
        'status',
        'user_id',
    ];

    public function details()
    {
        return $this->hasMany(StokOpnameDetail::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    //
    protected $fillable = [
        'tanggal_opname',
        'user_id',
        'status',
        'catatan',
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

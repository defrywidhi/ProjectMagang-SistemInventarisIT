<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode_rab',
        'judul',
        'user_id',
        'status',
        'tanggal_dibuat',
        'tanggal_disetujui',
        'approved_by',
        'catatan_approval',
    ];

    public function pengaju(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penyetuju(){
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details(){
        return $this->hasMany(RabDetail::class);
    }
}

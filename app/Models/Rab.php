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
        'manager_id',
        'manager_at',
        'direktur_id',
        'direktur_at',
        'catatan_approval',
    ];

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(RabDetail::class);
    }

    public function transaksiMasuks()
    {
        return $this->hasMany(TransaksiMasuk::class);
    }

    // Relasi ke User Manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relasi ke User Direktur
    public function direktur()
    {
        return $this->belongsTo(User::class, 'direktur_id');
    }
}

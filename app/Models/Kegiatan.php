<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';

    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function jadwalDetails()
    {
        return $this->hasMany(
            JadwalDetail::class,
            'kegiatan_id'
        );
    }
}
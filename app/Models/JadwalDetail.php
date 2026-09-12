<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalDetail extends Model
{
    use HasFactory;

    protected $table = 'jadwal_details';

    protected $fillable = [
        'jadwal_id',
        'posyandu_id',
        'kegiatan_id',
        'tgl_mulai',
        'tgl_selesai',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function jadwal()
    {
        return $this->belongsTo(
            JadwalBulanan::class,
            'jadwal_id'
        );
    }

    public function posyandu()
    {
        return $this->belongsTo(
            Posyandu::class,
            'posyandu_id'
        );
    }

    public function kegiatan()
    {
        return $this->belongsTo(
            Kegiatan::class,
            'kegiatan_id'
        );
    }
}
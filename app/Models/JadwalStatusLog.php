<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalStatusLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'jadwal_status_logs';

    protected $fillable = [
        'jadwal_id',
        'user_id',
        'status_lama',
        'status_baru',
        'keterangan',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function jadwal()
    {
        return $this->belongsTo(
            JadwalBulanan::class,
            'jadwal_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}
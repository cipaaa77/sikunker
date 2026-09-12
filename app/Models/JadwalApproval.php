<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalApproval extends Model
{
    use HasFactory;

    protected $table = 'jadwal_approvals';

    protected $fillable = [
        'jadwal_id',
        'user_id',
        'status',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
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
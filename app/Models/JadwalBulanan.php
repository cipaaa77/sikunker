<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalBulanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulan',
        'tahun',
        'status',
        'catatan',
        'dibuat_oleh',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(JadwalDetail::class, 'jadwal_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(JadwalApproval::class, 'jadwal_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(JadwalStatusLog::class, 'jadwal_id');
    }
}
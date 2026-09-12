<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalBulanan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_bulanans';

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

    /*
    |--------------------------------------------------------------------------
    | User pembuat jadwal
    |--------------------------------------------------------------------------
    */

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'dibuat_oleh',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | User yang menyetujui jadwal
    |--------------------------------------------------------------------------
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail jadwal
    |--------------------------------------------------------------------------
    */

    public function details(): HasMany
    {
        return $this->hasMany(
            JadwalDetail::class,
            'jadwal_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Log status
    |--------------------------------------------------------------------------
    */

    public function statusLogs(): HasMany
    {
        return $this->hasMany(
            JadwalStatusLog::class,
            'jadwal_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */

    public function approvals(): HasMany
    {
        return $this->hasMany(
            JadwalApproval::class,
            'jadwal_id',
            'id'
        );
    }
}
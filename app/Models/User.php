<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jadwalDibuat(): HasMany
    {
        return $this->hasMany(
            JadwalBulanan::class,
            'dibuat_oleh'
        );
    }

    public function jadwalDisetujui(): HasMany
    {
        return $this->hasMany(
            JadwalBulanan::class,
            'approved_by'
        );
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(
            JadwalApproval::class,
            'user_id'
        );
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(
            JadwalStatusLog::class,
            'user_id'
        );
    }
}
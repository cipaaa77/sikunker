<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posyandu extends Model
{
    use HasFactory;

    protected $fillable = [
        'wilayah_id',
        'kode_posyandu',
        'nama_posyandu',
        'alamat',
        'ketua',
        'kontak',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function hariOperasionals(): HasMany
    {
        return $this->hasMany(HariOperasional::class);
    }

    public function jadwalDetails(): HasMany
    {
        return $this->hasMany(JadwalDetail::class);
    }
}
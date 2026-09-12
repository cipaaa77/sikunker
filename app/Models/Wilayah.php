<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_wilayah',
        'rw',
        'kelurahan',
        'kecamatan',
        'alamat',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function posyandus(): HasMany
    {
        return $this->hasMany(Posyandu::class);
    }
}
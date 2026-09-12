<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HariOperasional extends Model
{
    use HasFactory;

    protected $fillable = [
        'posyandu_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'aktif',
    ];

    protected $casts = [
        'hari' => 'integer',
        'aktif' => 'boolean',
    ];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }
}
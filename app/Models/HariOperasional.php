<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariOperasional extends Model
{
    use HasFactory;

    protected $table = 'hari_operasionals';

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

    public function posyandu()
    {
        return $this->belongsTo(
            Posyandu::class,
            'posyandu_id'
        );
    }

    public function getNamaHariAttribute(): string
    {
        return match ((int) $this->hari) {
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
            default => '-',
        };
    }

    public function getJamMulaiFormatAttribute(): string
    {
        return $this->jam_mulai
            ? substr((string) $this->jam_mulai, 0, 5)
            : '-';
    }

    public function getJamSelesaiFormatAttribute(): string
    {
        return $this->jam_selesai
            ? substr((string) $this->jam_selesai, 0, 5)
            : '-';
    }

    public function getJamOperasionalAttribute(): string
    {
        return $this->jam_mulai_format
            . ' - '
            . $this->jam_selesai_format;
    }
}
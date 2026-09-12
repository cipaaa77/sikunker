<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $table = 'posyandus';

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

    /*
    |--------------------------------------------------------------------------
    | Relasi Wilayah
    |--------------------------------------------------------------------------
    */

    public function wilayah()
    {
        return $this->belongsTo(
            Wilayah::class,
            'wilayah_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Hari Operasional
    |--------------------------------------------------------------------------
    */

    public function hariOperasionals()
    {
        return $this->hasMany(
            HariOperasional::class,
            'posyandu_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Detail Jadwal
    |--------------------------------------------------------------------------
    */

    public function jadwalDetails()
    {
        return $this->hasMany(
            JadwalDetail::class,
            'posyandu_id'
        );
    }
}
<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        Wilayah::create([
            'nama_wilayah' => 'RW 01',
            'rw' => '01',
            'kelurahan' => 'Kelurahan Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'alamat' => 'Wilayah RW 01',
            'aktif' => true,
        ]);

        Wilayah::create([
            'nama_wilayah' => 'RW 02',
            'rw' => '02',
            'kelurahan' => 'Kelurahan Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'alamat' => 'Wilayah RW 02',
            'aktif' => true,
        ]);

        Wilayah::create([
            'nama_wilayah' => 'RW 03',
            'rw' => '03',
            'kelurahan' => 'Kelurahan Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'alamat' => 'Wilayah RW 03',
            'aktif' => true,
        ]);

        Wilayah::create([
            'nama_wilayah' => 'RW 04',
            'rw' => '04',
            'kelurahan' => 'Kelurahan Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'alamat' => 'Wilayah RW 04',
            'aktif' => true,
        ]);

        Wilayah::create([
            'nama_wilayah' => 'RW 05',
            'rw' => '05',
            'kelurahan' => 'Kelurahan Contoh',
            'kecamatan' => 'Kecamatan Contoh',
            'alamat' => 'Wilayah RW 05',
            'aktif' => true,
        ]);
    }
}
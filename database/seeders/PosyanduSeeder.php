<?php

namespace Database\Seeders;

use App\Models\Posyandu;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $rw01 = Wilayah::where('rw', '01')->first();
        $rw02 = Wilayah::where('rw', '02')->first();
        $rw03 = Wilayah::where('rw', '03')->first();
        $rw04 = Wilayah::where('rw', '04')->first();
        $rw05 = Wilayah::where('rw', '05')->first();

        /*
         * RW 01
         */
        Posyandu::create([
            'wilayah_id' => $rw01->id,
            'kode_posyandu' => 'POS-001',
            'nama_posyandu' => 'Posyandu Melati',
            'alamat' => 'RW 01',
            'ketua' => 'Ketua Posyandu Melati',
            'kontak' => '081234567801',
            'aktif' => true,
        ]);

        /*
         * RW 02
         */
        Posyandu::create([
            'wilayah_id' => $rw02->id,
            'kode_posyandu' => 'POS-002',
            'nama_posyandu' => 'Posyandu Mawar',
            'alamat' => 'RW 02',
            'ketua' => 'Ketua Posyandu Mawar',
            'kontak' => '081234567802',
            'aktif' => true,
        ]);

        /*
         * RW 03
         */
        Posyandu::create([
            'wilayah_id' => $rw03->id,
            'kode_posyandu' => 'POS-003',
            'nama_posyandu' => 'Posyandu Kenanga',
            'alamat' => 'RW 03',
            'ketua' => 'Ketua Posyandu Kenanga',
            'kontak' => '081234567803',
            'aktif' => true,
        ]);

        /*
         * RW 04
         */
        Posyandu::create([
            'wilayah_id' => $rw04->id,
            'kode_posyandu' => 'POS-004',
            'nama_posyandu' => 'Posyandu Anggrek',
            'alamat' => 'RW 04',
            'ketua' => 'Ketua Posyandu Anggrek',
            'kontak' => '081234567804',
            'aktif' => true,
        ]);

        /*
         * RW 05
         */
        Posyandu::create([
            'wilayah_id' => $rw05->id,
            'kode_posyandu' => 'POS-005',
            'nama_posyandu' => 'Posyandu Mawar',
            'alamat' => 'RW 05',
            'ketua' => 'Ketua Posyandu Mawar',
            'kontak' => '081234567805',
            'aktif' => true,
        ]);
    }
}
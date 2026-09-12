<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        Kegiatan::create([
            'kode_kegiatan' => 'KGT-001',
            'nama_kegiatan' => 'Imunisasi',
            'deskripsi' => 'Pelayanan imunisasi bagi sasaran Posyandu.',
            'aktif' => true,
        ]);

        Kegiatan::create([
            'kode_kegiatan' => 'KGT-002',
            'nama_kegiatan' => 'Penimbangan Balita',
            'deskripsi' => 'Penimbangan dan pemantauan pertumbuhan balita.',
            'aktif' => true,
        ]);

        Kegiatan::create([
            'kode_kegiatan' => 'KGT-003',
            'nama_kegiatan' => 'Kelas Ibu Hamil',
            'deskripsi' => 'Kegiatan edukasi dan pendampingan ibu hamil.',
            'aktif' => true,
        ]);

        Kegiatan::create([
            'kode_kegiatan' => 'KGT-004',
            'nama_kegiatan' => 'Pemberian Vitamin',
            'deskripsi' => 'Pemberian vitamin kepada sasaran kegiatan.',
            'aktif' => true,
        ]);

        Kegiatan::create([
            'kode_kegiatan' => 'KGT-005',
            'nama_kegiatan' => 'Pemeriksaan Lansia',
            'deskripsi' => 'Pemeriksaan kesehatan bagi lansia.',
            'aktif' => true,
        ]);
    }
}
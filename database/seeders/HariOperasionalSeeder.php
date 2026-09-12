<?php

namespace Database\Seeders;

use App\Models\HariOperasional;
use App\Models\Posyandu;
use Illuminate\Database\Seeder;

class HariOperasionalSeeder extends Seeder
{
    public function run(): void
    {
        $melati = Posyandu::where(
            'kode_posyandu',
            'POS-001'
        )->first();

        $mawar = Posyandu::where(
            'kode_posyandu',
            'POS-002'
        )->first();

        $kenanga = Posyandu::where(
            'kode_posyandu',
            'POS-003'
        )->first();

        $anggrek = Posyandu::where(
            'kode_posyandu',
            'POS-004'
        )->first();

        $mawar2 = Posyandu::where(
            'kode_posyandu',
            'POS-005'
        )->first();

        /*
         * Posyandu Melati
         * Senin
         */
        HariOperasional::create([
            'posyandu_id' => $melati->id,
            'hari' => 1,
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'aktif' => true,
        ]);

        /*
         * Posyandu Mawar RW 02
         * Selasa
         */
        HariOperasional::create([
            'posyandu_id' => $mawar->id,
            'hari' => 2,
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'aktif' => true,
        ]);

        /*
         * Posyandu Kenanga
         * Rabu
         */
        HariOperasional::create([
            'posyandu_id' => $kenanga->id,
            'hari' => 3,
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'aktif' => true,
        ]);

        /*
         * Posyandu Anggrek
         * Kamis
         */
        HariOperasional::create([
            'posyandu_id' => $anggrek->id,
            'hari' => 4,
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'aktif' => true,
        ]);

        /*
         * Posyandu Mawar RW 05
         * Jumat
         */
        HariOperasional::create([
            'posyandu_id' => $mawar2->id,
            'hari' => 5,
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:00',
            'aktif' => true,
        ]);
    }
}
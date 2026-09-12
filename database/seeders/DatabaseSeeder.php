<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            /*
             * 1. User
             */
            UserSeeder::class,

            /*
             * 2. Wilayah
             */
            WilayahSeeder::class,

            /*
             * 3. Posyandu membutuhkan wilayah_id
             */
            PosyanduSeeder::class,

            /*
             * 4. Master kegiatan
             */
            KegiatanSeeder::class,

            /*
             * 5. Hari operasional membutuhkan posyandu_id
             */
            HariOperasionalSeeder::class,
        ]);
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * ADMIN
         */
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@posyandu.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        /*
         * PETUGAS
         */
        User::create([
            'name' => 'Petugas Posyandu',
            'email' => 'petugas@posyandu.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        /*
         * KOORDINATOR
         */
        User::create([
            'name' => 'Koordinator Posyandu',
            'email' => 'koordinator@posyandu.test',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
        ]);
    }
}
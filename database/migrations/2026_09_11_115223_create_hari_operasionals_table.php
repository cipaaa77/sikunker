<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hari_operasionals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('posyandu_id')
                ->constrained('posyandus')
                ->cascadeOnDelete();

            /*
             * 1 = Senin
             * 2 = Selasa
             * 3 = Rabu
             * 4 = Kamis
             * 5 = Jumat
             * 6 = Sabtu
             * 7 = Minggu
             */
            $table->unsignedTinyInteger('hari');

            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->unique([
                'posyandu_id',
                'hari'
            ]);

            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_operasionals');
    }
};
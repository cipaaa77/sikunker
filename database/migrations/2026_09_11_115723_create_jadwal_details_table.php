<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('posyandu_id')
                ->constrained('posyandus')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('kegiatan_id')
                ->constrained('kegiatans')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('tgl_mulai');

            $table->date('tgl_selesai');

            $table->time('jam_mulai');

            $table->time('jam_selesai');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_details');
    }
};
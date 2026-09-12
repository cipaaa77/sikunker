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

            /*
             * Header jadwal bulanan
             */
            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnDelete();

            /*
             * Posyandu
             */
            $table->foreignId('posyandu_id')
                ->constrained('posyandus')
                ->restrictOnDelete();

            /*
             * Kegiatan
             */
            $table->foreignId('kegiatan_id')
                ->constrained('kegiatans')
                ->restrictOnDelete();

            /*
             * Tanggal kegiatan
             */
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');

            /*
             * Jam kegiatan
             */
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            /*
             * DG = Dalam Gedung
             * LG = Luar Gedung
             */
            $table->enum('tipe_kegiatan', [
                'DG',
                'LG'
            ])->default('DG');

            /*
             * Status pelaksanaan
             */
            $table->enum('status', [
                'terjadwal',
                'selesai',
                'dibatalkan'
            ])->default('terjadwal');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            /*
             * Index untuk pencarian dan
             * conflict detection
             */
            $table->index('jadwal_id');
            $table->index('posyandu_id');
            $table->index('kegiatan_id');
            $table->index('tgl_mulai');
            $table->index('tgl_selesai');
            $table->index('status');
            $table->index('tipe_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_details');
    }
};
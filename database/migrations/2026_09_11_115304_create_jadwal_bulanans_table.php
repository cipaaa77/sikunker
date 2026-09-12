<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_bulanans', function (Blueprint $table) {
            $table->id();

            /*
             * Periode jadwal
             */
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');

            /*
             * Workflow:
             *
             * draft
             * diajukan
             * disetujui
             * ditolak
             * final
             */
            $table->enum('status', [
                'draft',
                'diajukan',
                'disetujui',
                'ditolak',
                'final'
            ])->default('draft');

            $table->text('catatan')->nullable();

            /*
             * Pembuat jadwal
             */
            $table->foreignId('dibuat_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
             * User yang memberikan approval terakhir
             */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            /*
             * Satu periode hanya memiliki satu
             * jadwal bulanan.
             */
            $table->unique([
                'bulan',
                'tahun'
            ]);

            $table->index('status');
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_bulanans');
    }
};
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

            $table->unsignedTinyInteger('bulan');

            $table->unsignedSmallInteger('tahun');

            $table->enum('status', [
                'draft',
                'diajukan',
                'disetujui',
            ])->default('draft');

            $table->text('catatan')->nullable();

            $table->foreignId('dibuat_oleh')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique([
                'bulan',
                'tahun',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_bulanans');
    }
};
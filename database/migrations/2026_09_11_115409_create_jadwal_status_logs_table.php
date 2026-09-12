<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status_lama', [
                'draft',
                'diajukan',
                'disetujui',
            ])->nullable();

            $table->enum('status_baru', [
                'draft',
                'diajukan',
                'disetujui',
            ]);

            $table->text('keterangan')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_status_logs');
    }
};
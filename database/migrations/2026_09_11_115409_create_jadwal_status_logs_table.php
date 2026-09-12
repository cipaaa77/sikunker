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

            /*
             * Jadwal yang statusnya berubah
             */
            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnDelete();

            /*
             * User yang melakukan perubahan
             */
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Status sebelum perubahan
             */
            $table->string('status_lama')->nullable();

            /*
             * Status setelah perubahan
             */
            $table->string('status_baru');

            /*
             * Keterangan perubahan
             */
            $table->text('keterangan')->nullable();

            /*
             * Log hanya membutuhkan created_at
             */
            $table->timestamp('created_at')->useCurrent();

            $table->index('jadwal_id');
            $table->index('user_id');
            $table->index('status_baru');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_status_logs');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_approvals', function (Blueprint $table) {
            $table->id();

            /*
             * Jadwal yang diverifikasi
             */
            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnDelete();

            /*
             * User yang melakukan approval
             */
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Status approval
             */
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak'
            ]);

            /*
             * Catatan koordinator
             */
            $table->text('catatan')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index('jadwal_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_approvals');
    }
};
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

            $table->foreignId('jadwal_id')
                ->constrained('jadwal_bulanans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
            ]);

            $table->text('catatan')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique('jadwal_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_approvals');
    }
};
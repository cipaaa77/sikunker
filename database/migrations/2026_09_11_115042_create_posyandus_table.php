<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posyandus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wilayah_id')
                ->constrained('wilayahs')
                ->restrictOnDelete();

            $table->string('kode_posyandu')->unique();
            $table->string('nama_posyandu');

            $table->text('alamat')->nullable();

            $table->string('ketua')->nullable();
            $table->string('kontak')->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->index('wilayah_id');
            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posyandus');
    }
};
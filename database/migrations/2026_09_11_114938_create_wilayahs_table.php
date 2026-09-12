<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();

            $table->string('nama_wilayah');
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();

            $table->text('alamat')->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->index('rw');
            $table->index('kelurahan');
            $table->index('kecamatan');
            $table->index('aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wilayahs');
    }
};
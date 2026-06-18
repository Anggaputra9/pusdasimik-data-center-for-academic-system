<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_pengajuan_judul', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan')->nullable();
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->string('prodi')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_pengajuan_judul');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nim_mahasiswa', 20);
            $table->string('nama_mahasiswa', 100)->nullable();
            $table->string('kode_kelas', 20);
            $table->string('nama_mata_kuliah', 150);
            $table->enum('status_kehadiran', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha']);
            $table->dateTime('waktu');
            $table->string('sistem_asal', 50)->default('sistem-presensi')->comment('Nama sistem yang mengirim data');
            $table->timestamps();

            // Index untuk pencarian
            $table->index('nim_mahasiswa');
            $table->index('kode_kelas');
            $table->index('waktu');
            $table->index('sistem_asal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_presensi');
    }
};

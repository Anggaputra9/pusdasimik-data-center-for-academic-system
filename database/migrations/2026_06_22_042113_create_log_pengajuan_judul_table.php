<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('log_pengajuan_judul', function (Blueprint $table) {
            $table->id();

            // Data mahasiswa dari sistem pengajuan judul
            $table->string('nim', 20);
            $table->string('nama_mahasiswa', 100)->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->string('fakultas', 100)->nullable();

            // Data pengajuan
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->string('bidang', 100)->nullable();

            // Status pengajuan dari sistem pengajuan judul
            $table->enum('status', [
                'diajukan',
                'pending',
                'disetujui',
                'ditolak',
                'revisi',
            ])->default('diajukan');

            // Data pembimbing, kalau sudah dipilih admin
            $table->string('pembimbing_1_nip', 30)->nullable();
            $table->string('pembimbing_1_nama', 100)->nullable();
            $table->string('pembimbing_2_nip', 30)->nullable();
            $table->string('pembimbing_2_nama', 100)->nullable();

            // Catatan tambahan dari sistem pengajuan judul/admin
            $table->text('catatan')->nullable();

            // ID asli dari sistem pengajuan judul, supaya bisa dilacak
            $table->unsignedBigInteger('external_submission_id')->nullable();

            $table->timestamps();

            $table->index('nim');
            $table->index('status');
            $table->index('external_submission_id');
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pengajuan_judul');
    }
};
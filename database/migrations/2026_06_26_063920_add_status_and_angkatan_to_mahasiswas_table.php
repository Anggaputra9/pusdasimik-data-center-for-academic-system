<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Tambahkan kolom tanpa after clause agar lebih aman
            if (!Schema::hasColumn('mahasiswas', 'email')) {
                $table->string('email', 100)->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'angkatan')) {
                $table->integer('angkatan')->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'status')) {
                $table->enum('status', ['aktif', 'cuti', 'non_aktif', 'lulus', 'keluar'])
                      ->default('aktif')
                      ->comment('Status mahasiswa untuk permission control');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['email', 'angkatan', 'status']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('tahun_akademik', 20)->default('2025/2026 Genap')->after('unit_id');
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'do'])->default('aktif')->after('tahun_akademik');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['tahun_akademik', 'status']);
        });
    }
};

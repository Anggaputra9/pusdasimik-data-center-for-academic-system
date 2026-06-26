<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE log_pengajuan_judul MODIFY status VARCHAR(50) NOT NULL DEFAULT 'diajukan'");
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE log_pengajuan_judul MODIFY status ENUM('diajukan', 'pending', 'disetujui', 'ditolak', 'revisi') NOT NULL DEFAULT 'diajukan'");
    }
};
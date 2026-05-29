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
            // Drop old columns
            $table->dropColumn(['program_studi', 'fakultas']);
            
            // Add new foreign key column to units table
            $table->foreignId('unit_id')->after('nama')->constrained('units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            
            // Restore old columns
            $table->string('program_studi', 100)->after('nama');
            $table->string('fakultas', 100)->after('program_studi');
        });
    }
};

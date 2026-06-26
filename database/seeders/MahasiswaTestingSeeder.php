<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaTestingSeeder extends Seeder
{
    /**
     * Seed mahasiswa untuk testing sistem terintegrasi
     */
    public function run(): void
    {
        $mahasiswaData = [
            // Mahasiswa AKTIF (eligible untuk semua)
            [
                'nim' => '2024001',
                'nama' => 'Budi Santoso',
                'email' => '2024001@student.ac.id',
                'unit_id' => 1, // Default unit ID
                'angkatan' => 2024,
                'status' => 'aktif', // Status aktif
            ],
            [
                'nim' => '2024002',
                'nama' => 'Ani Wijaya',
                'email' => '2024002@student.ac.id',
                'unit_id' => 1,
                'angkatan' => 2024,
                'status' => 'aktif',
            ],
            [
                'nim' => '2024003',
                'nama' => 'Citra Dewi',
                'email' => '2024003@student.ac.id',
                'unit_id' => 1,
                'angkatan' => 2024,
                'status' => 'aktif',
            ],
            
            // Mahasiswa TIDAK AKTIF (tidak eligible)
            [
                'nim' => '2024004',
                'nama' => 'Dedi Kurniawan',
                'email' => '2024004@student.ac.id',
                'unit_id' => 1,
                'angkatan' => 2024,
                'status' => 'cuti', // Status cuti = tidak aktif
            ],
            [
                'nim' => '2024005',
                'nama' => 'Eka Putri',
                'email' => '2024005@student.ac.id',
                'unit_id' => 1,
                'angkatan' => 2024,
                'status' => 'cuti', // Status cuti/tidak aktif
            ],
        ];

        foreach ($mahasiswaData as $data) {
            Mahasiswa::updateOrCreate(
                ['nim' => $data['nim']], // Find by NIM
                $data // Update or create with this data
            );
        }

        $this->command->info('✅ Seeder Mahasiswa Testing berhasil!');
        $this->command->info('📊 Data yang dibuat:');
        $this->command->info('   - 3 Mahasiswa AKTIF (2024001, 2024002, 2024003)');
        $this->command->info('   - 2 Mahasiswa TIDAK AKTIF (2024004: cuti, 2024005: non_aktif)');
        $this->command->info('');
        $this->command->info('🎯 Testing Scenario:');
        $this->command->info('   ✅ 2024001-2024003: Dapat pinjam buku');
        $this->command->info('   ❌ 2024004-2024005: TIDAK dapat pinjam buku (status tidak aktif)');
    }
}

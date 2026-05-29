<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get fakultas
        $fst = Fakultas::where('kode', 'FST')->first();
        $fd = Fakultas::where('kode', 'FD')->first();

        $prodiData = [
            // Fakultas Sains dan Teknologi
            [
                'fakultas_id' => $fst->id,
                'kode' => 'IF',
                'nama' => 'Informatika',
            ],
            [
                'fakultas_id' => $fst->id,
                'kode' => 'ARS',
                'nama' => 'Arsitektur',
            ],
            [
                'fakultas_id' => $fst->id,
                'kode' => 'IL',
                'nama' => 'Ilmu Lingkungan',
            ],
            [
                'fakultas_id' => $fst->id,
                'kode' => 'PST',
                'nama' => 'Perpustakaan Sains Teknologi',
            ],
            // Fakultas Dakwah
            [
                'fakultas_id' => $fd->id,
                'kode' => 'BKI',
                'nama' => 'Bimbingan Konseling Islam',
            ],
            [
                'fakultas_id' => $fd->id,
                'kode' => 'KPI',
                'nama' => 'Komunikasi Penyiaran Islam',
            ],
            [
                'fakultas_id' => $fd->id,
                'kode' => 'MD',
                'nama' => 'Manajemen Dakwah',
            ],
            [
                'fakultas_id' => $fd->id,
                'kode' => 'PMI',
                'nama' => 'Pengembangan Masyarakat Islam',
            ],
        ];

        foreach ($prodiData as $data) {
            ProgramStudi::create($data);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Fakultas (parent units)
        $fst = Unit::create([
            'parent_id' => null,
            'kode' => 'FST',
            'nama' => 'Fakultas Sains dan Teknologi',
            'tipe' => 'fakultas',
        ]);

        $fd = Unit::create([
            'parent_id' => null,
            'kode' => 'FD',
            'nama' => 'Fakultas Dakwah',
            'tipe' => 'fakultas',
        ]);

        // Create Program Studi for Fakultas Sains dan Teknologi
        Unit::create([
            'parent_id' => $fst->id,
            'kode' => 'IF',
            'nama' => 'Informatika',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fst->id,
            'kode' => 'ARS',
            'nama' => 'Arsitektur',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fst->id,
            'kode' => 'IL',
            'nama' => 'Ilmu Lingkungan',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fst->id,
            'kode' => 'PST',
            'nama' => 'Perpustakaan Sains Teknologi',
            'tipe' => 'prodi',
        ]);

        // Create Program Studi for Fakultas Dakwah
        Unit::create([
            'parent_id' => $fd->id,
            'kode' => 'BKI',
            'nama' => 'Bimbingan Konseling Islam',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fd->id,
            'kode' => 'KPI',
            'nama' => 'Komunikasi Penyiaran Islam',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fd->id,
            'kode' => 'MD',
            'nama' => 'Manajemen Dakwah',
            'tipe' => 'prodi',
        ]);

        Unit::create([
            'parent_id' => $fd->id,
            'kode' => 'PMI',
            'nama' => 'Pengembangan Masyarakat Islam',
            'tipe' => 'prodi',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultasData = [
            [
                'kode' => 'FST',
                'nama' => 'Fakultas Sains dan Teknologi',
            ],
            [
                'kode' => 'FD',
                'nama' => 'Fakultas Dakwah',
            ],
        ];

        foreach ($fakultasData as $data) {
            Fakultas::create($data);
        }
    }
}

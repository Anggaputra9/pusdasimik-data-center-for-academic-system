<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosenData = [
            ['nip' => '198501012010011001', 'nama' => 'Dr. Ir. Bambang Riyanto, M.T.'],
            ['nip' => '198703152012012001', 'nama' => 'Dr. Siti Aminah, S.Kom., M.Kom.'],
            ['nip' => '199001202015011002', 'nama' => 'Ahmad Hidayat, S.T., M.Eng.'],
            ['nip' => '198205102008012003', 'nama' => 'Prof. Dr. Ir. Dewi Kusuma, M.Sc.'],
            ['nip' => '198912252014011003', 'nama' => 'Andi Wijaya, S.Kom., M.T.'],
            ['nip' => '198408172009012004', 'nama' => 'Dr. Rina Marlina, S.Si., M.Si.'],
            ['nip' => '199203082016011004', 'nama' => 'Hendra Gunawan, S.T., M.Kom.'],
            ['nip' => '198606222011012005', 'nama' => 'Dr. Eng. Fitri Handayani, S.T., M.T.'],
        ];

        foreach ($dosenData as $data) {
            Dosen::create($data);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $if = Unit::where('kode', 'IF')->first()->id;
        $ars = Unit::where('kode', 'ARS')->first()->id;
        $il = Unit::where('kode', 'IL')->first()->id;
        $pst = Unit::where('kode', 'PST')->first()->id;
        $bki = Unit::where('kode', 'BKI')->first()->id;
        $kpi = Unit::where('kode', 'KPI')->first()->id;
        $md = Unit::where('kode', 'MD')->first()->id;
        $pmi = Unit::where('kode', 'PMI')->first()->id;

        $ta = '2025/2026 Genap';

        $mahasiswaData = [
            ['nim' => '2021001', 'nama' => 'Budi Santoso', 'unit_id' => $if, 'status' => 'aktif'],
            ['nim' => '2021002', 'nama' => 'Siti Nurhaliza', 'unit_id' => $if, 'status' => 'aktif'],
            ['nim' => '2021003', 'nama' => 'Ahmad Fauzi', 'unit_id' => $if, 'status' => 'cuti'],
            ['nim' => '2021004', 'nama' => 'Dewi Lestari', 'unit_id' => $ars, 'status' => 'aktif'],
            ['nim' => '2021005', 'nama' => 'Rizki Pratama', 'unit_id' => $ars, 'status' => 'aktif'],
            ['nim' => '2021006', 'nama' => 'Putri Ayu', 'unit_id' => $ars, 'status' => 'lulus'],
            ['nim' => '2021007', 'nama' => 'Andi Wijaya', 'unit_id' => $il, 'status' => 'aktif'],
            ['nim' => '2021008', 'nama' => 'Maya Sari', 'unit_id' => $il, 'status' => 'do'],
            ['nim' => '2021009', 'nama' => 'Doni Setiawan', 'unit_id' => $pst, 'status' => 'aktif'],
            ['nim' => '2021010', 'nama' => 'Rina Wati', 'unit_id' => $pst, 'status' => 'aktif'],
            ['nim' => '2021011', 'nama' => 'Hendra Gunawan', 'unit_id' => $bki, 'status' => 'aktif'],
            ['nim' => '2021012', 'nama' => 'Lina Marlina', 'unit_id' => $bki, 'status' => 'cuti'],
            ['nim' => '2021013', 'nama' => 'Bambang Suryanto', 'unit_id' => $bki, 'status' => 'aktif'],
            ['nim' => '2021014', 'nama' => 'Fitri Handayani', 'unit_id' => $kpi, 'status' => 'aktif'],
            ['nim' => '2021015', 'nama' => 'Agus Salim', 'unit_id' => $kpi, 'status' => 'aktif'],
            ['nim' => '2021016', 'nama' => 'Nurul Hidayah', 'unit_id' => $kpi, 'status' => 'lulus'],
            ['nim' => '2021017', 'nama' => 'Yudi Hermawan', 'unit_id' => $md, 'status' => 'aktif'],
            ['nim' => '2021018', 'nama' => 'Sari Rahayu', 'unit_id' => $md, 'status' => 'aktif'],
            ['nim' => '2021019', 'nama' => 'Irfan Hakim', 'unit_id' => $pmi, 'status' => 'aktif'],
            ['nim' => '2021020', 'nama' => 'Diah Permata', 'unit_id' => $pmi, 'status' => 'aktif'],
        ];

        foreach ($mahasiswaData as $data) {
            Mahasiswa::create($data + ['tahun_akademik' => $ta]);
        }
    }
}

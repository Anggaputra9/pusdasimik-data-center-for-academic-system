<?php

namespace Database\Seeders;

use App\Models\ApiClient;
use Illuminate\Database\Seeder;

/**
 * Dijalankan terpisah dari DatabaseSeeder agar token yang sudah diterbitkan
 * tidak ikut hilang saat `migrate:fresh --seed`.
 *
 * Cara pakai:
 *     php artisan db:seed --class=ApiClientSeeder
 */
class ApiClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'slug' => 'website-presensi',
                'nama' => 'Website Presensi',
                'deskripsi' => 'Aplikasi presensi mahasiswa — perlu data NIM, nama, prodi, dan status keaktifan.',
            ],
            [
                'slug' => 'website-perpustakaan',
                'nama' => 'Website Perpustakaan',
                'deskripsi' => 'Sistem peminjaman buku — verifikasi keanggotaan via status mahasiswa.',
            ],
            [
                'slug' => 'website-pengajuan-judul',
                'nama' => 'Website Pengajuan Judul',
                'deskripsi' => 'Pengajuan judul skripsi/TA — wajib cek status mahasiswa (aktif/cuti/lulus/DO) sebelum menerima pengajuan.',
            ],
        ];

        foreach ($clients as $c) {
            ApiClient::firstOrCreate(['slug' => $c['slug']], $c + ['is_active' => true]);
        }
    }
}

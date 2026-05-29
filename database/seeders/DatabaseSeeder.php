<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Units data (2 fakultas + 8 prodi = 10 records)
        $this->call(UnitsSeeder::class);
        
        // Seed Mahasiswa data (20 records)
        $this->call(MahasiswaSeeder::class);
        
        // Seed Dosen data (8 records)
        $this->call(DosenSeeder::class);

        // Catatan: ApiClientSeeder TIDAK dipanggil di sini.
        // Klien API dikelola terpisah supaya token yang sudah diterbitkan
        // tidak hangus saat `migrate:fresh --seed`.
        // Jalankan manual jika perlu:  php artisan db:seed --class=ApiClientSeeder

        // Create test user (optional)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

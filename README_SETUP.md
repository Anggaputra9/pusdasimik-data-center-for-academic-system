# Pusat Data - API Provider Setup Documentation

## 📋 Project Overview
Pusat Data adalah server API yang menyediakan Single Source of Truth untuk data mahasiswa dalam ekosistem sistem informasi akademik.

## ✅ Yang Sudah Dikonfigurasi

### 1. Database Configuration
- **Database Name**: `pusat_data`
- **Connection**: MySQL
- **Credentials**: root (no password)
- **File**: `.env` sudah dikonfigurasi

### 2. Mahasiswa Model & Migration
**Model**: `app/Models/Mahasiswa.php`
- Primary Key: `nim` (string)
- Fillable fields: nim, nama, program_studi, fakultas

**Migration**: `database/migrations/2026_05_28_080844_create_mahasiswas_table.php`
- nim (string, 20 chars, primary key)
- nama (string, 100 chars)
- program_studi (string, 100 chars)
- fakultas (string, 100 chars)
- timestamps

### 3. API Controller
**File**: `app/Http/Controllers/Api/MahasiswaController.php`

**Endpoints yang tersedia**:
- `GET /api/mahasiswa` - List semua mahasiswa
- `POST /api/mahasiswa` - Tambah mahasiswa baru
- `GET /api/mahasiswa/{nim}` - Detail mahasiswa berdasarkan NIM
- `PUT /api/mahasiswa/{nim}` - Update data mahasiswa
- `DELETE /api/mahasiswa/{nim}` - Hapus mahasiswa

**Response Format**:
```json
{
    "success": true,
    "data": {
        "nim": "2021001",
        "nama": "Budi Santoso",
        "program_studi": "Teknik Informatika",
        "fakultas": "Fakultas Teknik"
    }
}
```

**Error Response (404)**:
```json
{
    "success": false,
    "message": "Data mahasiswa tidak ditemukan"
}
```

### 4. API Routes
**File**: `routes/api.php`
- Menggunakan `Route::apiResource('mahasiswa', MahasiswaController::class)`
- Semua routes otomatis ter-prefix dengan `/api`

### 5. Sample Data Seeder
**File**: `database/seeders/MahasiswaSeeder.php`
- Berisi 10 sample mahasiswa
- Berbagai fakultas dan program studi

### 6. Laravel Sanctum
- Sedang dalam proses instalasi
- Akan digunakan untuk autentikasi token API

## 🚀 Langkah Selanjutnya

### 1. Start MySQL Server
Buka XAMPP Control Panel dan start MySQL/MariaDB

### 2. Create Database
```bash
E:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS pusat_data CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Atau buat manual via phpMyAdmin:
- Buka http://localhost/phpmyadmin
- Create database: `pusat_data`
- Collation: `utf8mb4_unicode_ci`

### 3. Run Migrations
```bash
cd e:\kuliah\semester6\laravel\pusat-data
php artisan migrate
```

### 4. Run Seeder
```bash
php artisan db:seed --class=MahasiswaSeeder
```

### 5. Test API
Start development server:
```bash
php artisan serve
```

Test endpoint:
```bash
# Get all mahasiswa
curl http://localhost:8000/api/mahasiswa

# Get specific mahasiswa
curl http://localhost:8000/api/mahasiswa/2021001
```

## 🔐 API Authentication (Sanctum)

### Publish Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Run Sanctum Migrations
```bash
php artisan migrate
```

### Generate API Token
Buat controller untuk generate token:
```php
use Laravel\Sanctum\HasApiTokens;

// Di User model, tambahkan trait:
use HasApiTokens;

// Generate token:
$token = $user->createToken('api-token')->plainTextToken;
```

### Protect Routes
```php
// Di routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('mahasiswa', MahasiswaController::class);
});
```

## 📱 Admin Interface (To Be Created)

### Install Tailwind CSS
```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

### Create Admin Views
- Dashboard untuk manage mahasiswa
- Form tambah/edit mahasiswa
- Table list mahasiswa dengan search & pagination

## 🔗 Integration dengan Client Systems

### Sistem Presensi
```php
// Client akan hit endpoint:
GET /api/mahasiswa/{nim}

// Response digunakan untuk validasi kehadiran
```

### Sistem Perpustakaan
```php
// Client akan hit endpoint:
GET /api/mahasiswa/{nim}

// Response digunakan untuk data peminjam
```

### Sistem Skripsi
```php
// Client akan hit endpoint:
GET /api/mahasiswa/{nim}

// Response digunakan untuk validasi pengajuan judul
```

## 📝 Sample Data
Seeder sudah berisi 10 mahasiswa:
- 2021001 - Budi Santoso (Teknik Informatika)
- 2021002 - Siti Nurhaliza (Sistem Informasi)
- 2021003 - Ahmad Fauzi (Teknik Elektro)
- 2021004 - Dewi Lestari (Manajemen)
- 2021005 - Rizki Pratama (Akuntansi)
- 2021006 - Putri Ayu (Psikologi)
- 2021007 - Andi Wijaya (Hukum)
- 2021008 - Maya Sari (Kedokteran)
- 2021009 - Doni Setiawan (Arsitektur)
- 2021010 - Rina Wati (Sastra Inggris)

## 🛠️ Troubleshooting

### Error: SQLSTATE[HY000] [1049] Unknown database
- MySQL belum running atau database belum dibuat
- Jalankan langkah 1 dan 2 di atas

### Error: Class 'Laravel\Sanctum\...' not found
- Tunggu hingga `php artisan install:api` selesai
- Atau jalankan: `composer require laravel/sanctum`

### Error: Failed opening required vendor/...
- Composer sedang update packages
- Tunggu hingga proses selesai

## 📚 Resources
- Laravel Documentation: https://laravel.com/docs
- Laravel Sanctum: https://laravel.com/docs/sanctum
- REST API Best Practices: https://restfulapi.net/

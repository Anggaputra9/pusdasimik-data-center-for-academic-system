# 🚀 Panduan Menggunakan DevTunnels untuk Sistem Terintegrasi

## ✅ Jawaban Singkat
**YA, URL DevTunnels BISA digunakan untuk konfigurasi API key/endpoint!**

DevTunnels memungkinkan sistem lain (perpustakaan, presensi, pengajuan-judul) mengakses Laravel API Anda yang berjalan di localhost melalui URL publik.

---

## 📖 Apa itu DevTunnels?

DevTunnels adalah layanan dari Microsoft yang membuat tunnel/terowongan dari komputer lokal Anda ke internet. Mirip seperti ngrok atau localtunnel.

**Kegunaan:**
- Testing API integration tanpa deploy ke server
- Sistem lain bisa kirim data ke localhost Anda
- Cocok untuk development dan testing
- GRATIS dari Microsoft!

---

## 🔧 Langkah-langkah Setup

### 1️⃣ Install dan Setup DevTunnels

```bash
# Install DevTunnels CLI (jika belum)
# Download dari: https://aka.ms/devtunnels/download

# Login ke Microsoft account
devtunnel user login

# Buat tunnel baru (hanya sekali)
devtunnel create --allow-anonymous

# Output contoh:
# Created tunnel: abc123xyz with id: abc-123-xyz-456
```

### 2️⃣ Jalankan Laravel Server

```bash
# Jalankan Laravel di localhost
php artisan serve

# Laravel akan jalan di http://127.0.0.1:8000
```

### 3️⃣ Hubungkan DevTunnels ke Laravel

```bash
# Buka tunnel ke port 8000 (port Laravel)
devtunnel port create 8000 --protocol https

# Jalankan tunnel
devtunnel host

# Output akan menampilkan URL publik:
# Connect via browser: https://abc123xyz-8000.devtunnels.ms
```

**🎉 Sekarang Laravel Anda bisa diakses dari internet!**

---

## ⚙️ Konfigurasi Laravel

### 1. Update File `.env`

Buka file `.env` Anda dan update:

```env
# Ganti dengan URL DevTunnels Anda
APP_URL=https://abc123xyz-8000.devtunnels.ms

# Tambahkan domain ke Sanctum (tanpa https://)
SANCTUM_STATEFUL_DOMAINS=abc123xyz-8000.devtunnels.ms,localhost,127.0.0.1

# Session domain
SESSION_DOMAIN=null
```

### 2. Clear Cache Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 3. Test URL DevTunnels

Buka browser dan akses:
```
https://abc123xyz-8000.devtunnels.ms/api/me
```

Jika muncul "Unauthenticated", berarti API sudah jalan! ✅

---

## 🔑 Cara Memberikan Akses ke Sistem Lain

### Step 1: Generate API Token (Sanctum)

Sistem lain butuh token untuk akses API Anda:

```bash
php artisan tinker
```

Kemudian jalankan:
```php
// Buat atau ambil API client
$client = App\Models\ApiClient::where('slug', 'sistem-perpustakaan')->first();

// Generate token
$token = $client->createToken('perpus-access')->plainTextToken;

// Copy token ini
echo $token;
```

### Step 2: Berikan Informasi ke Tim Sistem Lain

Berikan informasi berikut ke developer sistem perpustakaan/presensi/pengajuan-judul:

```
📌 INFORMASI API PUSAT DATA

Base URL: https://abc123xyz-8000.devtunnels.ms

Endpoints yang tersedia:
┌─────────────────────────────────────────────────────────────┐
│ POST /api/peminjaman/kirim         (Sistem Perpustakaan)    │
│ POST /api/presensi/kirim           (Sistem Presensi)        │
│ POST /api/pengajuan-judul/kirim    (Sistem Pengajuan Judul) │
└─────────────────────────────────────────────────────────────┘

Authentication: Bearer Token
Token: {token_yang_sudah_digenerate}

Headers yang wajib:
  Authorization: Bearer {token}
  Content-Type: application/json
  Accept: application/json
```

---

## 🧪 Contoh Testing dari Sistem Lain

### Dari Sistem Perpustakaan

```bash
curl -X POST https://abc123xyz-8000.devtunnels.ms/api/peminjaman/kirim \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "id_peminjaman": 123,
    "nim_peminjam": "2021001",
    "kode_buku": "BK001",
    "tanggal_pinjam": "2026-06-19",
    "status": "Dipinjam"
  }'
```

### Dari Sistem Presensi

```bash
curl -X POST https://abc123xyz-8000.devtunnels.ms/api/presensi/kirim \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nim_mahasiswa": "2021001",
    "kode_kelas": "SI-3A",
    "nama_mata_kuliah": "Pemrograman Web",
    "status_kehadiran": "hadir",
    "waktu": "2026-06-19 08:00:00"
  }'
```

### Dari Sistem Pengajuan Judul

```bash
curl -X POST https://abc123xyz-8000.devtunnels.ms/api/pengajuan-judul/kirim \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nim": "2021001",
    "judul": "Sistem Informasi Akademik",
    "dosen_pembimbing": "Dr. Ahmad",
    "status": "pending"
  }'
```

---

## 🎯 Konfigurasi di Sistem Lain (Laravel)

Jika sistem lain juga menggunakan Laravel, tambahkan di `.env` mereka:

```env
# Di sistem-perpustakaan/.env
PUSAT_DATA_URL=https://abc123xyz-8000.devtunnels.ms
PUSAT_DATA_TOKEN=your_sanctum_token_here
```

Kemudian di config mereka:

```php
// config/services.php
return [
    'pusat_data' => [
        'url' => env('PUSAT_DATA_URL'),
        'token' => env('PUSAT_DATA_TOKEN'),
    ],
];
```

Cara panggilnya:

```php
use Illuminate\Support\Facades\Http;

$response = Http::withToken(config('services.pusat_data.token'))
    ->post(config('services.pusat_data.url') . '/api/peminjaman/kirim', [
        'id_peminjaman' => 123,
        'nim_peminjam' => '2021001',
        'kode_buku' => 'BK001',
        'tanggal_pinjam' => now()->format('Y-m-d'),
        'status' => 'Dipinjam'
    ]);

if ($response->successful()) {
    // Data berhasil dikirim
    $result = $response->json();
}
```

---

## ⚠️ Hal Penting yang Perlu Diperhatikan

### 1. DevTunnels untuk Development Only
- ❌ **JANGAN** gunakan DevTunnels untuk production
- ✅ Hanya untuk testing dan development
- ✅ Untuk production, deploy ke server real (VPS, cloud, dll)

### 2. URL DevTunnels Bisa Berubah
- Setiap kali restart `devtunnel host`, URL bisa berubah
- Solusi: Gunakan tunnel dengan nama tetap (persisted tunnel)
- Atau buat tunnel dengan `--expiration` yang lebih lama

### 3. Keamanan
- ✅ Pastikan selalu menggunakan HTTPS (DevTunnels default HTTPS)
- ✅ Jangan share token Sanctum di public
- ✅ Monitor log untuk activity mencurigakan
- ✅ Batasi IP jika perlu (di middleware Laravel)

### 4. Performance
- DevTunnels menambah latency (karena ada tunnel)
- Normal untuk development, tapi tidak ideal untuk production

---

## 🔄 Membuat Tunnel Permanen

Untuk URL yang tidak berubah-ubah:

```bash
# Buat tunnel dengan nama
devtunnel create pusat-data --allow-anonymous

# List tunnel yang ada
devtunnel list

# Host specific tunnel
devtunnel host pusat-data --port 8000 --protocol https
```

---

## 🐛 Troubleshooting

### Problem: "Failed to authenticate"
**Solusi:**
1. Pastikan token valid: `php artisan tinker` → `PersonalAccessToken::all()`
2. Cek header Authorization: `Bearer {token}` (ada spasi setelah Bearer)
3. Cek ApiClient status: `is_active = 1`

### Problem: "CORS Error"
**Solusi:** Update `config/cors.php`:
```php
'allowed_origins' => ['*'], // atau list domain spesifik
'supports_credentials' => true,
```

### Problem: "Sanctum tidak recognize domain"
**Solusi:**
1. Update `SANCTUM_STATEFUL_DOMAINS` di `.env`
2. Clear config: `php artisan config:clear`
3. Restart Laravel server

### Problem: DevTunnels disconnect terus
**Solusi:**
- Cek koneksi internet
- Gunakan `devtunnel host --verbose` untuk log detail
- Restart devtunnel

---

## 📊 Monitoring & Logging

Untuk monitoring request dari sistem lain:

```bash
# Monitor Laravel log real-time
tail -f storage/logs/laravel.log

# Atau di Windows
Get-Content storage\logs\laravel.log -Wait -Tail 50
```

Tambahkan logging di Controller:

```php
use Illuminate\Support\Facades\Log;

public function terimaDataPeminjaman(Request $request)
{
    Log::info('Data diterima dari perpustakaan', [
        'client' => $request->user('sanctum')->slug,
        'data' => $request->all(),
        'ip' => $request->ip()
    ]);
    
    // ... rest of code
}
```

---

## ✨ Tips & Best Practices

1. **Gunakan Environment Variables**
   - Jangan hardcode URL di code
   - Simpan di `.env` dan `config/services.php`

2. **Versioning API**
   - Pertimbangkan: `/api/v1/peminjaman/kirim`
   - Memudahkan update tanpa break sistem lain

3. **Response Konsisten**
   - Selalu return JSON dengan struktur sama
   - Include `success`, `message`, `data`

4. **Rate Limiting**
   - Tambahkan throttle untuk mencegah spam
   - Default Laravel: 60 requests/minute

5. **Documentation**
   - Buat dokumentasi API (Postman Collection atau OpenAPI)
   - Share ke semua tim pengembang sistem lain

---

## 📚 Resource Tambahan

- DevTunnels Docs: https://learn.microsoft.com/azure/developer/dev-tunnels/
- Laravel Sanctum: https://laravel.com/docs/11.x/sanctum
- API Best Practices: https://restfulapi.net/

---

## 🤝 Workflow Development Terintegrasi

```
┌─────────────────┐         ┌─────────────────┐
│ Sistem          │         │ Sistem          │
│ Perpustakaan    │────┐    │ Presensi        │────┐
│ (localhost:3000)│    │    │ (localhost:4000)│    │
└─────────────────┘    │    └─────────────────┘    │
                       │                             │
                       │    ┌─────────────────┐     │
                       │    │ Sistem          │     │
                       └────│ Pengajuan Judul │─────┘
                            │ (localhost:5000)│     │
                            └─────────────────┘     │
                                                     │
                                                     ↓
                                    ┌──────────────────────────────┐
                                    │ 🌐 DEVTUNNELS               │
                                    │ https://abc-8000.dev...ms   │
                                    └──────────────────────────────┘
                                                     ↓
                                    ┌──────────────────────────────┐
                                    │ 🏛️ PUSAT DATA (Laravel)     │
                                    │ http://localhost:8000        │
                                    │ - API Endpoints              │
                                    │ - Sanctum Auth               │
                                    │ - Database SQLite            │
                                    └──────────────────────────────┘
```

---

## 🎓 Kesimpulan

**DevTunnels adalah solusi SEMPURNA untuk:**
✅ Testing integrasi sistem saat development  
✅ Demo ke dosen/client tanpa deploy  
✅ Debugging API dari sistem lain  
✅ Kolaborasi tim yang berbeda lokasi  

**Cara pakainya:**
1. Jalankan Laravel: `php artisan serve`
2. Jalankan DevTunnels: `devtunnel host --port 8000 --protocol https`
3. Update `.env` dengan URL DevTunnels
4. Share URL + Token ke sistem lain
5. Testing dan development! 🚀

**Untuk production nanti:**
- Deploy ke VPS/Cloud (DigitalOcean, AWS, dll)
- Gunakan domain real (pusatdata.universitasanda.ac.id)
- Setup SSL certificate
- Update URL di sistem-sistem lain

---

Semoga membantu! Happy coding! 🎉

# ⚡ DevTunnels Quick Start

## 🎯 Jawaban Cepat
**YA! URL DevTunnels bisa untuk konfigurasi API.** Ikuti 3 langkah di bawah:

---

## 🚀 3 Langkah Setup

### 1. Jalankan Laravel
```bash
php artisan serve
# Output: Server started on http://127.0.0.1:8000
```

### 2. Jalankan DevTunnels
```bash
# First time setup
devtunnel user login
devtunnel create pusat-data --allow-anonymous

# Jalankan tunnel (setiap kali development)
devtunnel host --port 8000 --protocol https
# Output: https://abc123xyz-8000.devtunnels.ms
```

### 3. Update Laravel `.env`
```env
APP_URL=https://abc123xyz-8000.devtunnels.ms
SANCTUM_STATEFUL_DOMAINS=abc123xyz-8000.devtunnels.ms,localhost,127.0.0.1
SESSION_DOMAIN=null
```

```bash
php artisan config:clear
```

✅ **SELESAI!** URL siap dibagikan ke sistem lain.

---

## 📤 Info untuk Sistem Lain

```
Base URL: https://abc123xyz-8000.devtunnels.ms

Endpoints:
  POST /api/peminjaman/kirim
  POST /api/presensi/kirim
  POST /api/pengajuan-judul/kirim

Headers:
  Authorization: Bearer {token}
  Content-Type: application/json
  Accept: application/json
```

### Generate Token
```bash
php artisan tinker
```
```php
$client = App\Models\ApiClient::where('slug', 'sistem-perpustakaan')->first();
$token = $client->createToken('access')->plainTextToken;
echo $token;
```

---

## 🧪 Test Cepat

```bash
# Test dari sistem lain
curl -X POST https://YOUR-URL.devtunnels.ms/api/peminjaman/kirim \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"id_peminjaman":123,"nim_peminjam":"2021001","kode_buku":"BK001","tanggal_pinjam":"2026-06-19","status":"Dipinjam"}'
```

---

## ⚙️ Konfigurasi di Sistem Lain (Laravel)

**File: `.env`**
```env
PUSAT_DATA_URL=https://abc123xyz-8000.devtunnels.ms
PUSAT_DATA_TOKEN=your_token_here
```

**File: `config/services.php`**
```php
'pusat_data' => [
    'url' => env('PUSAT_DATA_URL'),
    'token' => env('PUSAT_DATA_TOKEN'),
],
```

**Cara panggil:**
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
```

---

## ⚠️ Penting!

- ✅ DevTunnels untuk **development/testing** saja
- ❌ JANGAN untuk production
- 🔄 URL bisa berubah setiap restart
- 🔒 Jangan share token di public

---

## 🐛 Troubleshooting

**Error: "Unauthenticated"**
→ Token salah atau expired

**Error: "CORS"**
→ Update `SANCTUM_STATEFUL_DOMAINS` di `.env`

**Error: "Connection refused"**
→ Laravel belum jalan atau port salah

**DevTunnels disconnect**
→ Restart: `devtunnel host --port 8000 --protocol https`

---

📖 **Panduan lengkap:** Baca `PANDUAN_DEVTUNNELS.md`

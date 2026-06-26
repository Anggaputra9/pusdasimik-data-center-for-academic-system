<?php

use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Middleware\EnsureApiClientActive;
use App\Http\Controllers\Api\PemasokDataController;
use App\Http\Controllers\Api\PengajuanJudulController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC API ENDPOINTS (Tanpa Auth)
// ============================================
// Endpoint ini bisa diakses tanpa token untuk keperluan integrasi sistem eksternal
Route::get('/public/mahasiswa/{nim}', [MahasiswaController::class, 'show']);
Route::get('/public/mahasiswa/{nim}/permissions', [MahasiswaController::class, 'checkPermissions']);
Route::get('/public/dosen/{nip}', [DosenController::class, 'show']);

// ============================================
// PROTECTED API ENDPOINTS (Butuh Auth)
// ============================================
// Semua endpoint API butuh token Sanctum valid + klien aktif.
Route::middleware(['auth:sanctum', EnsureApiClientActive::class])->group(function () {
    Route::get('/me', function (Request $request) {
        $client = $request->user('sanctum');

        return [
            'id' => $client->id,
            'slug' => $client->slug,
            'nama' => $client->nama,
            'is_active' => $client->is_active,
        ];
    });

    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::get('/mahasiswa/{nim}/permissions', [MahasiswaController::class, 'checkPermissions']);
    Route::apiResource('dosen', DosenController::class);

    Route::get('/units/fakultas', [UnitController::class, 'getFakultas']);
    Route::get('/units/prodi/{fakultasId}', [UnitController::class, 'getProdiByFakultas']);
    Route::apiResource('units', UnitController::class);

    Route::post('/peminjaman/kirim', [PemasokDataController::class, 'terimaDataPeminjaman']);
    Route::post('/presensi/kirim', [PemasokDataController::class, 'terimaDataPresensi']);
    Route::get('/presensi/ambil', [PemasokDataController::class, 'ambilDataPresensi']);
    Route::get('/presensi/status', [PemasokDataController::class, 'statusSinkronisasi']);

    Route::post('/terima-data', [PemasokDataController::class, 'terimaDataPeminjaman']);

    // Endpoint utama untuk menerima data pengajuan judul dari sistem-pengajuan-judul
    Route::post('/pengajuan-judul/kirim', [PengajuanJudulController::class, 'terimaPengajuanJudul']);

    // Alias agar kompatibel jika sistem-pengajuan-judul mengirim ke /api/log-pengajuan-judul
    Route::post('/log-pengajuan-judul', [PengajuanJudulController::class, 'terimaPengajuanJudul']);
});
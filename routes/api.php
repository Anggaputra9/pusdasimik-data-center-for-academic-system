<?php

use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Middleware\EnsureApiClientActive;
use App\Http\Controllers\Api\PemasokDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::apiResource('dosen', DosenController::class);

    Route::get('/units/fakultas', [UnitController::class, 'getFakultas']);
    Route::get('/units/prodi/{fakultasId}', [UnitController::class, 'getProdiByFakultas']);
    Route::apiResource('units', UnitController::class);

    
    Route::post('/peminjaman/kirim', [PemasokDataController::class, 'terimaDataPeminjaman']);
    
    Route::post('/terima-data', [PemasokDataController::class, 'terimaDataPeminjaman']);
});
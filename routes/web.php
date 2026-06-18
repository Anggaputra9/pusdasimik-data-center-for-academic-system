<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ApiClientController;
use App\Http\Controllers\Admin\PresensiController;

// Redirect root to admin dashboard gais
Route::get('/', function () {
    return redirect()->route('admin.mahasiswa.index');
});

// Admin routes group
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('unit', UnitController::class)->except(['show', 'create', 'edit']);

    Route::resource('api-client', ApiClientController::class)->except(['show', 'create', 'edit']);
    Route::post('api-client/{apiClient}/tokens', [ApiClientController::class, 'issueToken'])->name('api-client.tokens.store');
    Route::delete('api-client/{apiClient}/tokens/{tokenId}', [ApiClientController::class, 'revokeToken'])->name('api-client.tokens.destroy');

    // ✅ Hanya nama route diubah dari 'log-perpus' menjadi 'perpus.log' agar cocok dengan sidebar
    Route::get('/log-perpus', [\App\Http\Controllers\Api\PemasokDataController::class, 'tampilkanLogPerpus'])->name('perpus.log');
    
    // Log Presensi routes
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/export', [PresensiController::class, 'export'])->name('presensi.export');
    Route::get('/presensi/{id}', [PresensiController::class, 'show'])->name('presensi.show');
});

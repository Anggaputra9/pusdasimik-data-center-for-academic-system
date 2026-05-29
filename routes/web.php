<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ApiClientController;

// Redirect root to admin dashboard
Route::get('/', function () {
    return redirect()->route('admin.mahasiswa.index');
});

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::resource('dosen', DosenController::class);
    Route::resource('unit', UnitController::class)->except(['show', 'create', 'edit']);

    Route::resource('api-client', ApiClientController::class)->except(['show', 'create', 'edit']);
    Route::post('api-client/{apiClient}/tokens', [ApiClientController::class, 'issueToken'])->name('api-client.tokens.store');
    Route::delete('api-client/{apiClient}/tokens/{tokenId}', [ApiClientController::class, 'revokeToken'])->name('api-client.tokens.destroy');
});

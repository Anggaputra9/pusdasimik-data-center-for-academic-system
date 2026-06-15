<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menggunakan DB Facade agar super aman gais
use Illuminate\Support\Facades\Validator;

class PemasokDataController extends Controller
{
    public function terimaDataPeminjaman(Request $request)
    {
        // 1. Validasi data kiriman dari laptop kamu gais
        $validator = Validator::make($request->all(), [
            'nim_peminjam'   => 'required',
            'kode_buku'      => 'required',
            'tanggal_pinjam' => 'required|date',
            'status'         => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Format data dari perpus salah gais!',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // 2. MODUS CERDAS: Angga tidak perlu membuat Model baru gais! 
            // Kita langsung tembak buatkan tabel darurat bernama 'log_peminjaman_perpus' di databasenya Angga.
            // Jika tabel belum ada di database Angga, kita buat otomatis lewat kode di bawah ini gais:
            if (!\Schema::hasTable('log_peminjaman_perpus')) {
                \Schema::create('log_peminjaman_perpus', function ($table) {
                    $table->id();
                    $table->string('nim_peminjam');
                    $table->string('kode_buku');
                    $table->date('tanggal_pinjam');
                    $table->string('status');
                    $table->timestamps();
                });
            }

            // 3. Masukkan datanya langsung ke database Angga gais
            DB::table('log_peminjaman_perpus')->insert([
                'nim_peminjam'   => $request->nim_peminjam,
                'kode_buku'      => $request->kode_buku,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'status'         => $request->status,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '🚀 Keren Ngga! Data transaksi dari perpus berhasil masuk ke database pusat!'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan di pusat data: ' . $e->getMessage()
            ], 500);
        }
    }
}
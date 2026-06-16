<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Menggunakan DB Facade agar super aman gais
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class PemasokDataController extends Controller
{
    /**
     * FUNGSI 1: Menerima setoran data transaksi via API dari sistem-perpustakaan gais
     */
    public function terimaDataPeminjaman(Request $request)
    {
        // 1. Validasi data kiriman murni tanpa kompromi gais
        $validator = Validator::make($request->all(), [
            'id_peminjaman'  => 'required', 
            'nim_peminjam'   => 'required',
            'kode_buku'      => 'required',
            'tanggal_pinjam' => 'required',
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
            // 2. Memastikan tabel terbuat dengan struktur yang bersih gais
            if (!Schema::hasTable('log_peminjaman_perpus')) {
                Schema::create('log_peminjaman_perpus', function ($table) {
                    $table->id();
                    $table->unsignedBigInteger('id_peminjaman')->nullable(); 
                    $table->string('nim_peminjam');
                    $table->string('kode_buku');
                    $table->date('tanggal_pinjam');
                    $table->string('status');
                    $table->timestamps(); // Ini yang membuat created_at dan updated_at jir
                });
            } else {
                if (!Schema::hasColumn('log_peminjaman_perpus', 'id_peminjaman')) {
                    Schema::table('log_peminjaman_perpus', function ($table) {
                        $table->unsignedBigInteger('id_peminjaman')->nullable()->after('id');
                    });
                }
            }

            // Clean format tanggalnya gais
            $tanggalClean = date('Y-m-d', strtotime($request->tanggal_pinjam));

            // 🔍 3. 🔥 LOGIKA TOTAL SOLUSI: Pisah Cek & Eksekusi Secara Presisi Jir!
            // Cek dulu apakah id_peminjaman dari perpustakaan ini sudah pernah mendarat di pusat data atau belum
            $cekDataLama = DB::table('log_peminjaman_perpus')
                ->where('id_peminjaman', $request->id_peminjaman)
                ->first();

            if ($cekDataLama) {
                // A. KONDISI UPDATE STATUS (Dipinjam -> Dikembalikan) gais!
                // Hanya update kolom status dan updated_at. Jangan sentuh created_at biar database tidak ngambek jir!
                DB::table('log_peminjaman_perpus')
                    ->where('id_peminjaman', $request->id_peminjaman)
                    ->update([
                        'nim_peminjam'   => $request->nim_peminjam,
                        'kode_buku'      => $request->kode_buku,
                        'tanggal_pinjam' => $tanggalClean,
                        'status'         => $request->status, // Berubah Live Jadi 'Dikembalikan' 🚀
                        'updated_at'     => now()
                    ]);

                $pesanAction = '🚀 Sinkronisasi status transaksi berhasil DI-UPDATE menjadi ' . $request->status . ' di Pusat Data gais!';
            } else {
                // B. KONDISI INSERT DATA BARU gais!
                // Masukkan data murni baru gres beserta created_at penanda waktu mendarat pertama kali
                DB::table('log_peminjaman_perpus')->insert([
                    'id_peminjaman'  => $request->id_peminjaman,
                    'nim_peminjam'   => $request->nim_peminjam,
                    'kode_buku'      => $request->kode_buku,
                    'tanggal_pinjam' => $tanggalClean,
                    'status'         => $request->status, // Status awal 'Dipinjam'
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);

                $pesanAction = '✨ Data transaksi baru berhasil DICATAT & DISINKRONISASI ke Pusat Data gais!';
            }

            return response()->json([
                'success' => true,
                'message' => $pesanAction
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan di pusat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FUNGSI 2: Menarik data log dan melemparkannya ke tampilan web gais
     */
    public function tampilkanLogPerpus()
    {
        if (Schema::hasTable('log_peminjaman_perpus')) {
            // Kita urutkan berdasarkan id paling besar agar data transaksi terbaru muncul paling atas gais
            $logs = DB::table('log_peminjaman_perpus')->orderBy('id', 'desc')->get();
        } else {
            $logs = collect([]); 
        }

        return view('admin.perpus.log_perpus', compact('logs'));
    }
}
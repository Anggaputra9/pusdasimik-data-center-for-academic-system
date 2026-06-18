<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class PengajuanJudulController extends Controller
{
    /**
     * FUNGSI 1: Menerima setoran data pengajuan judul dari sistem-pengajuan-judul
     */
    public function terimaPengajuanJudul(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pengajuan'   => 'required',
            'nim'            => 'required',
            'nama_mahasiswa' => 'required',
            'judul'          => 'required',
            'status'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Format data dari sistem pengajuan judul salah!',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            if (!Schema::hasTable('log_pengajuan_judul')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tabel log_pengajuan_judul belum tersedia di Pusat Data.'
                ], 500);
            }

            $cekDataLama = DB::table('log_pengajuan_judul')
                ->where('id_pengajuan', $request->id_pengajuan)
                ->first();

            if ($cekDataLama) {
                // Update data yang sudah ada (misal status berubah)
                DB::table('log_pengajuan_judul')
                    ->where('id_pengajuan', $request->id_pengajuan)
                    ->update([
                        'nim'            => $request->nim,
                        'nama_mahasiswa' => $request->nama_mahasiswa,
                        'prodi'          => $request->prodi,
                        'fakultas'       => $request->fakultas,
                        'judul'          => $request->judul,
                        'deskripsi'      => $request->deskripsi,
                        'status'         => $request->status,
                        'updated_at'     => now()
                    ]);

                $pesanAction = '🚀 Status pengajuan judul berhasil DI-UPDATE menjadi ' . $request->status . ' di Pusat Data!';
            } else {
                // Insert data baru
                DB::table('log_pengajuan_judul')->insert([
                    'id_pengajuan'   => $request->id_pengajuan,
                    'nim'            => $request->nim,
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'prodi'          => $request->prodi,
                    'fakultas'       => $request->fakultas,
                    'judul'          => $request->judul,
                    'deskripsi'      => $request->deskripsi,
                    'status'         => $request->status,
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);

                $pesanAction = '✨ Data pengajuan judul baru berhasil DICATAT & DISINKRONISASI ke Pusat Data!';
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
     * FUNGSI 2: Menampilkan log pengajuan judul ke halaman admin
     */
    public function tampilkanLogPengajuanJudul()
    {
        if (Schema::hasTable('log_pengajuan_judul')) {
            $logs = DB::table('log_pengajuan_judul')->orderBy('id', 'desc')->get();
        } else {
            $logs = collect([]);
        }

        return view('admin.pengajuan_judul.log_pengajuan_judul', compact('logs'));
    }
}
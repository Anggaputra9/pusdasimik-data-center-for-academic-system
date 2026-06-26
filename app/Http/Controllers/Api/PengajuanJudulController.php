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

            $idColumn = null;

            if (Schema::hasColumn('log_pengajuan_judul', 'id_pengajuan')) {
                $idColumn = 'id_pengajuan';
            } elseif (Schema::hasColumn('log_pengajuan_judul', 'external_submission_id')) {
                $idColumn = 'external_submission_id';
            }

            if (!$idColumn) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom id_pengajuan atau external_submission_id belum tersedia di tabel log_pengajuan_judul.'
                ], 500);
            }

            $prodiColumn = null;

            if (Schema::hasColumn('log_pengajuan_judul', 'prodi')) {
                $prodiColumn = 'prodi';
            } elseif (Schema::hasColumn('log_pengajuan_judul', 'program_studi')) {
                $prodiColumn = 'program_studi';
            }

            $data = [
                $idColumn         => $request->id_pengajuan,
                'nim'             => $request->nim,
                'nama_mahasiswa'  => $request->nama_mahasiswa,
                'fakultas'        => $request->fakultas,
                'judul'           => $request->judul,
                'deskripsi'       => $request->deskripsi,
                'status'          => $request->status,
                'updated_at'      => now(),
            ];

            if ($prodiColumn) {
                $data[$prodiColumn] = $request->prodi;
            }

            $cekDataLama = DB::table('log_pengajuan_judul')
                ->where($idColumn, $request->id_pengajuan)
                ->first();

            if ($cekDataLama) {
                DB::table('log_pengajuan_judul')
                    ->where($idColumn, $request->id_pengajuan)
                    ->update($data);

                $pesanAction = 'Status pengajuan judul berhasil di-update menjadi ' . $request->status . ' di Pusat Data.';
            } else {
                $data['created_at'] = now();

                DB::table('log_pengajuan_judul')->insert($data);

                $pesanAction = 'Data pengajuan judul baru berhasil dicatat dan disinkronisasi ke Pusat Data.';
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